<?php

namespace app\modules\helpers;
use \yii\base\Module;

class Helper extends Module
{


    public function __construct(){

    }


    public static function setValidations($rules){
//        foreach ($rules as $var){
//            $result[$var[0]] = $var[1];
//        }
return $rules;
    }

    public function genColor()
    {
        return dechex(mt_rand(0, 16777215));
    }
}