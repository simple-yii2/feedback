<?php

use yii\helpers\Html;
use cms\feedback\frontend\widgets\FeedbackWidget;

$title = Yii::t('feedback', 'Feedback');

$this->title = $title . ' | ' . Yii::$app->name;

Yii::$app->params['breadcrumbs'] = [$title];

?>
<h1><?= Html::encode($title) ?></h1>

<?= FeedbackWidget::widget([
    'layout' => 'horizontal',
]) ?>
