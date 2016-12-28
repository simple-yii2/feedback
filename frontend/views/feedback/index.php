<?php

use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;
use yii\helpers\Html;

$title = Yii::t('feedback', 'Feedback');

$this->title = $title . ' | ' . Yii::$app->name;

$this->params['breadcrumbs'] = [
	$title,
];

?>
<h1><?= Html::encode($title) ?></h1>

<?php $form = ActiveForm::begin([
	'layout' => 'horizontal',
	'enableClientValidation' => false,
]); ?>

	<?= $form->field($model, 'name') ?>

	<?= $form->field($model, 'phone') ?>

	<?= $form->field($model, 'email') ?>

	<?= $form->field($model, 'message')->textarea(['rows' => 5]) ?>

	<?= $form->field($model, 'verificationCode')->widget(Captcha::className(), [
		'options' => ['class' => 'form-control', 'autocomplete' => 'off'],
		'captchaAction' => ['/site/captcha'],
		'template' => '<div class="captcha">{image}<div>{input}</div></div>',
	]) ?>

	<div class="form-group">
		<div class="col-sm-offset-3 col-sm-6">
			<?= Html::submitButton(Yii::t('feedback', 'Send message'), ['class' => 'btn btn-primary']) ?>
		</div>
	</div>

<?php ActiveForm::end(); ?>