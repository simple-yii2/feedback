<?php

namespace cms\feedback\common\models;

use yii\db\ActiveRecord;

class Feedback extends ActiveRecord
{

	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'Feedback';
	}

}
