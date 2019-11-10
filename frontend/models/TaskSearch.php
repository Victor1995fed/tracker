<?php
namespace frontend\models;
use frontend\constants\TaskStatus;
use yii\data\ActiveDataProvider;

class TaskSearch extends Task
{

    /**
     * @inheritdoc
     */
    public $status;
    public $period;
    public $done;
    public function rules()
    {
        return [
            [['id','status','project','period','done'], 'integer']
        ];
    }

    public function search($params) {
        $query = Task::find()->joinWith(['status','category','priority','project'])->asArray();

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
            return $dataProvider;
        }
        $date = $this->getDate();
        $statusDone = $this->getStatusDone();
        $query
            ->andFilterWhere(['in', 'status.id', $this->status])
            ->andFilterWhere(['between', 'date_end', date('Y-m-d'), $date ])
            ->andFilterWhere(['not in', 'status.id', $statusDone ]);

        return $dataProvider;
    }

    private function getDate()
    {
     if ($this->period){
         switch ($this->period) {
             case '1':
                 return date('Y-m-d');
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