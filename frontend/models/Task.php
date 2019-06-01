<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;
/**
 * ContactForm is the model behind the contact form.
 */
class Task extends ActiveRecord
{

    public static function tableName()
    {
        return 'task';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['title', 'description'], 'string'],
            [['title'], 'string', 'max' => 250],
            [['category_id'], 'integer'    ],
            [['priority_id'], 'integer', 'max' => 4],
            [['date'], 'date', 'format' => 'Y-m-d'],
            [['status'], 'string', 'max' => 10],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Тема',
            'description' => 'Описание',
            'category_id' => 'Категория',
            'priority_id' => 'Приоритет',
            'date' => 'Дата',
        ];


    }

    public function getCategory(){
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }
    public function getPriority(){
        return $this->hasOne(Priorities::className(), ['id' => 'priority_id']);
    }
}
