<?php
namespace App\Helpers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Drivers\Gd\Encoders\WebpEncoder;
use Intervention\Image\ImageManager;
use Illuminate\View\ComponentAttributeBag;

class Image
{
    // Show Image URL
    public static function showFile($path, $image)
    {
        // $path = 'storage/' . $path;
        // $Arr  = explode('.', $image);
        // array_pop($Arr);
        // if (! empty($image) && file_exists(public_path($path . 'webp/' . implode('', $Arr) . '.webp'))) {
        //     return asset($path . 'webp/' . implode('', $Arr) . '.webp');
        // } elseif (! empty($image) && file_exists(public_path($path . '/' . implode('', $Arr) . '.jpg'))) {
        //     return asset($path . '/' . implode('', $Arr) . '.jpg');
        // } elseif (! empty($image) && file_exists(public_path($path . '/' . $image))) {
        //     return asset($path . '/' . $image);
        // } else {
        //     return asset('admin/img/no-img.webp');
        // }

        return route('image.resize', [
        'filename' => $image,
        'path' => str_replace('/','_',$path),
        'folder' => 'original',
        'width' => 1600,
        ]);
    }

    // Remove Image
    public static function removeFile($path, $image)
    {
        $path = 'storage/' . $path;
        self::deleteImageVariants($image, $path);

    }

    public static function deleteImageVariants($filename, $directory)
    {
        $baseFilename = basename($filename);
        $path         = public_path($directory);

        $allFiles  = File::files($path . 'original');
        // $allFiles2 = File::files($path . 'webp');

        foreach ($allFiles as $file) {
            $currentName = $file->getFilename();
            if ($currentName === $baseFilename || str_ends_with($currentName, "_$baseFilename")) {
                File::delete($file->getPathname());
            }
        }

        /// WEBP IMAGE REMOVE
        // $Arr = explode('.', $baseFilename);
        // array_pop($Arr);
        // $baseFilename = implode('', $Arr) . '.webp';
        // foreach ($allFiles2 as $file) {
        //     $currentName = $file->getFilename();
        //     if ($currentName === $baseFilename || str_ends_with($currentName, "_$baseFilename")) {
        //         File::delete($file->getPathname());
        //     }
        // }
    }

    // Make Directory
    public static function makeDirctory($path)
    {
        $path = public_path($path);
        if (! File::exists($path)) {
            File::makeDirectory($path, 0755, true);
        }
    }

    public static function uploadFile($path, $image, $withOriginalName = false)
    {
        // $path = 'storage/' . $path;
        self::makeDirctory('storage/' . $path);
        $filenameWithExt = $image->getClientOriginalName();
        $extension       = $image->getClientOriginalExtension();
        if (! $withOriginalName) {
            $projectname     = str_replace(' ', '-', config('app.name'));
            $filenameWithExt = strtolower($projectname . '-');
            $filenameWithExt = uniqid($filenameWithExt) . '.' . $extension;
        }
        $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
        $path     = $image->storeAs($path, $filename.'.'.$extension);
        return $filenameWithExt;
    }

    public static function directFile($path, $image, $withOriginalName = false, $watermark = false)
    {
        self::makeDirctory($path);
        $name      = $image->getClientOriginalName();
        $extention = $image->getClientOriginalExtension();
        if ($withOriginalName) {
            $fileName = strtolower(str_replace(' ', '-', $name));
        } else {
            $projectname = str_replace(' ', '-', config('app.name'));
            $fileName    = strtolower($projectname . '-');
            $fileName    = uniqid($fileName) . '.' . $extention;
        }
        $imgmanager = new ImageManager(new Driver());
        $newImage   = $imgmanager->read($image);
        if ($watermark) {
            $newImage->place(public_path('admin/img/logo.png'));
        }
        $newImage->save(public_path($path . $fileName));
        return $fileName;
    }

    public static function autoheight($path, $image)
    {
        $path         = 'storage/' . $path;
        $originalPath = $path . 'original/';

        self::makeDirctory($originalPath);

        $extention = $image->getClientOriginalExtension();

        $fileName = self::directFile($originalPath, $image);
        if (strtoupper($extention) != 'WEBP') {
            // self::converttowebp($path, $image, $fileName);
        }

        return $fileName;
    }
    public static function converttowebp($path, $image, $fileName, $watermark = false)
    {
        self::makeDirctory($path . 'webp');

        $imgmanager = new ImageManager(new Driver());
        $newImage   = $imgmanager->read($image);
        // $newImage->scale($width);

        if ($watermark) {
            $newImage->place(public_path('admin/img/logo.png'));
        }

        $Arr = explode('.', $fileName);
        array_pop($Arr);
        $newImage->toWebp(60)->save(public_path($path . 'webp/' . implode('', $Arr) . '.webp'));
    }

    // function getBinary($path,$width,$folder,$filename){
    //     $Arr = explode('_', $path);
    //     $path = implode('/', $Arr);
    //     $path = public_path("storage/{$path}/{$folder}/{$filename}");

    //     if (!file_exists($path)) {
    //         abort(404);
    //     }
    //     return \Image::convertSize($width, $path);
    // }

    public static function getBinary($path, $width, $folder, $filename)
    {
        $Arr  = explode('_', $path);
        $path = implode('/', $Arr);

        $storagePath = $folder
        ? "storage/{$path}/{$folder}/ws_{$width}_{$filename}"
        : "storage/{$path}/ws_{$width}_{$filename}";

        $resizedPath = public_path($storagePath);

        if (file_exists($resizedPath)) {
            return file_get_contents($resizedPath);
        }

        $storagePath = $folder
        ? "storage/{$path}/{$folder}/{$filename}"
        : "storage/{$path}/{$filename}";

        $originalFullPath = public_path($storagePath);

        if (! file_exists($originalFullPath)) {abort(404);}

        $binary = self::convertSize($width, $originalFullPath,$path);

        file_put_contents($resizedPath, $binary);

        return $binary;
    }

    public static function convertSize($width, $path,$folder=null)
    {
        $imgManager = new ImageManager(new Driver());
        $image      = $imgManager->read($path);

        $imgWidth = $image->width();
        $height   = $image->height();

        if ($imgWidth < $width || $width <= 0) {
            $width = $imgWidth;
        }

        $image = $image->scale($width, null);

        $watermarkPath = public_path('watermark1.png');
        if (file_exists($watermarkPath) && in_array($folder,['product'])) {
            $watermark = $imgManager->read($watermarkPath);
            $waterWidth = ($width * 20) / 100;
            $watermark = $watermark->scale($waterWidth, null);
            $image->place(
                $watermark,
                'top-left',
                10, // x offset
                10  // y offset
            );

            $image->place(
                $watermark,
                'bottom-right',
                10, // x offset
                10  // y offset
            );
        }

        return (string) $image->encode(new WebpEncoder());
    }

    function downloadUrlImage($path, $url)
    {
        $response = Http::get($url);
        if ($response->successful()) {

            $imageName = basename($url);

            Storage::disk('public')->put($path . '/original/' . $imageName, $response->body());

            $path      = 'storage/' . $path;
            $imagePath = public_path($path . '/original/' . $imageName);

            $Arr = explode('.', $imageName);
            array_pop($Arr);
            $this->makeDirctory($path . '/webp/');

            $webpPath = public_path($path . '/webp/');

            $imgmanager = new ImageManager(new Driver());
            $newImage   = $imgmanager->read($imagePath);

            $newImage->toWebp(60)->save($webpPath . implode('', $Arr) . '.webp');

            return $imageName;
        }
    }
}
