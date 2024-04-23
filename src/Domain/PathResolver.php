<?php

namespace EMedia\MediaManager\Domain;

use EMedia\MediaManager\Exceptions\FailedToResolvePathException;
use ElegantMedia\PHPToolkit\Path;
use Illuminate\Support\Facades\Storage;

class PathResolver
{

	/**
	 *
	 * Resolve disk path from the disk
	 *
	 * @param $diskName
	 * @return string
	 * @throws FailedToResolvePathException
	 */
	public static function resolveDiskPath($diskName)
	{
		$disk = Storage::disk($diskName);

		if (!$disk) {
			throw new \InvalidArgumentException("The given disk name `{$diskName}` is invalid.");
		}

		$adapter = $disk->getDriver()->getAdapter();

		if ($adapter instanceof \League\Flysystem\Adapter\Local) {
			return $adapter->getPathPrefix();
		}

		throw new FailedToResolvePathException("Only paths from a local disk can be resolved.");
	}

	/**
	 *
	 * Resolve file path from the disk
	 *
	 * @param $diskName
	 * @param $filePath
	 *
	 * @return string
	 * @throws FailedToResolvePathException
	 */
	public static function resolvePath($diskName, $filePath)
	{
		return self::resolveDiskPath($diskName) . Path::withoutEndingSlash($filePath);
	}

	/**
	 *
	 * Resolve a URL to a filepath on a disk
	 *
	 * @param $diskName
	 * @param $filePath
	 *
	 * @return bool|mixed|string
	 */
	public static function resolveUrl($diskName, $filePath)
	{
		$path = Path::withoutStartingSlash($filePath);

		if (!empty($path)) {
			$disk = Storage::disk($diskName);
			$adapter = $disk->getDriver()->getAdapter();

			// build the URL based on a custom host
			$servingHost = config('filesystems.disks.' . $diskName . '.host');
			$servingProtocol = config('filesystems.disks.' . $diskName . '.protocol', 'http');

			if ($servingHost != null) {
				if ($servingHost === 'aws-static') {

					if ($adapter instanceof \League\Flysystem\AwsS3v3\AwsS3Adapter) {
						// https://s3-ap-southeast-2.amazonaws.com/dreamjobs-dev/images/content/20161210/2GhojsckzPsf6iN.png
						$assetPath = $disk->url($path);

						// http://[bucket].s3-website-[region].amazonaws.com/filepath
						// http://dreamjobs-dev.s3-website-ap-southeast-2.amazonaws.com/images/content/20161210/2GhojsckzPsf6iN.png
						$bucket = $adapter->getBucket();
						$hostPath = preg_replace(['~https://s3-~', '~/' . $bucket . '/~'],
							[$servingProtocol .'://'.$bucket.'.s3-website-', '/'], $assetPath);
						if (!empty($hostPath)) {
							return $hostPath;
						}
					}
				} else {
					$host = Path::withEndingSlash($servingHost);
					return $servingProtocol . '://' . $host . $path;
				}
			}

			// if this is for the 'public' disk, return the public URL
			if ($diskName === 'public_access') {
				return url($path);
			}

			return $disk->url($path);
		}

		return false;
	}


	/**
	 *
	 * Get an pre-signed URL for temporary access
	 *
	 * @param        $disk
	 * @param        $imagePath
	 * @param string $expiry
	 *
	 * @return string
	 */
	public function getPresignedUrl($disk, $imagePath, $expiry = '+10 minutes')
	{
		$s3 = \Storage::disk($disk);
		$adapter = $s3->getDriver()->getAdapter();

		if ($adapter instanceof \League\Flysystem\AwsS3v3\AwsS3Adapter) {

			$client  = $adapter->getClient();

			$command = $client->getCommand('GetObject', [
				'Bucket' => config('filesystems.disks.' . $disk . '.bucket'),
				'Key'    => $imagePath,
			]);

			$request = $client->createPresignedRequest($command, $expiry);

			return (string) $request->getUri();
		}

		return $imagePath;
	}

	/**
	 *
	 * Add a file name suffix to a given path
	 *
	 * @param $filePath
	 * @param $suffix
	 * @return string
	 */
	public static function addNameSuffix($filePath, $suffix)
	{
		$pathinfo = pathinfo($filePath);

		$output = [];
		if ($pathinfo['dirname'] !== '.') {
			$output[] = $pathinfo['dirname'];
			$output[] = '/';
		}

		$output[] = $pathinfo['filename'] . '_';
		$output[] = $suffix;
		$output[] = '.' . $pathinfo['extension'];

		return implode('', $output);
	}

}
