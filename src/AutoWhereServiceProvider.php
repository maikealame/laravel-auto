<?php
namespace AutoWhere;

use Illuminate\Support\ServiceProvider;

class AutoWhereServiceProvider extends ServiceProvider
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
        $this->app->singleton('\AutoWhere\Contracts\AutoWhereInterface', function ($app)
        {
            return new \AutoWhere\Auto();
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
            return "<?php echo \AutoWhere\AutoWhereBlade::script(array ({$expression}));?>";
        });

        $blade->directive('autowherefilter', function ($expression) {
            if ($expression[0] === '(') $expression = trim($expression, '()');
            return "<?php echo \AutoWhere\AutoWhereBlade::filter(array ({$expression}));?>";
        });

        $blade->directive('autowheresort', function ($expression) {
            if ($expression[0] === '(') $expression = trim($expression, '()');
            return "<?php echo \AutoWhere\AutoWhereBlade::sort(array ({$expression}));?>";
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['maikealame\autoWhere\Contracts\AutoWhereInterface'];
    }

}
