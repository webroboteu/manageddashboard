<?php
namespace Botble\webrobotdashboard\Repositories\Interfaces;
use Botble\Support\Repositories\Interfaces\RepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

interface TaskInterface extends RepositoryInterface
{
    /**
     * @param int $paginate
     * @return Collection
     */
    public function getAllTasks($paginate = 10);
      /**

     * @param int $projectId
     * @return Project
     */
    public function getTaskById($taskId);
  /**

     * @param int $bookmakerId
     * @return 
     */
    public function deleteTask($taskId);
    /**

     * @param array [$name, $url]
     * @return 
     */
    public function createTask(array $Details);
     /**
     * @param int $bookmakerId
     * @param array [$name, $url]
     * @return 
     */
    public function updateProject($taskId, array $newDetails);
}