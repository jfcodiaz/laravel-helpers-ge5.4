<?php
namespace DevTics\LaravelHelpers\Model;

use Illuminate\Database\Eloquent\Model;



class ModelBase extends Model {
    
    const ACTION_CHECK_ABORT_404 = 0;
    const ACTION_CHECK_THOW_EXCEPTION = 1;
    const ACTION_CHECK_RETURN_BOOLEAN = 2;
    
    use \DevTics\LaravelHelpers\Model\traits\MethodsModelBase;
    protected $guarded = ['id'];    
    public $timestamps = false;
    
}
