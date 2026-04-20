<?php

namespace Botble\Snippets\Models;

use Botble\Base\Casts\SafeContent;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Models\BaseModel;

class Snippets extends BaseModel
{
    protected $table = 'snippets';

    protected $fillable = [
        'name',
        'description',
        'code',
        'target',
        'route_rules',
        'status',
    ];

    protected $casts = [
        'status' => \Botble\Base\Enums\BaseStatusEnum::class,
        'target' => \Botble\Snippets\Enums\SnippetTargetEnum::class,
        'name' => SafeContent::class,
        'description' => SafeContent::class,
    ];
}
