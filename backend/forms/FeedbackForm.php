<?php

namespace cms\feedback\backend\forms;

use Yii;
use yii\base\Model;
use cms\feedback\common\models\Feedback;

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
     * @var Feedback
     */
    private $_object;

    /**
     * @inheritdoc
     * @param Feedback $object 
     */
    public function __construct(Feedback $object = null, $config = [])
    {
        if ($object === null) {
            $object = new Feedback;
        }

        $this->_object = $object;

        parent::__construct(array_replace([
            'email' => $object->email,
        ], $config));
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
     * @param boolean $runValidation 
     * @return boolean
     */
    public function save($runValidation = true)
    {
        if ($runValidation && !$this->validate()) {
            return false;
        }

        $object = $this->_object;

        $object->email = $this->email;

        if (!$object->save(false)) {
            return false;
        }

        return true;
    }

}
