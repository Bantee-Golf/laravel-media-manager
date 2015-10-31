<?php

namespace EMedia\MediaManager\Facades;


use Illuminate\Support\Facades\Facade;

class FileHandler extends Facade
{
	/**
	 * Get the registered name of the component.
	 *
	 * @return string
	 */
	protected static function getFacadeAccessor()
	{
		return 'emedia.mediaManager.fileHandler';
	}
}