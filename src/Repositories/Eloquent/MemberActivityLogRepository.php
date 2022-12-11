<?php

namespace Botble\webrobotdashboard\Repositories\Eloquent;

use Botble\webrobotdashboard\Repositories\Interfaces\MemberActivityLogInterface;
use Botble\Support\Repositories\Eloquent\RepositoriesAbstract;

class MemberActivityLogRepository extends RepositoriesAbstract implements MemberActivityLogInterface
{
    /**
     * {@inheritDoc}
     */
    public function getAllLogs($memberId, $paginate = 10)
    {
        return $this->model
            ->where('member_id', $memberId)
            ->latest('created_at')
            ->paginate($paginate);
    }
}
