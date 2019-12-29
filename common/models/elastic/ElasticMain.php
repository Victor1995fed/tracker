<?php

namespace common\models\elastic;


use yii\db\Exception;
use yii\elasticsearch\ActiveRecord;

/**
 * ContactForm is the model behind the contact form.
 */
class ElasticMain extends ActiveRecord
{

    /**
     * @return array список атрибутов для этой записи
     */
    public function attributes()
    {

    }

// Fill in by frontend/models/Note model
    public function fill($model) {
        foreach ($model as $key => $val){
            if(array_search($key,$this->attributes()))
                $this->$key = $val;
        }
    }

    /**
     * Установка (update) для этой модели
     */
    public static function updateMapping()
    {
        $db = static::getDb();
        $command = $db->createCommand();
        $command->setMapping(static::index(), static::type(), static::mapping());
    }

    /**
     * Создать индекс этой модели
     */
    public static function createIndex()
    {
        $db = static::getDb();
        $command = $db->createCommand();
        if($command->indexExists(static::index()))
            $command->deleteIndex(static::index());
        $command->createIndex(static::index(), [
            'settings' => [
                'analysis' => [
                    'filter' => [
                        'ru_stop' => [
                            'type' => 'stop',
                            'stopwords' => '_russian_',
                        ],
                        'ru_stemmer' => [
                            'type' => 'stemmer',
                            'language' => 'russian',
                        ],
                    ],
                    'analyzer' => [
                        'default' => [
                            'char_filter' => [
                                'html_strip',
                            ],
                            'tokenizer' => 'standard',
                            'filter' => [
                                'lowercase',
                                'ru_stop',
                                'ru_stemmer',
                            ],
                        ],
                    ],
                ],
            ],
            'mappings' => static::mapping(),
        ]);
    }

    /**
     * Удалить индекс этой модели
     */
    public static function deleteIndex()
    {
        $db = static::getDb();
        $command = $db->createCommand();
        if($command->indexExists(static::index()))
            $command->deleteIndex(static::index());
    }
}
