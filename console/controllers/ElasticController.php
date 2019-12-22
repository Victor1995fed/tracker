<?php
namespace console\controllers;
use Yii;
use yii\console\Controller;
use frontend\models\Note;
use common\models\elastic\Note as ElasticNote;
use yii\db\Exception;

class ElasticController extends Controller {

    public function actionCreateIndex() {
        //Удаляем старый индекс если он существует
        ElasticNote::deleteIndex();
        //Добавляем данные
        foreach(Note::find()->asArray()->all() as $note) {
            $elastic = new ElasticNote();
            $elastic->fill($note);
            $elastic->setPrimaryKey($note['id']);
            $elastic->save();
        }
        Yii::info('The ElasticSearch index was created ('.
            ElasticNote::index() .'/'. ElasticNote::type() .').', __METHOD__);
    }
}