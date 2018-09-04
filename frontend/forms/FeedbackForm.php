<?php

namespace cms\feedback\frontend\forms;

use Yii;
use yii\base\Model;
use yii\web\NotFoundHttpException;
use cms\feedback\common\models\Feedback;

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
     * @var Feedback
     */
    private $_object;

    /**
     * @inheritdoc
     * @param Feedback|null $object 
     */
    public function __construct(Feedback $object = null, $config = [])
    {
        if ($object === null) {
            $object = new Feedback;
        }

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
            'verificationCode' => Yii::t('feedback', 'Code'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'phone', 'message'], 'string'],
            ['email', 'email'],
            ['verificationCode', 'captcha'],
            [['name', 'message', 'verificationCode'], 'required'],
        ];
    }

    /**
     * Sending the message
     * @return boolean
     */
    public function feedback()
    {
        if (!$this->validate()) {
            return false;
        }

        $view = dirname(dirname(__DIR__)) . '/mail/feedback.php';

        $from = $this->email;
        if (Yii::$app->mailer->transport instanceof \Swift_SmtpTransport) {
            $from = Yii::$app->mailer->transport->getUsername();
        }

        $message = Yii::$app->mailer->compose()
            ->setTo($this->_object->email)
            ->setSubject(Yii::t('feedback', 'New message'))
            ->setHtmlBody(Yii::$app->getView()->renderFile($view, ['model' => $this]));

        if (!empty($from)) {
            $message->setFrom($from);
        }

        if (!empty($this->email)) {
            $message->setReplyTo($this->email);
        }

        return $message->send();
    }

}
