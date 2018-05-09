<?php

namespace DevTics\LaravelHelpers\Model\traits;
use \DevTics\LaravelHelpers\Model\ModelBase;

trait UserBase {

    /**
     * Verifica si un objeto pertenece a un usuario
     * @param type $object
     * @param type $field
     * @param int $int_action int \DevTics\LaravelHelpers\Model\ModelBase::ACTION_CHECK_*
     * @return type
     */
    public function isMine($object, $field, $int_action = 0, $msjExeption = 'No tienes permisos para esta accion', $noException = 0) {
        $test = $object->{$field} == $this->id;
        if ($test) {
            return true;
        }

        if ($int_action == ModelBase::ACTION_CHECK_THOW_EXCEPTION) {
            throw new \Exception($msjExeption, $noException);
        }

        if ($int_action == ModelBase::ACTION_CHECK_ABORT_404) {
            abort(404);
        }
        return false;
    }

    public function isAdmin() {
        return false;
    }

}
