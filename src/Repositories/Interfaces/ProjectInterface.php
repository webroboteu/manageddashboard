<?php
namespace Botble\webrobotdashboard\Repositories\Interfaces;
use Botble\Support\Repositories\Interfaces\RepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

interface ProjectInterface extends RepositoryInterface
{
    /**
     * @param int $paginate
     * @return Collection
     */
    public function getAllProjects($paginate = 10);
      /**

     * @param int $projectId
     * @return Project
     */
    public function getProjectById($projectId);
  /**

     * @param int $bookmakerId
     * @return 
     */
    public function deleteProject($projectId);
    /**

     * @param array [$name, $url]
     * @return 
     */
    public function createProject(array $Details);
     /**
     * @param int $bookmakerId
     * @param array [$name, $url]
     * @return 
     */
    public function updateProject($projectId, array $newDetails);
}