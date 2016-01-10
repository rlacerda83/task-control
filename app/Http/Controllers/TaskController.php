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
            return view('tasks.form', [

            ]);
        }
    }

    public function removeAction()
    {

    }
}
