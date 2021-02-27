<?php
namespace console\controllers;

use Yii;
use yii\console\Controller;
use frontend\models\Note;
use frontend\models\Project;
use frontend\models\Comment;
use frontend\models\Task;
use common\models\elastic\Note as ElasticNote;
use common\models\elastic\Project as ElasticProject;
use common\models\elastic\Comment as ElasticComment;
use common\models\elastic\Task as ElasticTask;
use yii\db\Exception;

class ElasticController extends Controller {

    public function actionCreateIndex()
    {
        //Удаляем старый индекс если он существует
        ElasticNote::deleteIndex();
        ElasticProject::deleteIndex();
        ElasticTask::deleteIndex();
        ElasticComment::deleteIndex();
        //Добавляем данные
        try
        {
            $this->setData(Note::find()->asArray()->all(),  ElasticNote::class);
            $this->setData(Project::find()->asArray()->all(),  ElasticProject::class);
            $this->setData(Comment::find()->asArray()->all(),  ElasticComment::class);
            $this->setData(Task::find()->asArray()->all(),  ElasticTask::class);
        }
        catch (\Exception $e){
            throw new Exception($e->getMessage());
        }
        echo "Готово!";
    }

    private function setData(array $data, string $model)
    {
        foreach($data as $one) {
            $elastic = new $model();
            $elastic->fill($one);
            $elastic->setPrimaryKey($one['id']);
            $elastic->save();
        }
        Yii::info('The ElasticSearch index was created ('.
            $model::index() .'/'. $model::type() .').', __METHOD__);
    }
}