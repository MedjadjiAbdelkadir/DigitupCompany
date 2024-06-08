<?php

namespace App\Http\Controllers\Api\Task;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Task\TaskResource;
use App\Repositories\Task\TaskRepository;
use App\Http\Requests\Task\StoreTaskRequest;
use App\Http\Resources\Task\PaginateTaskCollection;

class TaskController extends Controller
{
    private $tasks;

    /**
     * TaskController constructor.
     * @param TaskRepository $tasks
     */
    public function __construct(TaskRepository $tasks)
    {
        $this->tasks = $tasks;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (auth()->user()->role === 'admin') {
            $tasks = $this->tasks->paginateWithTrashed(10);
            return response()->json([
                'status' => true,
                'data'  => new PaginateTaskCollection($tasks)
            ], 200);
        }

        $tasks = $this->tasks->paginate(10);
        return response()->json([
            'status' => true,
            'data'  => new PaginateTaskCollection($tasks)
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTaskRequest $request)
    {
        try {

            $data = array_replace($request->all(), [
                'user_id' =>  auth()->id()
            ]);
            $task = $this->tasks->create($data);
            return response()->json([
                'status'  => true,
                'message' => 'The Task has been created successfully',
                'data' => new TaskResource($task)
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'status'  => 0,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $task = $this->tasks->find($id);
        $this->authorize('view', $task);
        if (!$task) {
            return response()->json([
                "status"  => false,
                "message" => "Task not found."
            ], 404);
        }
        return response()->json([
            "status"  => true,
            "data"    => new TaskResource($task)
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $task = $this->tasks->find($id);
        if (!$task) {
            return response()->json([
                "status"  => false,
                "message" => "Task not found."
            ], 404);
        }
        $this->authorize('update', $task);
        $taskupdate = $this->tasks->update($id, $request->all());
        return response()->json([
            "status"  => true,
            "message" => "The Task has been updated.",
            "data"    => new TaskResource($taskupdate)
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $task = $this->tasks->find($id);
            if (!$task) {
                return response()->json([
                    "status"  => false,
                    "message" => "Task not found."
                ], 404);
            }
            $this->authorize('delete', $task);
            $taskDelete = $this->tasks->delete($id);
            if (!$taskDelete) {
                return response()->json([
                    'status' => false,
                    'message' => 'Server Error. Can\'t delete the task at this time.',
                ], 500);
            } else {
                return response()->json([
                    'status' => true,
                    'message' => 'Task deleted successfully.'
                ]);
            }
        } catch (Exception $e) {
            return response()->json([
                'status'  => 0,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function restore()
    {
        // $this->authorize('restore');
        $this->tasks->restoreAll();
        return response()->json([
            'status' => true,
            'message' => 'Task deleted restored successfully.'
        ]);
    }
}
