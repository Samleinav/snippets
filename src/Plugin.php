<?php

namespace Botble\Snippets;

use Illuminate\Support\Facades\Schema;
use Botble\PluginManagement\Abstracts\PluginOperationAbstract;

class Plugin extends PluginOperationAbstract
{
    public static function remove(): void
    {
        Schema::dropIfExists('Snippets');
        Schema::dropIfExists('Snippets_translations');
    }
}
