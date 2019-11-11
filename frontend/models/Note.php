<?php

namespace frontend\models;

//use app\models\File;
use frontend\constants\Settings;
use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;
/**
 * ContactForm is the model behind the contact form.
 */
class Note extends ActiveRecord
{

    public static function tableName()
    {
        return 'note';
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
            [['date_create'], 'date', 'format' => Settings::DATE_FORMAT_MODEL]

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
