<?php

namespace App\Http\Controllers\System;

use App\Models\System\Permission;
use App\Models\System\Profile;
use App\Repository\System\Module\ActionRepository;
use App\Repository\System\PermissionRepository;
use App\Repository\System\ProfileRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\View\View;

class ProfileController extends BaseController
{
    /**
     * @var ProfileRepository
     */
    private $repository;

    /**
     * @var ActionRepository
     */
    private $actionRepository;

    /**
     * @var PermissionRepository
     */
    private $permissionRepository;

    /**
     * ProfileController constructor.
     * @param ProfileRepository $repository
     * @param ActionRepository $actionRepository
     * @param PermissionRepository $permissionRepository
     */
    public function __construct(
        ProfileRepository $repository,
        ActionRepository $actionRepository,
        PermissionRepository $permissionRepository
    )
    {
        $this->repository = $repository;
        $this->actionRepository = $actionRepository;
        $this->permissionRepository = $permissionRepository;
    }

    /**
     * @param Request $request
     * @return View|\Symfony\Component\HttpFoundation\Response|static
     */
    public function indexAction(Request $request)
    {
        if ($request->getMethod() == 'GET') {
            return view('system.profile.index');
        }

        $profiles = $this->repository->getAllPaginate($request, 10);

        $rows = [];
        foreach ($profiles as $profile) {
            $row = json_decode(json_encode($profile), true);
            $row['edit'] = route('system.profile.edit', ['id' => $profile->id]);
            $row['delete'] = route('system.profile.remove', ['id' => $profile->id]);
            $rows[] = $row;
        }

        return JsonResponse::create([
            'rows' => $rows,
            'total' => $profiles->total()
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
            $profile = $this->repository->findById($id);

            if (!$profile) {
                $request->session()->flash('message', "Profile [$id] não encontrado");
                return redirect()->route('system.user.list');
            }

            $actions = $this->actionRepository->getAll();
            $arrayActions = [];
            foreach ($actions as $action) {
                $arrayActions[] = [
                    'id' => $action->id,
                    'module' => $action->module->label,
                    'action' => $action->label,
                    'permission' => $this->repository->hasPermission($profile, $action)
                ];
            }

            return view('system.profile.form', [
                'profile' => $profile,
                'actions' => $arrayActions
            ]);
        }

        $request->session()->flash('message', "Method not allowed");
        return redirect()->route('system.profile.list');
    }

    public function saveAction(Request $request)
    {
        $params = $request->all();
        unset($params['_token'], $params['q']);

        $routeBack = $request->get('redirect', false);
        if (!$routeBack) {
            $routeBack = 'system.profile.new';
            if (isset($params['id']) && (int) $params['id'] > 0) {
                $routeBack = 'system.profile.edit';
            }
        }

        if ($request->getMethod() == 'POST') {

            // saving data!
            $isValid = $this->repository->validateRequest($request);
            if (!is_bool($isValid)) {
                $request->session()->flash('message', "Dados inválidos, verifique os erros abaixo: ");
                $request->session()->flash('validationErrors', $isValid);

                return redirect()
                    ->route($routeBack, [$routeBack == 'system.profile.edit' ? $params['id'] : null])
                    ->withInput()
                    ->with('validationEros', $isValid);
            }

            //update
            if ($routeBack == 'system.profile.edit') {
                $profile = Profile::find($params['id']);

                if (!$profile) {
                    $request->session()->flash('message', "Perfil [{$params['id']}] não encontrado");
                    return redirect()->route('system.profile.list');
                }

                $profile->fill($params);
                $profile->update();

                $request->session()->flash('message', "Perfil [{$profile->name}] alterado com sucesso!");
                $request->session()->flash('success', true);
                return redirect()->route('system.profile.list');
            }

            //insert
            Profile::create($params);

            $request->session()->flash('message', "Perfil cadastrado com sucesso");
            $request->session()->flash('success', true);
            $redirect = $request->get('redirect', false) != false ? $request->get('redirect') : 'system.profile.list';
            return redirect()->route($redirect);
        }

        $request->session()->flash('message', "Method not allowed");
        return redirect()->route('system.profile.list');
    }

    public function savePermissionAction(Request $request)
    {
        $idProfile = $request->get('id', 0);
        $profile = Profile::find($idProfile);

        if (!$profile) {
            $request->session()->flash('message', "Perfil não encontrado.");

            return redirect()
                ->route('system.profile.edit', [$idProfile])
                ->withInput();
        }

        try {
            $newValue = (int) $request->get('value');
            $idAction = $request->get('pk');

            if ($newValue == 0) {
                $profile->actions()->detach($idAction);
            }

            if ($newValue == 1) {
                $profile->actions()->attach($idAction);
            }

            $return = [
                'success' => true,
                'message' => 'Permissão salva com sucesso'
            ];
        } catch (\Exception $e) {
            $return = [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }

        return response()->json($return);
    }

    /**
     * @return View
     */
    public function newAction()
    {
        $profile = new Profile();
        return view('system.profile.form', [
            'profile' => $profile
        ]);
    }

    public function removeAction(Request $request, $id)
    {
        $profile = $this->repository->findById($id);

        if (!$profile) {
            $request->session()->flash('message', "Perfil [$id] não encontrado");
            return redirect()->route('system.profile.list');
        }

        $profileName = $profile->name;
        Profile::destroy($id);

        $request->session()->flash('message', "Perfil [{$profileName}] removido com sucesso");
        $request->session()->flash('success', true);

        return redirect()->route('system.profile.list');
    }
}
