<?php
namespace  DevTics\LaravelHelpers\Utils;

class Pass4Dummies {
    
    private static $symbols = ["-","+", "(", ")", "$", ".", ","];
    
    // <editor-fold defaultstate="collapsed" desc="words">
    private static $words = [
        'vendo',
        'vender',
        'coma',
        'comer',
        'edificio',
        'avion',
        'casa',
        'oficina',
        'parque',
        'depa',
        'lote',
        'depa',
        'vienda',
        'carro',
        'mobil',
        'auto',
        'moto',
        'cell',
        'celular',
        'telefono',
        'rodar',
        'correr',
        'fresa',
        'sol',
        'luna',
        'tierra',
        'gato',
        'oso',
        'piano',
        'guitarra',
        'leon',
        'rock',
        'red',
        'cafe',
        'verde',
        'kimanta',
        'mexico',
        'negro',
        'azul',
        'verde',
        'tele',
        'juego'
    ];
    // </editor-fold>
    
    // <editor-fold defaultstate="collapsed" desc="getRandom">
    private static function getRandom($items) {
        return $items[rand(0,count($items)-1)];
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="change">
    private static function change($char) {
        switch($char){
            case 'a': return self::getRandom(['A','a','4','@']);
            case 'e': return self::getRandom(['e','E','3']);
            case 'i': return self::getRandom(['i','i','I','1']);
            case 'o': return self::getRandom(['o','o','O','0']);
            case 'u': return self::getRandom(['u','u','V','v']);
            case '0': return self::getRandom(['0','0','o',"O"]);
            case '1': return self::getRandom(['1','1','1','i',"l","I"]);
            case '3': return self::getRandom(['3','3','3','E']);
            case '4': return self::getRandom(['4','4','4','A']);
            case '5': return self::getRandom(['5','5','5','$',"s","S"]);
            case '6': return self::getRandom(['6','6','6','G']);
            case '7': return self::getRandom(['7','7','7','T']);
            case '8': return self::getRandom(['8','8','8','B']);
            case '9': return self::getRandom(['9','9','p','P']);
            default : return self::getRandom([$char, $char, strtoupper($char)]);
        }
    }
    // </editor-fold>
    
    // <editor-fold defaultstate="collapsed" desc="getWords">
    private static function getWords(){
        return self::$words;
    }
    // </editor-fold>
    
    // <editor-fold defaultstate="collapsed" desc="makeWords">
    public static function makeWord($arrWords = false) {
        $words = $arrWords ? $arrWords : self::getWords();
        $word = self::getRandom($words); 
        $newString = "";
        for($i=0; $i<strlen($word); $i++){
            $newString .= self::change($word[$i]);
        }
        return $newString;
    }
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="make">
    public static function make($arrWords = false) {
        return join("", [
            self::makeWord(),
            self::makeWord([rand(0, 99).""]),
            self::getRandom(self::$symbols)
        ]);
    }
    // </editor-fold>

}
