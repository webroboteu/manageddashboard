<?php

namespace Botble\webrobotdashboard\Repositories\Caches;

use Botble\webrobotdashboard\Repositories\Interfaces\MemberActivityLogInterface;
use Botble\Support\Repositories\Caches\CacheAbstractDecorator;

class MemberActivityLogCacheDecorator extends CacheAbstractDecorator implements MemberActivityLogInterface
{
    /**
     * {@inheritDoc}
     */
    public function getAllLogs($memberId, $paginate = 10)
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }
}
