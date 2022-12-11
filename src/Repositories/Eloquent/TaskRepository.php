<?php
namespace Botble\webrobotdashboard\Repositories\Eloquent;
use Botble\webrobotdashboard\Repositories\Interfaces\TaskInterface;
use Botble\Support\Repositories\Eloquent\RepositoriesAbstract;
use Botble\webrobotdashboard\Model\Task;

class TaskRepository extends RepositoriesAbstract implements TaskInterface
{
    /**
     * {@inheritDoc}
     */
    public function getAllTasks($paginate = 10)
    {
        return Task::all();
    }
    /**
     * {@inheritDoc}
     */
    public function getTaskById($taskId)
    {
        return Task::findOrFail($taskId);
    }
    /**
     * {@inheritDoc}
     */
    public function deleteTask($taskId)
    {
        Task::destroy($taskId);
    }
    /**
     * {@inheritDoc}
     */
    public function createTask(array $Details)
    {
        return Task::create($Details);
    }
    /**
     * {@inheritDoc}
     */
    public function updateTask($taskId, array $newDetails)
    {
        return Task::whereId($taskId)->update($newDetails);
    }
}
