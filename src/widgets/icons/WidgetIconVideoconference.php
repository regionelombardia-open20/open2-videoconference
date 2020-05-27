<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    Open20Package
 * @category   CategoryName
 */

namespace open20\amos\videoconference\widgets\icons;

use open20\amos\core\widget\WidgetIcon;
use open20\amos\core\widget\WidgetAbstract;
use open20\amos\core\icons\AmosIcons;
use open20\amos\videoconference\AmosVideoconference;
use Yii;
use yii\helpers\ArrayHelper;

// use open20\amos\comuni\AmosComuni;

class WidgetIconVideoconference extends WidgetIcon
{

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $paramsClassSpan = [
            'bk-backgroundIcon',
            'color-lightPrimary'
        ];

        $this->setLabel(AmosVideoconference::tHtml('amosvideoconference', 'Videoconference'));
        $this->setDescription(AmosVideoconference::t('amosvideoconference', 'Plugin per videoconferenze'));

        if (!empty(\Yii::$app->params['dashboardEngine']) && \Yii::$app->params['dashboardEngine'] == WidgetAbstract::ENGINE_ROWS) {
            $this->setIconFramework(AmosIcons::IC);
            $this->setIcon('videoconferenza');
            $paramsClassSpan = [];
        } else {
            $this->setIconFramework('dash');
            $this->setIcon('video-camera');
        }

        $this->setUrl(Yii::$app->urlManager->createUrl(['/videoconference/videoconf/index']));
        $this->setModuleName('videoconference');
        $this->setNamespace(__CLASS__);

        $this->setClassSpan(
            ArrayHelper::merge(
                $this->getClassSpan(),
                $paramsClassSpan
            )
        );
    }

}
