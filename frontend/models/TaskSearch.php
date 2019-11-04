<?php
namespace frontend\models;
use yii\data\ActiveDataProvider;

class TaskSearch extends Task
{

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['date_create', 'date_end', 'status', 'project', 'priority'], 'safe'],
        ];
    }

    public function search($params) {
        $query = Task::find();
        //Поиск по id
//        $query = Task::find()->where(['id'=>$params['id']]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 2,
                'page' => $params['page']
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

        return $dataProvider;
    }

}