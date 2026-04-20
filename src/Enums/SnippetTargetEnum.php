<?php

namespace Botble\Snippets\Enums;

use Botble\Base\Supports\Enum;
use Illuminate\Support\HtmlString;

class SnippetTargetEnum extends Enum
{
    public const GLOBAL = 'global';
    public const ADMIN = 'admin';
    public const FRONTEND = 'frontend';
    public const API = 'api';
    public const CUSTOM = 'custom';

    public static $langPath = 'plugins/snippets::snippets.targets';

    public function toHtml(): HtmlString|string
    {
        return match ($this->value) {
            self::GLOBAL => new HtmlString('<span class="badge bg-primary text-primary-fg">' . self::GLOBAL()->label() . '</span>'),
            self::ADMIN => new HtmlString('<span class="badge bg-warning text-warning-fg">' . self::ADMIN()->label() . '</span>'),
            self::FRONTEND => new HtmlString('<span class="badge bg-success text-success-fg">' . self::FRONTEND()->label() . '</span>'),
            self::API => new HtmlString('<span class="badge bg-info text-info-fg">' . self::API()->label() . '</span>'),
            self::CUSTOM => new HtmlString('<span class="badge bg-secondary text-secondary-fg">' . self::CUSTOM()->label() . '</span>'),
            default => parent::toHtml(),
        };
    }
}
