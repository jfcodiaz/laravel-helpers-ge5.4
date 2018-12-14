<?php

namespace DevTics\LaravelHelpers\Model\traits;
use Carbon\Carbon;

use \DevTics\LaravelHelpers\Model\ModelBase;

trait MethodsModelBase {
    
    public static function encoreHashId($id) {
        return env('USE_HASHIDS') ? \Hashids::encode($id) : $id;
    }
    
    public static function decodeHashId($hashId) {
        if(!env('USE_HASHIDS')) { 
            return $hashId; 
        }
        $decode = \Hashids::decode($hashId);
        if(count($decode)) {
            return $decode[0];
        }
    }
    
    public static function getHashIds($array){
        if(!env('USE_HASHIDS')) { 
            return $array; 
        }
        $ids=[];
        foreach($array as $v){
            $ids[] =  self::encoreHashId($v);
        }
        return $ids;
    }
    
    public function toArray() {
        $arr = parent::toArray(); 
        if(env('USE_HASHIDS')) {
            $idFields = isset($this->idField) ? $this->idField : 'id';
            $arr['id'] = self::encoreHashId($arr['id']);
        }
        return $arr;
    }
    
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
//            $this->destroy();
            $this->delete();
            $nDestroy = 1;
//            $nDestroy = $refMethod = new \ReflectionMethod(static::$model, 'destroy');
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

    public static function getAll($fields=['*']) {
        $query = static::query();
        static::setWith($query);
        return $query->get($fields ? $fields : ['*']);
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

    public static function getIndexQueryBuilder() {
        return static::query();
    }

    public static function setWith($qb) {
        $whit = \Illuminate\Support\Facades\Input::get("with");
        if($whit){
            $relations = explode(",", $whit);
            foreach ($relations as $fnWith){
                $qb->with($fnWith);
            }
        }
        return;
    }

    public static function pagination($n = false, $fields = ['*']) {
        $indexQueryBuilder = static::getIndexQueryBuilder();
        $_n = $n ? $n : \Config::get('app.entidadesPorPagina');
        static::setWith($indexQueryBuilder);
        return $indexQueryBuilder->paginate($_n, $fields ? $fields : ['*']);
    }

    public static function getById($id) {
        $query = static::where("id", $id);
        static::setWith($query);
        return $query->first();
    }

    public static function getByIds($ids, $returnQuery = false) {
        $arrIds = (array)$ids;
        $query = static::whereIn('id', $arrIds);
        static::setWith($query);
        if($returnQuery) {
            return $query;
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

    protected function getRules () {

        if($this->id==null) {
          return $this->createRules ? $this->createRules : $this->rules;
        }

        return $this->updateRules ? $this->updateRules : $this->rules;
    }

    public function validate($data) {

      $rules = $this->getRules();
      $v = \Validator::make($data, $rules);

      if($v->fails()) {
        $this->errors = $v->errors();
        return false;
      }

      return true;

    }

    public function errors() {

      return $this->errors;

    }

    public function save(array $options = []) {

      if($this->validate($this->attributes)) {
        return parent::save($options);
      }

      throw new \DevTics\LaravelHelpers\Exceptions\InvalidModelException($this->errors, 100);
    }

}
