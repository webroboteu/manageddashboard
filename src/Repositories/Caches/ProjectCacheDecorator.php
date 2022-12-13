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
}
