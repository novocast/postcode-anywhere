<?php

namespace Novocast\PostCodeAnywhere;

use Illuminate\Support\ServiceProvider as ServiceProvider;

class PostCodeAnywhereServiceProvider extends ServiceProvider
{
    protected $defer = false;

    /**
     * @return Object $this
     */
    public function boot()
    {
        $this->publishes([__DIR__.'/config/pca.php' => config_path('/pca.php')]);

        return $this;

    }

    /**
     * @return Object $this
     */
    public function register()
    {
        $this->registerPostcodeAnywhere();
        $this->app->alias('pa', 'PoscodeAnywhere\PosctodeAnywhere');
            
        return $this;

    }
        
    /**
     * @return Object $this;
     */
    protected function registerPostcodeAnywhere()
    {
        $this->app->bind('PostCodeAnywhere', function ($app) {
            return new PostCodeAnywhere();

        });
        return $this;
    }
        
    /**
     * @return array services from provider
     */
    public function provides()
    {
        return array('PostCodeAnywhere');
    }
}
