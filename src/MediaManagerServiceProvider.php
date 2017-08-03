<?php


namespace EMedia\MediaManager;


use Illuminate\Support\ServiceProvider;

class MediaManagerServiceProvider extends ServiceProvider {

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app->bind('emedia.mediaManager.fileHandler', function () {
			return $this->app->make('EMedia\MediaManager\Files\FileHandler');
		});

		$this->app->bind('emedia.mediaManager.imageHandler', function () {
			return $this->app->make('EMedia\MediaManager\Files\ImageHandler');
		});

		$this->app->register(\Intervention\Image\ImageServiceProvider::class);
	}
}