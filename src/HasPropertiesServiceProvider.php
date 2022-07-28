<?php declare(strict_types=1);

namespace JoeCianflone\HasProperties;

use Illuminate\Support\ServiceProvider;

class HasPropertiesServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                $this->getConfigFile() => config_path('hasproperties.php'),
            ], 'config');
        }

        // $this->app->booted(function ($app): void {
        //     $app->make(SettingsProvider::class)->provide();
        // });
    }

    public function register(): void
    {
        $this->mergeConfigFrom(
            $this->getConfigFile(),
            'hasproperties'
        );
    }

    /**
     * @return string
     */
    protected function getConfigFile(): string
    {
        return __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'hasproperties.php';
    }
}
