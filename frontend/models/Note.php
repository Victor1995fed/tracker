<?php

namespace frontend\models;

//use app\models\File;
use frontend\constants\Settings;
use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;
use common\models\elastic\Note as ElasticNote;
use yii\db\Exception;

/**
 * ContactForm is the model behind the contact form.
 */
class Note extends ActiveRecord
{



    public static function tableName()
    {
        return 'note';
    }

    public function afterSave($insert, $changedAttributes){
        parent::afterSave($insert, $changedAttributes);
        //Обновляем или добавляем запись в elasticsearch
        if($insert) {
            $elastic = new ElasticNote();
            $elastic->fill($this);
            $elastic->setPrimaryKey($this->id);
            $elastic->save(false);
        } else {
            $elastic = ElasticNote::get($this->id);
            $elastic->fill($this);
            $elastic->update(false, ['title', 'content']);
        }
    }

    public function afterDelete()
    {
        parent::afterDelete();
//        Удаляем данные из  elasticsearch
        $elastic = ElasticNote::get($this->id);
        $elastic->delete();
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['title', 'content'], 'string'],
            [['title'], 'string', 'max' => 250],
            [['date_create'], 'date', 'format' => 'yyyy-MM-dd']

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
            'content' => 'Контент',
            'date_create' => 'Дата создания',
        ];


    }
}
