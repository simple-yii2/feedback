<?php

use yii\helpers\Html;

use cms\feedback\frontend\widgets\Feedback;

$title = Yii::t('feedback', 'Feedback');

$this->title = $title . ' | ' . Yii::$app->name;

?>
<h1><?= Html::encode($title) ?></h1>

<?= Feedback::widget([
	'layout' => 'horizontal',
]) ?>
