<?php

namespace Dpb\Sanctuary\Providers;

use Dpb\Sanctuary\Models\Ghost;
use Illuminate\Config\Repository as ConfigRepository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Override;

class SanctuaryServiceProvider extends ServiceProvider
{
    private ConfigRepository $config;

    /**
     * Create a new service provider instance.
     *
     * @param  Application  $app
     */
    public function __construct(
        Application $app
    ) {
        parent::__construct($app);
        $this->config = $app->make(ConfigRepository::class);
    }

    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../../config/sanctuary.php', 'sanctuary');
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');
        $this->configureGuard();
        $this->registerRoutes();
        $this->resolveRelations();
    }

    /**
     * Register the unified package API routes.
     * Guarding is moved to the routing file for fine-grained control (guest vs auth).
     */
    private function registerRoutes(): void
    {
        Route::prefix('api/sanctuary')
            ->middleware(['api'])
            ->group(__DIR__ . '/../../routes/api.php');
    }

    private function configureGuard(): void
    {
        $this->config->set("auth.providers.{$this->config->get('sanctuary.provider_name', 'sanctuary_ghosts')}", [
            'driver' => 'eloquent',
            'model' => Ghost::class,
        ]);

        $this->config->set("auth.guards.{$this->config->get('sanctuary.auth_guard', 'sanctuary_api')}", [
            'driver' => 'sanctum',
            'provider' => $this->config->get('sanctuary.provider_name', 'sanctuary_ghosts'),
        ]);
    }

    private function resolveRelations(): void
    {
        Ghost::resolveRelationUsing(
            'user', fn(Ghost $ghost) => $ghost->belongsTo($this->config->get('sanctuary.user_model'), 'user_id', 'id'));
    }
}