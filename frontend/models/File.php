<?php

namespace app\models;

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
            'title' => 'Title',
            'url' => 'Url',
            'description' => 'Description',
            'uuid' => 'UUID',
        ];
    }

    public function getTask(){
        return $this->hasMany(Task::class, ['id' => 'task_id'])
            ->viaTable('task_file', ['file_id' => 'id']);
    }

    //TODO:: Допилить функционал с file_hash
//    /**
//     * Возвращает существующий или создает новый экземпляр класса с переносом данных в хранилище
//     * @param DiskFile $diskFile - дисковый файл
//     * @return File|array|bool|\yii\db\ActiveRecord|null
//     * @throws \Exception
//     */
//    public static function getOrCreateByFile($diskFile)
//    {
//        if ($file = File::find()->where(['file_hash' => $diskFile->getHash()])->one())
//            return $file;
//        else
//            return File::populateFromDiskFile($diskFile);
//    }

}
