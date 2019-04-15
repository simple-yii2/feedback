<?php

namespace cms\feedback\frontend\widgets;

use Yii;
use yii\base\Widget;
use yii\captcha\Captcha;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\MaskedInput;
use cms\feedback\common\models\Feedback;
use cms\feedback\frontend\forms\FeedbackForm;
use cms\feedback\frontend\Module;
use cms\user\common\models\User;

class FeedbackWidget extends Widget
{

    /**
     * @var string
     */
    public $formClass = 'yii\bootstrap\ActiveForm';

    /**
     * @var array
     */
    public $formConfig = [];

    /**
     * @var string
     */
    public $phoneMask = '+1-999-999-9999';

    /**
     * array
     */
    public $buttonOptions = ['class' => 'btn btn-primary'];

    /**
     * @var FeedbackForm|null
     */
    private $_model;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->registerTranslation();
        $this->initModel();
        $this->processModel();

        $this->phoneMask = Yii::t('feedback', $this->phoneMask);
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        $this->renderForm();
    }

    /**
     * Register translation
     * @return void
     */
    private function registerTranslation()
    {
        Module::cmsTranslation();
    }

    /**
     * Model initialization
     * @return void
     */
    private function initModel()
    {
        $object = Feedback::find()->one();
        if ($object === null) {
            return;
        }

        $model = new FeedbackForm($object);
        $user = Yii::$app->getUser()->getIdentity();
        if ($user instanceof User) {
            $model->name = $user->getUsername();
            $model->email = $user->email;
        }

        $model->setAttributes(Yii::$app->getSession()->getFlash('feedback'));

        $model->load(Yii::$app->getRequest()->get());

        $this->_model = $model;
    }

    /**
     * Load form from post, validate and send feedback
     * @return void
     */
    private function processModel()
    {
        $model = $this->_model;
        $app = Yii::$app;

        if ($model === null) {
            return;
        }

        if ($model->load($app->getRequest()->post()) && $model->feedback()) {
            $app->getSession()->setFlash('success', Yii::t('feedback', 'Your message was successfully sent.'));

            if ($app->getResponse()->refresh()) {
                $app->end();
            }
        }
    }

    /**
     * Rendering feedback form
     * @return void
     */
    private function renderForm()
    {
        $model = $this->_model;
        if ($model === null) {
            return;
        }

        $button = Html::submitButton(Yii::t('feedback', 'Send message'), $this->buttonOptions);

        //begin form
        $class = $this->formClass;
        $config = array_replace([
            'enableClientValidation' => false,
        ], $this->formConfig);
        $form = $class::begin($config);

        echo $form->field($model, 'name');
        // https://github.com/yiisoft/yii2/issues/16681
        // echo $form->field($model, 'phone')->widget(MaskedInput::className(), ['mask' => $this->phoneMask]);
        $field = $form->field($model, 'phone');
        echo $field->widget(MaskedInput::className(), ['options' => $field->inputOptions, 'mask' => $this->phoneMask]);
        //---------------------------------------------
        echo $form->field($model, 'email');
        echo $form->field($model, 'message')->textarea(['rows' => 5]);
        // https://github.com/yiisoft/yii2/issues/16681
        // echo $form->field($model, 'verificationCode')->widget(Captcha::className(), [
        //     'options' => ['class' => 'form-control', 'autocomplete' => 'off'],
        //     'captchaAction' => ['/site/captcha'],
        //     'template' => '<div class="captcha">{image}<div>{input}</div></div>',
        // ]);
        $field = $form->field($model, 'verificationCode');
        echo $field->widget(Captcha::className(), [
            'options' => array_merge(['class' => 'form-control', 'autocomplete' => 'off'], $field->inputOptions),
            'captchaAction' => ['/site/captcha'],
            'template' => '<div class="captcha">{image}<div>{input}</div></div>',
        ]);
        //---------------------------------------------
        echo Html::tag('div', $button, ['class' => 'form-group']);

        $class::end();
    }

}
