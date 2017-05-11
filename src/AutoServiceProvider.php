<?php
namespace Auto;

use Illuminate\Support\ServiceProvider;

class AutoServiceProvider extends ServiceProvider
{

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $config_path = __DIR__ . '/config/autowhere.php';
        $this->publishes([$config_path => config_path('autowhere.php')], 'autowhere');

        $this->registerBladeExtensions();
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $config_path = __DIR__ . '/config/autowhere.php';
        $this->mergeConfigFrom($config_path, 'autowhere');

        $this->registerAuto();
    }

    /**
     * Register Autowhere Contract.
     *
     * @return void
     */
    private function registerAuto()
    {
        $this->app->singleton('\Auto\Contracts\AutoInterface', function ($app)
        {
            return new \Auto\Auto();
        });
    }

    /**
     * Register Blade extensions.
     *
     * @return void
     */
    protected function registerBladeExtensions()
    {
        $blade = $this->app['view']->getEngineResolver()->resolve('blade')->getCompiler();


        $blade->directive('autowherescript', function ($expression) {
            if ($expression[0] === '(') $expression = trim($expression, '()');
            return "<?php echo \Auto\AutoWhereBlade::script(array ({$expression}));?>";
        });

        $blade->directive('autowherefilter', function ($expression) {
            if ($expression[0] === '(') $expression = trim($expression, '()');
            return "<?php echo \Auto\AutoWhereBlade::filter(array ({$expression}));?>";
        });

        $blade->directive('autosort', function ($expression) {
            if ($expression[0] === '(') $expression = trim($expression, '()');
            return "<?php echo \Auto\AutoSortBlade::sort(array ({$expression}));?>";
        });

        $blade->directive('autopages', function ($expression) {
            if ($expression[0] === '(') $expression = trim($expression, '()');
            return "<?php echo \Auto\AutoPageBlade::pages(array ({$expression}));?>";
        });

        $blade->directive('autopagesasync', function ($expression) {
            if ($expression[0] === '(') $expression = trim($expression, '()');
            return "<?php echo \Auto\AutoPageBlade::async(array ({$expression}));?>";
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['maikealame\laravel-auto\Contracts\AutoInterface'];
    }

}
