<?php
namespace Botble\webrobotdashboard\Repositories\Eloquent;
use Botble\webrobotdashboard\Repositories\Interfaces\ProjectInterface;
use Botble\Support\Repositories\Eloquent\RepositoriesAbstract;
use Botble\webrobotdashboard\Model\Project;

class ProjectRepository extends RepositoriesAbstract implements ProjectInterface
{
    /**
     * {@inheritDoc}
     */
    public function getAllProjects($paginate = 10)
    {
        return Project::all();
    }
    /**
     * {@inheritDoc}
     */
    public function getProjetById($projectId)
    {
        return Project::findOrFail($projectId);
    }
    /**
     * {@inheritDoc}
     */
    public function deleteProject($projectId)
    {
        Project::destroy($projectId);
    }
    /**
     * {@inheritDoc}
     */
    public function createProject(array $Details)
    {
        return Project::create($Details);
    }
    /**
     * {@inheritDoc}
     */
    public function updateProject($projectId, array $newDetails)
    {
        return Project::whereId($projectId)->update($newDetails);
    }
}
