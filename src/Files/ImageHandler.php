<?php


namespace EMedia\MediaManager\Files;

use Intervention\Image\Facades\Image;
use Symfony\Component\HttpFoundation\File\MimeType\MimeTypeGuesser;

class ImageHandler
{

	/**
	 * Resize a given image to a maximum width
	 * If a new image path is not given, the original will be overwritten
	 *
	 * @param $imagePath
	 * @param int $maxWidth
	 * @param bool $newImagePath
	 * @return bool
	 */
	public function resizeImageToMaxWidth($imagePath, $maxWidth = 1200, $newImagePath = false)
	{
		$image = Image::make($imagePath);
		if ( ! $newImagePath) $newImagePath = $imagePath;

		$currentWidth = $image->width();
		if ($currentWidth > $maxWidth)
		{
			$image->resize($maxWidth, null, function($constraint) {
				$constraint->aspectRatio();
			});
			$image->save($newImagePath);
		}
		else
		{
			$image->save($newImagePath);
		}

		return true;
	}


	/**
	 * Get the media type
	 *
	 * @param $fullPath
	 * @return bool|string
	 */
	public function getMediaType($fullPath)
	{
		$mimeType = MimeTypeGuesser::getInstance()->guess($fullPath);

		if (\Illuminate\Support\Str::contains($mimeType, ['image'])) return 'image';

		return false;
	}

	/**
	 * Generate thumbnails for a given image
	 *
	 * @param $fullPath
	 * @param $relativeDir
	 * @param $absoluteDir
	 * @param int $thumbnailWidth
	 * @return bool|string
	 */
	public function getThumbnail($fullPath, $relativeDir, $absoluteDir, $thumbnailWidth = 200)
	{
		$pathInfo = pathinfo($fullPath);

		$destinationAbsoluteDir = $absoluteDir . 'thumbs/';

		if (!file_exists($destinationAbsoluteDir))
		{
			mkdir($destinationAbsoluteDir, 755);
		}

		$thumbFilePath  = $destinationAbsoluteDir . $pathInfo['basename'];
		$thumbFileUrl   = $relativeDir . 'thumbs/' . $pathInfo['basename'];
		$this->resizeImageToMaxWidth($fullPath, $thumbnailWidth, $thumbFilePath);

		// file should be saved now
		if (file_exists($thumbFilePath))
		{
			return $thumbFileUrl;
		}
		return false;
	}

}
