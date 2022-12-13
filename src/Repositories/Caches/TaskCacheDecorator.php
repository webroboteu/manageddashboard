<?php
namespace Botble\webrobotdashboard\Repositories\Caches;
use Botble\webrobotdashboard\Repositories\Interfaces\TaskInterface;
use Botble\Support\Repositories\Caches\CacheAbstractDecorator;
class TaskCacheDecorator extends CacheAbstractDecorator implements TaskInterface
{
    /**
    * {@inheritDoc}
    */
    public function getAllTasks($paginate = 10)
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }
     /**
    * {@inheritDoc}
    */
    public function getTaskById($taskId)
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }
    /**
    * {@inheritDoc}
    */
    public function deleteTask($taskId)
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }
    /**
    * {@inheritDoc}
    */
    public function createTask(array $Details)
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }
     /**
    * {@inheritDoc}
    */
    public function updateTask($taskId, array $newDetails)
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }
}
