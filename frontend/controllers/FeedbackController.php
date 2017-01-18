<?php

namespace cms\feedback\frontend\controllers;

use Yii;
use yii\web\NotFoundHttpException;
use yii\web\Controller;

use cms\feedback\common\models\Feedback;

class FeedbackController extends Controller
{

	/**
	 * Form
	 * @return string
	 */
	public function actionIndex()
	{
		if (Feedback::find()->count == 0)
			throw new NotFoundHttpException(Yii::t('yii', 'Page not found.'));

		return $this->render('index');
	}

}
