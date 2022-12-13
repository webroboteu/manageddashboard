<?php
namespace Botble\webrobotdashboard\Repositories\Caches;
use Botble\webrobotdashboard\Repositories\Interfaces\ProjectInterface;
use Botble\Support\Repositories\Caches\CacheAbstractDecorator;
class ProjectCacheDecorator extends CacheAbstractDecorator implements ProjectInterface
{
    /**
    * {@inheritDoc}
    */
    public function getAllProjects($paginate = 10)
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }
    /**
    * {@inheritDoc}
    */
   public function getProjectById($projectId)
   {
    return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
   }
   /**
    * {@inheritDoc}
    */
   public function deleteProject($projectId)
   {
    return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
   }
    /**
    * {@inheritDoc}
    */
   public function createProject(array $Details)
   {
    return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
   }
    /**
    * {@inheritDoc}
    */
   public function updateProject($projectId, array $newDetails)
   {
    return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
   }
}
