<?php

namespace App\Http\Controllers\System;

use App\Helpers\Date;
use App\Models\System\User;
use App\Repository\System\ProfileRepository;
use App\Repository\System\UserRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class UserController extends BaseController
{
    /**
     * @var UserRepository
     */
    private $repository;

    /**
     * @var ProfileRepository
     */
    private $profileRepository;

    /**
     * UserController constructor.
     * @param UserRepository $repository
     * @param ProfileRepository $profileRepository
     */
    public function __construct(UserRepository $repository, ProfileRepository $profileRepository)
    {
        $this->repository = $repository;
        $this->profileRepository = $profileRepository;
    }

    /**
     * @param Request $request
     * @return View|\Symfony\Component\HttpFoundation\Response|static
     */
    public function indexAction(Request $request)
    {
        if ($request->getMethod() == 'GET') {
            return view('system.user.index');
        }

        $users = $this->repository->getAllPaginate($request, 10);

        $rows = [];
        foreach ($users as $user) {
            $row = json_decode(json_encode($user), true);
            $row['system_profile.name'] = $user->profile_name;
            $row['last_access'] = Date::conversion($user->last_access);
            $row['created_at'] = Date::conversion($user->created_at);
            $row['updated_at_at'] = Date::conversion($user->updated_at);
            $row['edit'] = route('system.user.edit', ['id' => $user->id]);
            $row['delete'] = route('system.user.remove', ['id' => $user->id]);
            $rows[] = $row;
        }

        return JsonResponse::create([
            'rows' => $rows,
            'total' => $users->total()
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return View
     */
    public function editAction(Request $request, $id)
    {
        if ($request->getMethod() == 'GET') {
            $user = $this->repository->findById($id);

            if (!$user) {
                $request->session()->flash('message', "Usuário [$id] não encontrado");
                return redirect()->route('system.user.list');
            }

            $profiles = $this->getProfiles();
            return view('system.user.form', [
                'user' => $user,
                'profiles' => $profiles
            ]);
        }

        $request->session()->flash('message', "Method not allowed");
        return redirect()->route('system.user.list');
    }

    protected function getProfiles()
    {
        return $this->profileRepository->getAll();

    }

    public function saveAction(Request $request)
    {
        $params = $request->all();
        unset($params['_token'], $params['q']);

        $routeBack = $request->get('redirect', false);
        if (!$routeBack) {
            $routeBack = 'system.user.new';
            if (isset($params['id']) && (int) $params['id'] > 0) {
                $routeBack = 'system.user.edit';
            }
        }

        if ($request->getMethod() == 'POST') {

            // saving data!
            $isValid = $this->repository->validateRequest($request);
            if (!is_bool($isValid)) {
                $request->session()->flash('message', "Dados inválidos, verifique os erros abaixo: ");
                $request->session()->flash('validationErrors', $isValid);

                return redirect()
                    ->route($routeBack, [$routeBack == 'user.edit' ? $params['id'] : null])
                    ->withInput()
                    ->with('validationEros', $isValid);
            }

            //update
            if ($routeBack == 'system.user.edit') {
                $user = $this->repository->findById($params['id']);

                if (!$user) {
                    $request->session()->flash('message', "Usuário [{$params['id']}] não encontrado");
                    return redirect()->route('system.user.list');
                }

                $user = User::findOrNew($params['id']);
                unset($params['password']);
                $user->fill($params);
                $user->update();

                $request->session()->flash('message', "Usuário [{$user->name}] alterado com sucesso!");
                $request->session()->flash('success', true);
                return redirect()->route('system.user.list');
            }

            //insert
            $user = new User();
            $params['password'] = Hash::make($params['password']);
            $user->create($params);

            $request->session()->flash('message', "Usuário cadastrado com sucesso");
            $request->session()->flash('success', true);
            $redirect = $request->get('redirect', false) != false ? $request->get('redirect') : 'system.user.list';
            return redirect()->route($redirect);
        }

        $request->session()->flash('message', "Method not allowed");
        return redirect()->route('system.user.list');
    }

    /**
     * @return View
     */
    public function newAction()
    {
        $user = new User();
        $user->status = User::STATUS_ACTIVE;
        $profiles = $this->getProfiles();
        return view('system.user.form', [
            'user' => $user,
            'profiles' => $profiles
        ]);
    }

    public function removeAction(Request $request, $id)
    {
        $user = $this->repository->findById($id);

        if (!$user) {
            $request->session()->flash('message', "Usuário [$id] não encontrado");
            return redirect()->route('system.user.list');
        }

        $userName = $user->name;
        User::destroy($id);

        $request->session()->flash('message', "Usuário [{$userName}] removido com sucesso");
        $request->session()->flash('success', true);

        return redirect()->route('system.user.list');
    }
}
