<?php

namespace App\Repositories\Task;

use App\Models\Task;
use App\Repositories\Task\TaskRepository;

class EloquentTask implements TaskRepository
{
    /**
     * {@inheritdoc}
     */
    public function all(){
        return Task::all();
    }


    /**
     * {@inheritdoc}
     */
    public function find($id)
    {
        return Task::find($id);
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        $task = Task::create($data);

        return $task;
    }

    /**
     * {@inheritdoc}
     */
    public function update($id, array $data)
    {
        $task = $this->find($id);

        $task->update($data);

        return $task;
    }

    /**
     * {@inheritdoc}
     */
    public function delete($id)
    {
        $task = $this->find($id);

        return $task->delete();
    }

    /**
     * @param $perPage
     * @param null $status
     * @param null $searchFrom
     * @param $searchTo
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|mixed
     */
    public function paginate($perPage, $search = null, $status = null)
    {
        $query = Task::query();

        if ($status) {
            $query->where('status', $status);
        }

        // if ($search) {
        //     (new StageKeywordSearch)($query, $search);
        // }

        $result = $query->orderBy('id', 'desc')
            ->paginate($perPage);

        if ($search) {
            $result->appends(['search' => $search]);
        }

        return $result;
    }

    public function paginateWithTrashed($perPage, $search = null, $status = null){
        $query = Task::query()->withTrashed();

        if ($status) {
            $query->where('status', $status);
        }

        // if ($search) {
        //     (new StageKeywordSearch)($query, $search);
        // }

        $result = $query->orderBy('id', 'desc')
            ->paginate($perPage);

        if ($search) {
            $result->appends(['search' => $search]);
        }

        return $result;
    }

    public function restoreAll(){
        Task::withTrashed()->restore();
    }
}
