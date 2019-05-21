<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use frontend\models\Priorities;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\bootstrap\ActiveForm;
use frontend\models\Project;
use mihaildev\ckeditor\CKEditor;

$this->title = 'Добавление проекта:  ';
$this->params['breadcrumbs'][] = ['label' => 'Задачи', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
    <h1><?= Html::encode($this->title) ?></h1>


    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'project-form']); ?>

            <?= $form->field($model, 'title')->textInput(['autofocus' => true]) ?>

            <div class="form-group">
                <?= Html::submitButton('Добавить', ['class' => 'btn btn-primary', 'name' => 'project-button']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
