<?php

namespace cms\feedback\frontend\models;

use Yii;
use yii\base\Model;

class FeedbackForm extends Model
{

	/**
	 * @var string Name
	 */
	public $name;

	/**
	 * @var string Phone
	 */
	public $phone;

	/**
	 * @var string E-mail
	 */
	public $email;

	/**
	 * @var string Message
	 */
	public $message;

	/**
	 * @var string Verification code
	 */
	public $verificationCode;

	/**
	 * @var cms\feedback\common\models\Feedback
	 */
	private $_object;

	/**
	 * @inheritdoc
	 * @param cms\feedback\common\models\Feedback $object 
	 */
	public function __construct(\cms\feedback\common\models\Feedback $object, $config = [])
	{
		$this->_object = $object;

		parent::__construct($config);
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'name' => Yii::t('feedback', 'Name'),
			'phone' => Yii::t('feedback', 'Phone'),
			'email' => Yii::t('feedback', 'E-mail'),
			'message' => Yii::t('feedback', 'Message'),
			'verificationCode' => Yii::t('feedback', 'Verification code'),
		];
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['name', 'message'], 'required'],
			['phone', 'string'],
			['email', 'email'],
			['verificationCode', 'captcha'],
		];
	}

	public function feedback()
	{
		if (!$this->validate())
			return false;

		$message = Yii::$app->mailer->compose()
			->setTo($this->_object->email)
			->setSubject(Yii::t('feedback', 'New message'))
			->setHtmlBody(Yii::$app->controller->renderPartial('mail', ['model' => $this]));

		if (!empty($this->email))
			$message->setReplyTo($this->email);

		return $message->send();
	}

}
