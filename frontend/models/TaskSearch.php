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
            [['id','status','project','period','done'], 'integer'],
//            [['date_create', 'date_end', 'status', 'project', 'priority', 'period','done'], 'safe'],
        ];
    }

    public function search($params) {
//        return $params;
//        return $this->period;
//        return $this->getDate($this->period);
        $query = Task::find()->joinWith('status');
        //Поиск по id
//        $query = Task::find()->where(['id'=>$params['id']]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 3,
                'page' => (int) --$params['page']
            ]
        ]);

        /**
         * Настройка параметров сортировки
         * Важно: должна быть выполнена раньше $this->load($params)
         */
        $dataProvider->setSort( [
            'attributes' => [
                'id',
//                'fullName' => [
//                    'asc' => ['first_name' => SORT_ASC, 'last_name' => SORT_ASC],
//                    'desc' => ['first_name' => SORT_DESC, 'last_name' => SORT_DESC],
//                    'label' => 'Full Name',
//                    'default' => SORT_ASC
//                ],
//                'country_id'
            ]
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
        return ($this->done == 1) ? TaskStatus::DONE : null;
    }

}