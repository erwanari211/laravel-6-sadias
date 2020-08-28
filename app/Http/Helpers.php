<?php

if (!function_exists('example_function')) {
    function example_function($string)
    {
        return $string;
    }
}

if (!function_exists('upload_file')) {
    function upload_file($file, $directory = 'uploads', $newFileName = null)
    {
        $originalName = $file->getClientOriginalName();
        $filename = $newFileName ? $newFileName : pathinfo($originalName, PATHINFO_FILENAME);
        $ext = $file->getClientOriginalExtension();
        $newFileName = Str::slug($filename).'-'.Str::random(8).'-'.time().'.'.$ext;
        $file->move($directory, $newFileName);
        $filepath = $directory.'/'.$newFileName;

        return $filepath;
    }
}

if (!function_exists('delete_file')) {
    function delete_file($filepath = '')
    {
        $isDeleted = false;
        if (File::exists($filepath)) {
            $isDeleted = File::delete($filepath);
        }

        return $isDeleted;
    }
}
