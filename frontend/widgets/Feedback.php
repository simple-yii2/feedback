<?php

namespace cms\feedback\frontend\widgets;

use Yii;
use yii\base\Widget;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class Feedback extends Widget
{

	/**
	 * @var string
	 * @see [[yii\bootstrap\ActiveForm::$layout]]
	 */
	public $layout = 'default';

	/**
	 * @var array|Closure
	 * @see [[yii\bootstrap\ActiveForm::$fieldConfig]]
	 */
	public $fieldConfig = [];

	/**
	 * @var cms\feedback\frontend\models\FeedbackForm|null
	 */
	private $_model;

	/**
	 * @inheritdoc
	 */
	public function init()
	{
		parent::init();

		$this->initModel();
		$this->processModel();
	}

	/**
	 * @inheritdoc
	 */
	public function run()
	{
		$this->renderForm();
	}

	/**
	 * Model initialization
	 * @return void
	 */
	private function initModel()
	{
		$object = \cms\feedback\common\models\Feedback::find()->one();
		if ($object === null)
			return;

		$model = new \cms\feedback\frontend\models\FeedbackForm($object);
		$user = Yii::$app->getUser()->getIdentity();
		if ($user instanceof \cms\user\common\models\User) {
			$model->name = $user->getUsername();
			$model->email = $user->email;
		}

		$this->_model = $model;
	}

	/**
	 * Load form from post, validate and send feedback
	 * @return void
	 */
	private function processModel()
	{
		$model = $this->_model;

		if ($model->load(Yii::$app->getRequest()->post()) && $model->feedback()) {
			Yii::$app->session->setFlash('success', Yii::t('feedback', 'Your message was successfully sent.'));
			$this->initModel();
		}
	}

	/**
	 * Rendering feedback form
	 * @return void
	 */
	private function renderForm()
	{
		$model = $this->_model;
		if ($model === null)
			return;

		$form = ActiveForm::begin([
			'layout' => $this->layout,
			'fieldConfig' => $this->fieldConfig,
			'enableClientValidation' => false,
		]);

		echo $form->field($model, 'name');
		echo $form->field($model, 'phone');
		echo $form->field($model, 'email');
		echo $form->field($model, 'message')->textarea(['rows' => 5]);
		echo $form->field($model, 'verificationCode')->widget(Captcha::className(), [
			'options' => ['class' => 'form-control', 'autocomplete' => 'off'],
			'captchaAction' => ['/site/captcha'],
			'template' => '<div class="captcha">{image}<div>{input}</div></div>',
		]);
		$this->renderButton();

		ActiveForm::end();
	}

	/**
	 * Renders submit button for feedback form
	 * @return void
	 */
	private function renderButton()
	{
		$css = [
			'offset' => 'col-sm-offset-3',
			'wrapper' => 'col-sm-6',
		];
		$config = $this->fieldConfig;
		if (is_array($config)) {
			$css['offset'] = ArrayHelper::getValue($config, ['horizontalCssClasses', 'offset'], $css['offset']);
			$css['wrapper'] = ArrayHelper::getValue($config, ['horizontalCssClasses', 'wrapper'], $css['wrapper']);
		}
		$button = Html::submitButton(Yii::t('feedback', 'Send message'), ['class' => 'btn btn-primary']);
		if ($this->layout == 'horizontal') {
			$wrapper = Html::tag('div', $button, ['class' => $css['offset'] . ' ' . $css['wrapper']]);
		} else {
			$wrapper = $button;
		}
		echo Html::tag('div', $wrapper, ['class' => 'form-group']);
	}

}
