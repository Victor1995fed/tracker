<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

//$this->title = 'Login';
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Добавление задачи:</p>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'task-form']); ?>

            <?= $form->field($model, 'title')->textInput(['autofocus' => true]) ?>

            <?= $form->field($model, 'description')->textarea(['rows' => '6']) ?>

            <?= $form->field($model, 'category')->dropDownList([
                '0' => 'Активный',
                '1' => 'Отключен',
                '2'=>'Удален'
            ]); ?>
<!---->
<!--            <div style="color:#999;margin:1em 0">-->
<!--                If you forgot your password you can --><?//= Html::a('reset it', ['site/request-password-reset']) ?><!--.-->
<!--                <br>-->
<!--                Need new verification email? --><?//= Html::a('Resend', ['site/resend-verification-email']) ?>
<!--            </div>-->

            <div class="form-group">
                <?= Html::submitButton('Добавить', ['class' => 'btn btn-primary', 'name' => 'task-button']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
