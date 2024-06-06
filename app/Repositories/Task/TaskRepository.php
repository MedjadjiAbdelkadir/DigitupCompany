<?php

namespace App\Repositories\Task;

interface TaskRepository
{
    /**
     * Get all available Task.
     * @return mixed
     */
    public function all();




    /**
     * {@inheritdoc}
     */
    public function create(array $data);

    /**
     * {@inheritdoc}
     */
    public function update($id, array $data);

    /**
     * {@inheritdoc}
     */
    public function delete($id);

    /**
     * Paginate Task.
     *
     * @param $perPage
     * @param null $search
     * @param null $status
     * @return mixed
     */
    public function paginate($perPage, $search = null, $status = null);

    public function paginateWithTrashed($perPage, $search = null, $status = null);

    /**
     * Restore all deleted tasks.
     */
    public function restoreAll();
}
