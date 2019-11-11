<?php

namespace frontend\models;
use frontend\constants\Settings;
use yii\db\ActiveRecord;
/**
 * ContactForm is the model behind the contact form.
 */
class Comment extends ActiveRecord
{

    public static function tableName()
    {
        return 'comment';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['content'], 'required'],
            [['date_create'], 'date', 'format' => Settings::DATE_FORMAT_MODEL],
            [['user_id'], 'required']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'content' => 'Текст комментария',
            'date_create' => 'Дата создания',
            'user_id' => 'ID Пользователя',
        ];
    }

    public function getTask(){
        return $this->hasMany(Task::class, ['id' => 'task_id'])
            ->viaTable('task_comment', ['comment_id' => 'id']);
    }

}
