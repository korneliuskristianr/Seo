<?php namespace Grouphub\Seo\Facades;

use Illuminate\Support\Facades\Facade;

class SeoWebsite extends Facade { 

	/**
	 * Get registered name of components.
	 *
	 * @return string
	 */
	public static function getFacadeAccessor() { return 'seo.website'; }
}