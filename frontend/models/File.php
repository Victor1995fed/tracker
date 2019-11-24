<?php

namespace app\models;

use app\modules\helpers\UploadFileExt;
use frontend\constants\Settings;
use frontend\models\Task;
use Yii;

/**
 * This is the model class for table "files".
 *
 * @property int $id
 * @property string $title
 * @property string $url
 * @property string $description
 */
class File extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'file';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['description'], 'string'],
            [['title', 'url'], 'string', 'max' => 255],
            [['uuid'],'string','max'=>36],
            [['date_create'], 'date', 'format' => Settings::DATE_FORMAT_MODEL],
            [['file_hash'],'string']
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
            'url' => 'Url',
            'description' => 'Description',
            'uuid' => 'UUID',
            'file_hash' => 'hash'
        ];
    }

    public function getTask(){
        return $this->hasMany(Task::class, ['id' => 'task_id'])
            ->viaTable('task_file', ['file_id' => 'id']);
    }

    //TODO:: Допилить функционал с file_hash
    /**
     * Возвращает экземпяр класса File если файл существует
     * @param  $diskFile - загружаемый файл
     * @return File|array|bool|\yii\db\ActiveRecord|null
     * @throws \Exception
     */
    public static function getFile(UploadFileExt $diskFile)
    {
        if ($file = File::find()->where(['file_hash' => $diskFile->getHash()])->one())
            return $file;
        else
            return false;
    }

}
