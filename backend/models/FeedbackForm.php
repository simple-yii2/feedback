<?php

namespace cms\feedback\backend\models;

use Yii;
use yii\base\Model;

/**
 * Editing form
 */
class FeedbackForm extends Model
{

	/**
	 * @var string E-mail
	 */
	public $email;

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

		//attributes
		$this->email = $object->email;

		parent::__construct($config);
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'email' => Yii::t('feedback', 'E-mail'),
		];
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			['email', 'string', 'max' => 100],
		];
	}

	/**
	 * Saving object using model attributes
	 * @return boolean
	 */
	public function save()
	{
		if (!$this->validate())
			return false;

		$object = $this->_object;

		$object->email = $this->email;

		if (!$object->save(false))
			return false;

		return true;
	}

}
