<?php
/**
 * Created by PhpStorm.
 * User: DNS
 * Date: 14.09.2019
 * Time: 18:49
 */

namespace frontend\controllers;
use app\models\Files;
use Yii;
use yii\web\Controller;
class FilesController  extends Controller
{

    public function actionDownload($uuid)
    {
        //TODO:: Добавить поле user, для идентификации скачивающего файл
        $files = new Files();
        $result = $files::find()->select('url')
            ->where(['uuid'=>$uuid])->one();
        return Yii::$app->response->sendFile($result->url);
    }
}