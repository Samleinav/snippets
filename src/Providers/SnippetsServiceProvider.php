<?php

namespace Botble\Snippets\Providers;

use Botble\Base\Supports\ServiceProvider;
use Botble\Base\Traits\LoadAndPublishDataTrait;
use Botble\Base\Facades\DashboardMenu;
use Botble\Snippets\Models\Snippets;

class SnippetsServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function boot(): void
    {
        $this
            ->setNamespace('plugins/snippets')
            ->loadHelpers()
            ->loadAndPublishConfigurations(['permissions'])
            ->loadAndPublishTranslations()
            ->loadRoutes()
            ->loadAndPublishViews()
            ->loadMigrations();
            
        if ($this->app->runningInConsole()) {
            $this->commands([
                \Botble\Snippets\Commands\RescueCommand::class,
            ]);
        }
            
            if (! app()->runningInConsole() || app()->runningUnitTests()) {
                $this->app['events']->listen(\Illuminate\Routing\Events\RouteMatched::class, function (\Illuminate\Routing\Events\RouteMatched $event) {
                    try {
                        $isAdmin = request()->segment(1) === \Illuminate\Support\Facades\Config::get('core.base.general.admin_dir', 'admin');
                        $isApi = $event->request->is('api/*');
                        $isFrontend = ! $isAdmin && ! $isApi;

                        $snippets = \Botble\Snippets\Models\Snippets::where('status', \Botble\Base\Enums\BaseStatusEnum::PUBLISHED)->get();
                        
                        $evaluator = $this->app->make(\Botble\Snippets\Services\SnippetEvaluator::class);

                        foreach ($snippets as $snippet) {
                            $shouldRun = false;
                            switch ($snippet->target->getValue()) {
                                case \Botble\Snippets\Enums\SnippetTargetEnum::GLOBAL:
                                    $shouldRun = true;
                                    break;
                                case \Botble\Snippets\Enums\SnippetTargetEnum::ADMIN:
                                    $shouldRun = $isAdmin;
                                    break;
                                case \Botble\Snippets\Enums\SnippetTargetEnum::FRONTEND:
                                    $shouldRun = $isFrontend;
                                    break;
                                case \Botble\Snippets\Enums\SnippetTargetEnum::API:
                                    $shouldRun = $isApi;
                                    break;
                                case \Botble\Snippets\Enums\SnippetTargetEnum::CUSTOM:
                                    if ($snippet->route_rules) {
                                        $rules = array_map('trim', explode(',', $snippet->route_rules));
                                        foreach ($rules as $rule) {
                                            if ($event->request->routeIs($rule)) {
                                                $shouldRun = true;
                                                break;
                                            }
                                        }
                                    }
                                    break;
                            }

                            if ($shouldRun) {
                                ob_start();
                                $evaluator->execute($snippet);
                                $output = ob_get_clean();
                                
                                // Secure Mode: Prevent raw echos from corrupting JSON/AJAX responses
                                if (!empty(trim($output))) {
                                    if ($event->request->ajax() || $event->request->wantsJson()) {
                                        \Illuminate\Support\Facades\Log::debug("Snippet '{$snippet->name}' produced output during an AJAX request. Output was suppressed to prevent JSON corruption.");
                                    } else {
                                        echo $output;
                                    }
                                }
                            }
                        }
                    } catch (\Throwable $e) {
                        \Illuminate\Support\Facades\Log::error('Snippets Router Error: ' . $e->getMessage());
                    }
                });
            }
            
            DashboardMenu::default()->beforeRetrieving(function () {
                DashboardMenu::make()
                    ->registerItem([
                        'id' => 'cms-plugins-snippets',
                        'priority' => 5,
                        'name' => 'plugins/snippets::snippets.name',
                        'icon' => 'ti ti-code',
                        'url' => route('snippets.index'),
                        'permissions' => ['snippets.index'],
                    ])
                    ->registerItem([
                        'id' => 'cms-plugins-snippets-all',
                        'priority' => 1,
                        'parent_id' => 'cms-plugins-snippets',
                        'name' => 'plugins/snippets::snippets.name',
                        'icon' => null,
                        'url' => route('snippets.index'),
                        'permissions' => ['snippets.index'],
                    ])
                    ->registerItem([
                        'id' => 'cms-plugins-snippets-settings',
                        'priority' => 2,
                        'parent_id' => 'cms-plugins-snippets',
                        'name' => 'Settings',
                        'icon' => null,
                        'url' => route('snippets.settings'),
                        'permissions' => ['snippets.index'],
                    ]);
            });
    }
}
