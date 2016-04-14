<?php

namespace App\Http\Controllers;

use App\Helpers\Date;
use App\Helpers\UserLogged;
use App\Models\HoursControl;
use App\Repository\HoursControlRepository;
use App\Services\HoursControl as HoursControlService;
use App\Services\Reports;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\View\View;

class HoursControlController extends BaseController
{
    /**
     * @var HoursControlRepository
     */
    private $repository;

    /**
     * @param HoursControlRepository $repository
     */
    public function __construct(HoursControlRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param Request $request
     * @return View|\Symfony\Component\HttpFoundation\Response|static
     */
    public function indexAction(Request $request)
    {
        if ($request->getMethod() == 'GET') {
            return view('hours-control.index');
        }

        $hourControls = $this->repository->getAllPaginate($request, 10);

        $rows = [];
        foreach ($hourControls as $hourControl) {
            $row = json_decode(json_encode($hourControl), true);
            $row['day'] = Date::conversion($hourControl->day);
            $row['edit'] = route('hours-control.edit', ['id' => $hourControl->id]);
            $row['delete'] = route('hours-control.remove', ['id' => $hourControl->id]);
            $rows[] = $row;
        }

        return JsonResponse::create([
            'rows' => $rows,
            'total' => $hourControls->total()
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
            $hourControl = $this->repository->findById($id);

            if (!$hourControl) {
                $request->session()->flash('message', "Register [$id] not found");
                return redirect('hours-control');
            }

            $hourControl->day = Date::conversion($hourControl->day);
            return view('hours-control.form', [
                'hourControl' => $hourControl
            ]);
        }

        $request->session()->flash('message', "Method not allowed");
        return redirect('hours-control');
    }

    public function saveAction(Request $request)
    {
        $params = $request->all();
        $params['day'] = Date::conversion($params['day']);
        $params['user_id'] = UserLogged::getId();

        $request->replace($params);

        unset($params['_token'], $params['q']);

        $routeBack = $request->get('redirect', false);
        if (!$routeBack) {
            $routeBack = 'hours-control.new';
            if (isset($params['id']) && (int) $params['id'] > 0) {
                $routeBack = 'hours-control.edit';
            }
        }

        if ($request->getMethod() == 'POST') {

            // saving data!
            $isValid = $this->repository->validateRequest($request);

            if (!is_bool($isValid)) {
                $request->session()->flash('message', "Invalid data, please check the following errors: ");
                $request->session()->flash('validationErrros', $isValid);

                $formattedDate = \Datetime::createFromFormat('Y-m-d', $request->get('day'));
                $request->replace(['day' => $formattedDate->format('d/m/Y')]);

                return redirect()
                    ->route($routeBack, [$routeBack == 'hours-control.edit' ? $params['id'] : null])
                    ->withInput()
                    ->with('validationErrors', $isValid);
            }

            //update
            if ($routeBack == 'hours-control.edit') {
                $hourControl = $this->repository->findById($params['id']);

                if (!$hourControl) {
                    $request->session()->flash('message', "Register [{$params['id']}] not found");
                    return redirect('hours-control');
                }

                $hourControl = HoursControl::findOrNew($params['id']);
                $hourControl->fill($params);
                $hourControl->update();

                $request->session()->flash('message', "Register [{$hourControl->task}] updated successfully!");
                $request->session()->flash('success', true);
                return redirect('hours-control');
            }

            //insert
            $hourControls = new HoursControl();
            $hourControls->create($params);

            $request->session()->flash('message', "Successfully created register");
            $request->session()->flash('success', true);
            $redirect = $request->get('redirect', false) != false ? $request->get('redirect') : 'hours-control';
            return redirect()->route($redirect);
        }

        $request->session()->flash('message', "Method not allowed");
        return redirect('hours-control');
    }

    /**
     * @return View
     */
    public function newAction()
    {
        $now = Carbon::now();
        $hourControl = new HoursControl();
        $hourControl->day = $now->format('d/m/Y');
        $hourControl->time = $now->format('H:i:s');

        return view('hours-control.form', [
            'hourControl' => $hourControl
        ]);
    }

    public function removeAction(Request $request, $id)
    {
        $hourControl = $this->repository->findById($id);

        if (!$hourControl) {
            $request->session()->flash('message', "Register [$id] not found");
            return redirect('hours-control');
        }

        HoursControl::destroy($id);

        $request->session()->flash('message', "Successfully removed register [{$id}]");
        $request->session()->flash('success', true);

        return redirect('hours-control');
    }

    public function reportAction(Request $request)
    {
        $hours = [];
        $startDate = Carbon::now()->firstOfMonth()->format('Y-m-d');
        $endDate = Carbon::now()->endOfMonth()->format('Y-m-d');

        if ($request->input('startDate') && $request->input('endDate')) {
            $startDate = Date::conversion($request->input('startDate'));
            $endDate = Date::conversion($request->input('endDate'));
            $service = new HoursControlService();
            $reports = new Reports();
            $hours = $service->getHoursByDate($startDate, $endDate);

            $hours['workingHours'] = $reports->getTotalWorkingHoursByDate($startDate, $endDate);
        }

        return view('hours-control.report', [
            'hours' => $hours,
            'startDate' => Date::conversion($startDate),
            'endDate' => Date::conversion($endDate)
        ]);
    }
}
