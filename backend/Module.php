<?php

namespace cms\feedback\backend;

use Yii;

use cms\components\BackendModule;

class Module extends BackendModule {

	/**
	 * @inheritdoc
	 */
	public static function moduleName()
	{
		return 'feedback';
	}

	/**
	 * @inheritdoc
	 */
	protected static function cmsSecurity()
	{
		$auth = Yii::$app->getAuthManager();
		if ($auth->getRole('Feedback') === null) {
			//role
			$role = $auth->createRole('Feedback');
			$auth->add($role);
		}
	}

	/**
	 * @inheritdoc
	 */
	protected function cmsMenu($base)
	{
		if (!Yii::$app->user->can('Feedback'))
			return [];

		return [
			['label' => Yii::t('feedback', 'Feedback'), 'url' => ["$base/feedback/feedback/index"]],
		];
	}

}
