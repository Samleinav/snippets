<?php

return [
    [
        'name' => 'Snippets',
        'flag' => 'snippets.index',
    ],
    [
        'name' => 'Create',
        'flag' => 'snippets.create',
        'parent_flag' => 'snippets.index',
    ],
    [
        'name' => 'Edit',
        'flag' => 'snippets.edit',
        'parent_flag' => 'snippets.index',
    ],
    [
        'name' => 'Delete',
        'flag' => 'snippets.destroy',
        'parent_flag' => 'snippets.index',
    ],
];
