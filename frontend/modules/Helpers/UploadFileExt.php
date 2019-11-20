<?php

namespace app\modules\helpers;
use yii\web\UploadedFile;


class UploadFileExt extends UploadedFile
{

    public function saveFilePut( $path)
    {
        $input = file_get_contents('php://input');
        $fullName = $this->baseName . '.' . $this->extension; //get name file
        $pattern = '/'.$fullName.'".*?Content-Type.*?\n(.*?)------/ms';
        $resultMath = preg_match_all($pattern,$input,$found); //search file in php://input
        if($resultMath == 0)
            return false;
        return file_put_contents($path, trim($found[1][0]));
    }
}