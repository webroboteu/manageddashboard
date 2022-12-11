<?php
namespace Botble\webrobotdashboard\Models;
use Html;
use Botble\Base\Models\BaseModel;
use Botble\webrobotdashboard\Models\Project;

class Task extends BaseModel
{
    /**
     * @var string
     */
    protected $table = 'member_tasks';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'date',
        'quantity',
        'dataset',
        'project_id'
    ];
  
     /**
    * @return BelongsTo
    * @deprecated
    */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class)->withDefault();
    }

    /**
    * @return BelongsToMany
    */
    public function sites(): BelongsToMany
    {
        return $this->belongsToMany(WebSite::class, 'task_sites');
    }
}
