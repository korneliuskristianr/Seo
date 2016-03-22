<?php namespace Grouphub\Seo;

use Illuminate\Support\ServiceProvider;
use Grouphub\Seo\Og\Protocol;
use Grouphub\Seo\Og\ProtocolInterface;
// use Grouphub\Seo\Opengraph\OpenGrahpProtocol;

class SeoServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = true;

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->package('grouphub/seo');

		$this->app->bind('Grouphub\Seo\Og\ProtocolInterface','Grouphub\Seo\Og\Protocol');
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app['seo.website'] = $this->app->share(function($app){
			// $protocol = $app['Grouphub\Seo\Og\ProtocolInterface'];
			// return $protocol;
			return $app->make('Grouphub\Seo\Og\ProtocolInterface');
		});

		$this->app['seo.article'] = $this->app->share(function($app){
			return $app->make('Grouphub\Seo\Og\Article');
		});

		$this->app['seo.image'] = $this->app->share(function($app){			
			return $app->make('Grouphub\Seo\Og\Image');
		});

		$this->app['seo.profile'] = $this->app->share(function($app){			
			return $app->make('Grouphub\Seo\Og\Profile');
		});

		$this->app['seo.audio'] = $this->app->share(function($app){			
			return $app->make('Grouphub\Seo\Og\Audio');
		});

		$this->app['seo.video'] = $this->app->share(function($app){			
			return $app->make('Grouphub\Seo\Og\Video');
		});

		// Shorcut for facade aliasing
		$this->app->booting(function() {
			$loader = \Illuminate\Foundation\AliasLoader::getInstance();		
			$loader->alias('SeoWebsite', 'Grouphub\Seo\Facades\SeoWebsite');
			$loader->alias('SeoArticle', 'Grouphub\Seo\Facades\SeoArticle');
			$loader->alias('SeoProfile', 'Grouphub\Seo\Facades\SeoProfile');
			$loader->alias('SeoAudio', 'Grouphub\Seo\Facades\SeoAudio');
			$loader->alias('SeoImage', 'Grouphub\Seo\Facades\SeoImage');
			$loader->alias('SeoVideo', 'Grouphub\Seo\Facades\SeoVideo');
		});
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('seo.website', 'seo.image', 'seo.profile', 'seo.video', 'seo.audio');
	}

}
