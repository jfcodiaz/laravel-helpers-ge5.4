<?php

namespace DevTics\LaravelHelpers\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class InvalidModelException extends Exception {

  private $errors =  [];

  public function __construct($errors = [], $code = 0, $previous = [] ) {
    $this->errors = $errors;
    //parent::__construct($mssage = "Modelo Invalido", $code =0, $previous = []);
  }

  public function getErrors() {
    return $this->errors;
  }

}
