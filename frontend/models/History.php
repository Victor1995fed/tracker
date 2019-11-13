<?php

namespace frontend\models;

use yii\db\ActiveRecord;

class History extends ActiveRecord
{

    public static function tableName()
    {
        return 'history';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['comment','user_id'], 'required'],
            [['comment'], 'string'],
            [['user_id'], 'integer'],
            [['date'], 'date', 'format' => 'yyyy-MM-dd'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'comment' => 'Текст комментария',
            'date' => 'Дата создания',
            'user_id' => 'ID пользоателя'
        ];

    }


    public static function getChangesFields(Array $dirtyAttributes, Array $oldAttributes)
    {
        $changedFields = [];

        foreach ($dirtyAttributes as $attribute => $newValue) {
            if(array_key_exists($attribute, $oldAttributes) && $oldAttributes[$attribute] != $newValue) {
                $changedFields[] = [
                    'attribute' => $attribute,
                    'old' => $oldAttributes[$attribute],
                    'new' => $newValue,
                ];
            }
        }

        return $changedFields;
    }

    public function getTask()
    {
        return $this->hasMany(Task::class, ['id' => 'task_id'])
            ->viaTable('task_history', ['history_id' => 'id']);
    }
}
