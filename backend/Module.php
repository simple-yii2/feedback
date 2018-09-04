<?php

namespace cms\feedback\backend;

use Yii;
use cms\components\BackendModule;

class Module extends BackendModule
{

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
    protected function cmsMenu()
    {
        if (!Yii::$app->user->can('Feedback')) {
            return [];
        }

        return [
            'label' => Yii::t('feedback', 'Feedback'),
            'url' => ['/feedback/feedback/index'],
        ];
    }

}
