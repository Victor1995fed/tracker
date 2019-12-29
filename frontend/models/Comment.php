<?php

namespace frontend\models;
use common\models\elastic\Comment as ElastiComment;
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

    public function afterSave($insert, $changedAttributes){
        parent::afterSave($insert, $changedAttributes);
        //Обновляем или добавляем запись в elasticsearch
        if($insert) {
            $elastic = new ElastiComment();
            $elastic->fill($this);
            $elastic->setPrimaryKey($this->id);
            $elastic->save(false);
        } else {
            $elastic = ElastiComment::get($this->id);
            $elastic->fill($this);
            $elastic->update(false, ['content']);
        }
    }

    public function afterDelete()
    {
        parent::afterDelete();
//        Удаляем данные из  elasticsearch
        $elastic = ElastiComment::get($this->id);
        $elastic->delete();
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['content'], 'required'],
            [['date_create'], 'date', 'format' => 'yyyy-MM-dd'],
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
