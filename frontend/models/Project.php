<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;
/**
 * ContactForm is the model behind the contact form.
 */
class Project extends ActiveRecord
{

    public static function tableName()
    {
        return 'project';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['title'], 'string'],
            [['date'], 'date', 'format' => 'Y-m-d'],
            [['status_id'], 'integer'],
            ['description','string']
        ];


    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Название',
            'description' => 'Описание',
            'date' => 'Дата',
            'iamge' => 'Картинка',
        ];


    }


    public function getStatus(){
        return $this->hasOne(Status::class, ['id' => 'status_id']);
    }
}
