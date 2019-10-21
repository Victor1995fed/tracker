<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "session".
 *
 * @property int $id
 * @property int $user_id
 * @property string $token
 * @property string $date_create
 */
class Session extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'session';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id'], 'integer'],
            [['token'], 'string'],
            [['date_create'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'token' => 'Token',
            'date_create' => 'Date Create',
        ];
    }
}
