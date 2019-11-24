<?php
/**
 * Created by PhpStorm.
 * User: DNS
 * Date: 14.09.2019
 * Time: 18:49
 */

namespace frontend\controllers;
use app\models\File;
use Yii;
use yii\web\Controller;
class FileController  extends AbstractApiController
{

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => \yii\filters\ContentNegotiator::className(),
                'only' => ['delete'],
                'formats' => [
                    'application/json' => \yii\web\Response::FORMAT_JSON,
                ],
            ],
        ];
    }


    public function actionDownload($uuid)
    {
        //TODO:: Добавить поле user, для идентификации скачивающего файл
        $files = new File();
        $result = $files::find()->select('url')
            ->where(['uuid'=>$uuid])->one();
        return Yii::$app->response->sendFile($result->url);
    }


    public function actionDelete($uuid)
    {
        //TODO:: Доработать возможность удаления файла с сервера, если он нигде не используется
        //get id
        $dataId = File::find()->select('id')->where(['uuid'=>$uuid])->one();
        $id = $dataId['id'];
        //TODO:: Добавить поле user, для идентификации скачивающего файл
        $model =  $this->findModel($id);
        if($model['url'] != '')
            @unlink($model['url']);
        $model->unlinkAll('task',true);
        $model->delete();
        return true;
    }

    protected function findModel($id)
    {
        if (($model = File::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}