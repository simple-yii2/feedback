<?php

namespace cms\feedback\frontend\controllers;

use Yii;
use yii\web\NotFoundHttpException;
use yii\web\Controller;

use cms\feedback\common\models\Feedback;
use cms\feedback\frontend\models\FeedbackForm;

class FeedbackController extends Controller
{

	/**
	 * Form
	 * @return string
	 */
	public function actionIndex()
	{
		$object = Feedback::find()->one();
		if ($object === null)
			throw new NotFoundHttpException(Yii::t('yii', 'Page not found.'));

		$model = new FeedbackForm($object);

		$user = Yii::$app->getUser()->getIdentity();
		if ($user instanceof \cms\user\common\models\User) {
			$model->name = $user->getUsername();
			$model->email = $user->email;
		}

		if ($model->load(Yii::$app->getRequest()->post()) && $model->feedback()) {
			Yii::$app->session->setFlash('success', Yii::t('feedback', 'Your message was successfully sent.'));
			return $this->refresh();
		}

		return $this->render('index', [
			'model' => $model,
		]);
	}

}
