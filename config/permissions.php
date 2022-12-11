<?php

return [
    [
        'name' => 'Members',
        'flag' => 'member.index',
    ],
    [
        'name' => 'Create',
        'flag' => 'member.create',
        'parent_flag' => 'member.index',
    ],
    [
        'name' => 'Edit',
        'flag' => 'member.edit',
        'parent_flag' => 'member.index',
    ],
    [
        'name' => 'Delete',
        'flag' => 'member.destroy',
        'parent_flag' => 'member.index',
    ],
    [
        'name' => 'Projects',
        'flag' => 'project.index',
    ],
    [
        'name' => 'Create',
        'flag' => 'project.create',
        'parent_flag' => 'project.index',
    ],
    [
        'name' => 'Edit',
        'flag' => 'project.edit',
        'parent_flag' => 'project.index',
    ],
    [
        'name' => 'Delete',
        'flag' => 'project.destroy',
        'parent_flag' => 'project.index',
    ]
    ,
    [
        'name' => 'Tasks',
        'flag' => 'task.index',
    ],
    [
        'name' => 'Create',
        'flag' => 'task.create',
        'parent_flag' => 'task.index',
    ],
    [
        'name' => 'Edit',
        'flag' => 'task.edit',
        'parent_flag' => 'task.index',
    ],
    [
        'name' => 'Delete',
        'flag' => 'task.destroy',
        'parent_flag' => 'task.index',
    ]
];
