# File and Media Handling Package for Laravel 6

## Installation

Add the private repository in your 'composer.json` file.
```
"repositories": [
    {
        "type":"vcs",
        "url":"git@bitbucket.org:elegantmedia/mediamanager-laravel.git"
    }
]
```

Add the repository to the required list on composer.json
`composer require emedia/media-manager`


In `config\app.php`, add the service provider and the Facade.

Add to the list of providers:

`EMedia\MediaManager\MediaManagerServiceProvider::class`

Add to the list of aliases:
```
'FileHandler'  => EMedia\MediaManager\Facades\FileHandler::class,
'ImageHandler' => EMedia\MediaManager\Facades\ImageHandler::class,
```

## To handle file uploads, images and other media

To upload a file,
```
$fileDir  	 = '/images/content/';			// relative dir for HTTP use
$fileDirPath = public_path() . '/images/content/'; // absolute path on server
$filePath    = MediaHandler::uploadFile('file', $fileDirPath, $fileDir);

if ( ! empty($filePath) ) {
	// resize image to a max width
	ImageHandler::resizeImageToMaxWidth($fullPath, 1920);
	// generate a thumbnail
	$thumbnail = ImageHandler::getThumbnail($fullPath, $fileDir, $fileDirPath, 750);
}
```
