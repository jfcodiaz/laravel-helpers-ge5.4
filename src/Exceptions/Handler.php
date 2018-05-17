<?php

namespace DevTics\LaravelHelpers\Exceptions;

class Handler {

  private static function getMethod($exception) {
    return str_replace("\\","_", get_class($exception));// \Symfony\Component\HttpKernel\Exception\NotFoundHttpException::class);
  }

  public static function Symfony_Component_HttpKernel_Exception_NotFoundHttpException($request, $excetpion) {
      if ($request->expectsJson()) {
        return \Response::json([
            'success' => false,
            'error' => true,
            'message' => __('devticsHelpers::exceptions.http-404')
        ])->setStatusCode(404);
      }
  }

  public static function render($request, $exception) {
      $method = self::getMethod($exception);
      if(method_exists(get_class(), $method)) {
        $refMethod = new \ReflectionMethod(get_class(), $method);
        return $refMethod->invokeArgs(null, [$request, $exception]);
      }
  }

}
