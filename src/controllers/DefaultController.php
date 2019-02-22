<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    lispa\amos\videoconference\controllers
 * @category   CategoryName
 */

namespace lispa\amos\videoconference\controllers;

use lispa\amos\dashboard\controllers\base\DashboardController;
use Yii;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\filters\AccessControl;

class DefaultController extends DashboardController {

    /**
     * @var string $layout Layout per la dashboard interna.
     */
    public $layout = "@vendor/lispa/amos-core/views/layouts/dashboard_interna";
    
    
    /**
     * Lists all Videoconference models.
     * @return mixed
     */
    public function actionIndex() {
        return $this->redirect(['/videoconference/videoconf/index']);

       /* Url::remember();

        $params = [
            'currentDashboard' => $this->getCurrentDashboard()
        ];

        return $this->render('index', $params);*/
       
    }

}
