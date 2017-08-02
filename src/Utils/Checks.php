<?php
namespace  DevTics\LaravelHelpers\Utils;

class Checks {
    public static function checkFieldNoEmpty($object, $properties) {
        foreach($properties as $prop) {
            if( !trim($object->{$prop}) ) {
                return false;
            }
        }
        return true;
    }
    
    public static function resolveCallback($callback = false, $no_error=0){
        if(is_numeric($callback)){
            $_callback = function() use ($callback) {
                abort($callback);
            };
        } else if(is_string($callback)) {
            $_callback = function() use ($callback, $no_error) {
                throw new Exception($callback, $no_error);
            };            
        } else if(is_callable ($callback)) {
            $_callback = $callback;
        } else {
            $_callback = $callback;
        }
        return $_callback;
    }
    public static function check($result, $callback, $no_error) {
        $fnCallback = self::resolveCallback($callback, $no_error);
        if($result) {
            if($fnCallback) {
                $fnCallback();
            }
            return true;
        }
        return false;
    }
    public static function iAm($user, $callback = false, $no_error=0) {
        self::check(
            \Illuminate\Support\Facades\Auth::user()->user ==$user->id,
            $callback, 
            $no_error);
    }
}
