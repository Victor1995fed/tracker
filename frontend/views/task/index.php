<?php
use yii\helpers\Html;
/* @var $this yii\web\View */

$this->title = 'Ваши задачи';

?>
<div class="site-index">
    <h1><?= Html::encode($this->title) ?></h1>
    <p>
        <?= Html::a('Добавить', ['add-task'], ['class' => 'btn btn-success']); ?>
    </p>
</div>
