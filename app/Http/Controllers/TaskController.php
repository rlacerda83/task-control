<?php

namespace App\Http\Controllers;

use App\Helpers\Date;
use App\Models\Tasks;
use App\Services\TaskProcessor;
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
            $row['link'] = (strlen($task->link)) ? "<a target='_blank' href='{$task->link}'>Click</a>" : null;
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

            $task->date = Date::conversion($task->date);
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
        $params['date'] = Date::conversion($params['date']);

        $request->replace($params);

        unset($params['_token'], $params['q']);

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

                return redirect()
                    ->route($routeBack, [$routeBack == 'tasks.edit' ? $params['id'] : null])
                    ->withInput()
                    ->with('validationErros', $isValid);
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
        $task->status = Tasks::STATUS_PENDING;
        return view('tasks.form', [
            'task' => $task
        ]);
    }


    public function removeAction(Request $request, $id)
    {
        $task = $this->repository->findById($id);

        if (!$task) {
            $request->session()->flash('message', "Task [$id] not found");
            return redirect('tasks');
        }

        $taskDescription = $task->task;
        Tasks::destroy($id);

        $request->session()->flash('message', "Successfully removed task [{$taskDescription}]");
        $request->session()->flash('success', true);

        return redirect('tasks');
    }

    public function processAction(Request $request)
    {
        $success = false;
        $message = '';
        try {
            $processor = new TaskProcessor($request->get('password', null));
            $success = $processor->process();

        } catch (\Exception $e) {
            $message = $e->getMessage();
        }

        return JsonResponse::create([
            'success' => $success,
            'message' => $message
        ]);
    }

}
