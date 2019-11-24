<?php
namespace app\models;

use Faker\Provider\Uuid;

use Yii;
use yii\base\Model;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;

class UploadForm extends Model
{

public function uploadDir(){
    return  Yii::getAlias('@app').'/uploads/';
}
//return $uploadDir;
    /**
     * @var UploadedFile file attribute
     */
    public $file;
    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['file'],'file',
                'maxSize' => 1024 * 1024 * 5,
                'skipOnEmpty' => false,
                'maxFiles' => 5
                ]
        ];
    }


    public function upload()
    {
        if ($this->validate()) {

            $dataFiles = [];
            $folder = date("Y-m-d").'/';
            if(FileHelper::createDirectory( $this->uploadDir().$folder)){
                foreach ($this->file as $file){
                    $uuid = Uuid::uuid();
                    $path = $this->uploadDir() . $folder . $uuid . '_' . $file->baseName . '.' . $file->extension;
                    $hash = $file->getHash();
                    //Проверка на уникальность файла
                    if($findOne = File::getFile($file)){
                        $dataFiles[] = ['findOne'=>$findOne];
                        continue;
                    }
                    if(Yii::$app->request->isPost){
                        $file->saveAs($path);
                    }
                    elseif(Yii::$app->request->isPut){
                        $file->saveFilePut($path);
                    }
                    $dataFiles[] = ['path'=>$path,'name'=>$file->baseName . '.' . $file->extension,'uuid'=>$uuid,'file_hash'=>$hash];
                }
            }

                return $dataFiles;

        } else {
            return false;
        }
    }


}