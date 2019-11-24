<?php

namespace app\modules\helpers;
use yii\db\Exception;
use yii\web\UploadedFile;


class UploadFileExt extends UploadedFile
{

    public function saveFilePut($path)
    {
        if($content = file_get_contents($this->tempName))
            return file_put_contents($path, $content);
        else
            throw new Exception('Не удалось найти файл для сохранения');
    }

    public function getHash()
    {
        return md5_file($this->tempName);
    }


}