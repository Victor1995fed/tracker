<?php

namespace frontend\models;

use common\models\elastic\Project as ElasticProject;
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


    public function afterSave($insert, $changedAttributes){
        parent::afterSave($insert, $changedAttributes);
        //Обновляем или добавляем запись в elasticsearch
        if($insert) {
            $elastic = new ElasticProject();
            $elastic->fill($this);
            $elastic->setPrimaryKey($this->id);
            $elastic->save(false);
        } else {
            $elastic = ElasticProject::get($this->id);
            $elastic->fill($this);
            $elastic->update(false, ['title', 'description']);
        }
    }

    public function afterDelete()
    {
        parent::afterDelete();
//        Удаляем данные из  elasticsearch
        $elastic = ElasticProject::get($this->id);
        $elastic->delete();
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['title'], 'string'],
            [['date'], 'date', 'format' => 'yyyy-MM-dd'],
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
