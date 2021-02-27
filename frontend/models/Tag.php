<?php

namespace frontend\models;

use frontend\models\Task;
use Yii;

/**
 * This is the model class for table "tags".
 *
 * @property int $id
 * @property string $title
 */
class Tag extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tag';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title','user_id'], 'required'],
            [['title'], 'string', 'max' => 255],
            [['user_id'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'user_id' => 'id пользователя'
        ];
    }


    public function getTask(){
        return $this->hasMany(Task::class, ['id' => 'task_id'])->orderBy(['id' => SORT_DESC])
            ->viaTable('task_tag', ['tag_id' => 'id']);
    }

}
