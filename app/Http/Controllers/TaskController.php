<?php

namespace App\Http\Controllers;

use App\Helpers\Date;
use App\Models\Tasks;
use App\Repository\TaskRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Session\Session;

class TaskController extends BaseController
{
    /**
     * @var TaskRepository
     */
    private $repository;

    /**
     * @param TaskRepository $repository
     */
    public function __construct(TaskRepository $repository)
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
            return view('tasks.index');
        }

        $tasks = $this->repository->getAllPaginate($request);

        $rows = [];
        foreach ($tasks as $task) {
            $row = json_decode(json_encode($task), true);
            $row['date'] = Date::conversion($task->date);
            $row['created_at'] = Date::conversion($task->created_at);
            $row['updated_at_at'] = Date::conversion($task->updated_at);
            $row['sent_at'] = Date::conversion($task->sent_at);
            $row['edit'] = route('tasks.edit', ['id' => $task->id]);
            $row['delete'] = route('tasks.remove', ['id' => $task->id]);
            $row['link'] = (strlen($task->link)) ? "<a href='{$task->link}'>Click</a>" : null;
            $rows[] = $row;
        }

        return JsonResponse::create([
            'rows' => $rows,
            'total' => $tasks->total()
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
            $task = $this->repository->findById($id);

            if (!$task) {
                $request->session()->flash('message', "Task [$id] not found");
                return redirect('tasks');
            }

            return view('tasks.form', [
                'task' => $task
            ]);
        }

        $request->session()->flash('message', "Method not allowed");
        return redirect('tasks');
    }

    public function saveAction(Request $request)
    {
        $params = $request->all();
        unset($params['_token']);

        $routeBack = 'tasks.new';
        if (isset($params['id']) && (int) $params['id'] > 0) {
            $routeBack = 'tasks.edit';
        }

        if ($request->getMethod() == 'POST') {

            // saving data!
            $isValid = $this->repository->validateRequest($request);

            if (!is_bool($isValid)) {
                $request->session()->flash('message', "Invalid data, please check the following errors: ");
                $request->session()->flash('validationErrros', $isValid);

                return redirect()->route($routeBack, [$routeBack == 'tasks.edit' ? $params['id'] : '']);
            }

            //update
            if ($routeBack == 'tasks.edit') {
                $task = $this->repository->findById($params['id']);

                if (!$task) {
                    $request->session()->flash('message', "Task [{$params['id']}] not found");
                    return redirect('tasks');
                }


                $this->repository->update($params, $params['id']);
                $request->session()->flash('message', "Task [{$task->task}] updated successfully!");
                $request->session()->flash('success', true);
                return redirect('tasks');
            }

            //insert
            $tasks = new Tasks();
            $tasks->create($params);

            $request->session()->flash('message', "Successfully created task");
            $request->session()->flash('success', true);
            return redirect('tasks');
        }

        $request->session()->flash('message', "Method not allowed");
        return redirect('tasks');
    }

    /**
     * @return View
     */
    public function newAction()
    {
        $task = new Tasks();
        return view('tasks.form', [
            'task' => $task
        ]);
    }


    public function removeAction()
    {

    }

}
