<?php
namespace  DevTics\LaravelHelpers\Utils;
use Illuminate\Support\Facades\File as fcFile;
 
class File {
    public static function createPathIfNoExits($dirFile) {
        if(!fcFile::exists($dirFile)) {
            fcFile::makeDirectory($dirFile, env('FOLDER_UNMASK', 493), true);
        } 
    }
}
