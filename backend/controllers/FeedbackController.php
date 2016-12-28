<?php

namespace cms\feedback\backend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;

use cms\feedback\backend\models\FeedbackForm;
use cms\feedback\common\models\Feedback;

class FeedbackController extends Controller
{

	/**
	 * @inheritdoc
	 */
	public function behaviors()
	{
		return [
			'access' => [
				'class' => AccessControl::className(),
				'rules' => [
					['allow' => true, 'roles' => ['Feedback']],
				],
			],
		];
	}

	/**
	 * Form
	 * @return string
	 */
	public function actionIndex()
	{
		$object = Feedback::find()->one();
		if ($object === null)
			$object = new Feedback;

		$model = new FeedbackForm($object);

		if ($model->load(Yii::$app->getRequest()->post()) && $model->save()) {
			Yii::$app->session->setFlash('success', Yii::t('feedback', 'Changes saved successfully.'));
			return $this->redirect(['index']);
		}

		return $this->render('index', [
			'model' => $model,
		]);
	}

}
