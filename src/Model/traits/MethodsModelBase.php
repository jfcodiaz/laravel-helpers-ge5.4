<?php

namespace DevTics\LaravelHelpers\Model\traits;
use Carbon\Carbon;

use \DevTics\LaravelHelpers\Model\ModelBase;
    
trait MethodsModelBase {
        
    public function checkUserCanDelete($user) {          
        if($user->isAdmin()){
            return true;
        }
        $user->isMine($this, 'user_id', ModelBase::ACTION_CHECK_THOW_EXCEPTION);
    }
    
    public static function tryDeleteTrashedByUser($user, $id) {        
        $refresClass = new \ReflectionClass(get_called_class());
        $queryRef = $refresClass->getMethod('query');
        $qb = $queryRef->invoke(null);
        $res = $qb->withTrashed()
            ->where('id', $id)->get();
        if($res->count()) {
            $obj = $res->get(0);
            $obj->checkUserCanDelete($user);
            $obj->forceDelete();
            return [
                'nDestroy' => 1
            ];
        } else {
            abort(404, "Objeto no encotrado");
        }
    }
    
    public function tryDeleteByUser($user) {
        $this->checkUserCanDelete($user);
        if(method_exists($this, 'trashed')) {//soft Delete                            
            $this->delete();
            $nDestroy = 1;
            $type = 'soft';
        } else {
            $this->destroy();
            $nDestroy = $refMethod = new \ReflectionMethod(static::$model, 'destroy');
            $type = 'hard';
        }
        return [
            'nDestroy' => $nDestroy,
            'type' => $type
        ];
    }
   
    public function successMsjStore() {
        return "Success Store";
    }
    public static function arrCreate($param) {
        $fields = $param[0];
        $data = $param[1];
        foreach ($data as $d){
            $arr = [];
            foreach($fields as $i => $f ){
                $arr[$f] =  $d[$i];
            }
            self::create($arr);
        }
    }
    
    public function strDateToUTCDateTime($dateString) {
        if(is_string($dateString)) {
            $date = new \DateTime($dateString);
            $date->setTimezone(new \DateTimeZone("UTC"));
            return $date;
        }
        return $dateString;
    }
    
    public function datetimeFormat($attr){
        return Carbon::createFromFormat($this->dateFormat, $this->attributes[$attr])->toW3cString();
    }
    
    public static function getAllForDataTables() {
        return static::getAll();
    }
    
    public static function getAll($columns=['*']) {
        return static::get($columns);
    }

    public static function filtarRelaciones($arr) {
        if (is_array($arr)) {
            return array_intersect(static::$listaRelaciones, $arr);
        }
        $relaciones = filter_var($arr, FILTER_VALIDATE_BOOLEAN);
        if ($relaciones) {
            return static::$listaRelaciones;
        }
        return [];
    }

    public static function pagination($n = false, $fields = ['*']) {
        $_n = $n ? $n : \Config::get('app.entidadesPorPagina');

        return static::paginate($_n, $fields ? $fields : ['*']);
    }

    public static function getById($id) {
        $query = static::where("id", $id);
        $whit = \Illuminate\Support\Facades\Input::get("with");
        if($whit){
            $relations = explode(",", $whit);
            foreach ($relations as $fnWith){
                $query->with($fnWith);
            }
        }
        $res = $query->get();
        if ($res->count()) {
            return $res->get(0);
        }
        return null;
    }
        
    public static function getByIds($ids, $returnQuery = false) {
        $arrIds = (array)$ids;
        $query = static::whereIn('id', $arrIds);
        if($returnQuery) {
            return $returnQuery;
        }
        return $query->get();
    }
        
    public static function relation($id, $relation) {            
        $whit = \Illuminate\Support\Facades\Input::get("with");
        $query = static::where('id', $id)->with($relation);
        if($whit){
            $relations = explode(",", $whit);
            foreach ($relations as $fnWith){
                $query->with($fnWith);
            }
        }
        return $query->get()->get(0)->getRelation($relation);
    }

    public static function getRandom($limit = false) {
        if ($limit) {
            $res = static::orderBy(\DB::raw('RAND()'))->limit($limit)->get();
            return $res;
        }
        $res = static::orderBy(\DB::raw('RAND()'))->limit(1)->get();
        if (count($res)) {
            return $res[0];
        }
        return false;
    }
        
}
    
