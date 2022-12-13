<?php
namespace Botble\webrobotdashboard\Models;
use Html;
use Botble\Base\Models\BaseModel;
use Botble\Base\Traits\EnumCastable;
use Botble\webrobotdashboard\Enums\FrequencyEnum;
use Botble\webrobotdashboard\Enums\StatusEnum;
use Botble\webrobotdashboard\Models\Member;
use Botble\webrobotdashboard\Models\Task;
class Project extends BaseModel
{
    use EnumCastable;
    /**
     * @var string
     */
    protected $table = 'member_projects';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'frequency',
        'status',
        'member_id'
    ];


    /**
     * @var array
     */
    protected $casts = [
        'status' => StatusEnum::class,
        'frequency' => FrequencyEnum::class
    ];  

    /**
    * @return BelongsToMany
    */
    public function tasks(): BelongsToMany
    {
        return $this->belongsToMany(Task::class, 'project_tasks');
    }

      /**
    * @return BelongsTo
    * @deprecated
    */
    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class)->withDefault();
    }
}