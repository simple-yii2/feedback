<?php

namespace cms\feedback\frontend;

use Yii;

/**
 * Frontend module
 */
class Module extends \yii\base\Module
{

	/**
	 * @inheritdoc
	 */
	public function init()
	{
		parent::init();

		if (!isset(Yii::$app->i18n->translations['feedback'])) {
			Yii::$app->i18n->translations['feedback'] = [
				'class' => 'yii\i18n\PhpMessageSource',
				'sourceLanguage' => 'en-US',
				'basePath' => dirname(__DIR__) . '/messages',
			];
		}
	}

}
