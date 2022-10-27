<?php

use JD\Cloudder\Facades\Cloudder;
use App\Models\File;

use Illuminate\Support\Facades\Auth;

if (! function_exists('getFromCloudinary')) {
    function getFromCloudinary($public_id, $options = [])
    {
        return Cloudder::secureShow($public_id, $options);
    }
}

if (! function_exists('uploadToCloudinary')) {
    function uploadToCloudinary($file, $options = [])
    {
        Cloudder::upload($file, null, $options);
        return Cloudder::getResult();
    }
}

if (! function_exists('saveAsFile')) {
    function saveAsFile($uploaded)
    {
        return File::create([
            'name' => $uploaded['name'],
            'public_id' => $uploaded['public_id'],
            'type' => $uploaded['type'],
            'uploaded_by_id' => Auth::id()
        ]);
    }
}