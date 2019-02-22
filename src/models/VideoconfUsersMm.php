<?php

namespace lispa\amos\videoconference\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "videoconf_users_mm".
 */
class VideoconfUsersMm extends \lispa\amos\videoconference\models\base\VideoconfUsersMm {

    public function representingColumn() {
        return [
                //inserire il campo o i campi rappresentativi del modulo
        ];
    }

    public function attributeHints() {
        return [
        ];
    }

    /**
     * Returns the text hint for the specified attribute.
     * @param string $attribute the attribute name
     * @return string the attribute hint
     */
    public function getAttributeHint($attribute) {
        $hints = $this->attributeHints();
        return isset($hints[$attribute]) ? $hints[$attribute] : null;
    }

    public function rules() {
        return ArrayHelper::merge(parent::rules(), [
        ]);
    }

    public function attributeLabels() {
        return
                ArrayHelper::merge(
                        parent::attributeLabels(), [
        ]);
    }

}
