<?php
namespace frontend\models;
use frontend\constants\Settings;
use frontend\constants\TaskStatus;
use yii\data\ActiveDataProvider;
use yii\db\Exception;

class TaskSearch extends Task
{

    /**
     * @inheritdoc
     */
    public $status;
    public $period;
    public $done;
    public $tag;
    public function rules()
    {
        return [
            [['id','status','project','period','done'], 'integer'],
            [['tag'], 'safe']
        ];
    }

    public function search($params) {
        $currentUser = \Yii::$app->user->identity->id;
        $query = Task::find()->joinWith(['status','category','priority','project','tag'])->where(['task.user_id'=>$currentUser])->asArray();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
                'page' => (int) --$params['page']
            ]
        ]);

        $dataProvider->setSort( [
            'defaultOrder' => [
                'id' => SORT_DESC,
            ],
            'attributes' => [
                'title',
                'id',
                'status.id',
                'project.title',
                'priority.id',
                'date_end'
            ],
        ]);

        if (!($this->load($params,'') && $this->validate())) {
            //TODO:: Добавить логи
            return $dataProvider;
        }

        $date = $this->getDate();
        $statusDone = $this->getStatusDone();
        $query
            ->andFilterWhere(['in', 'status.id', $this->status])
            ->andFilterWhere(['between', 'date_end', date(Settings::DATE_FORMAT_PHP), $date ])
            ->andFilterWhere(['not in', 'status.id', $statusDone ])
            ->andFilterWhere(['in', 'tag.id',$params['tag'] ?? []])->groupBy('task.id')
            ->andFilterHaving(['>=','count(task.id)', count($params['tag'] ?? [])]);

        return $dataProvider;
    }

    private function getDate()
    {
     if ($this->period){
         switch ($this->period) {
             //TODO:: Вынести значения в константы
             case '30':
                 return date('Y-m-d', strtotime('+30 days'));
                break;

             case '7':
                 return date('Y-m-d', strtotime('+7 days'));
                 break;

             default :
                 return null;
                 break;
         }
     }
    }

    private function getStatusDone()
    {
        return ($this->done != 1) ? TaskStatus::DONE : null;
    }

}