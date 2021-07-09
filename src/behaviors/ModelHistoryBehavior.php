<?php
namespace kirillemko\yci\behaviors;

use yii\db\ActiveRecord;
use yii\base\Behavior;

class ModelHistoryBehavior extends Behavior
{

    public function events()
    {
        return [
            ActiveRecord::EVENT_BEFORE_VALIDATE => 'beforeValidate',
        ];
    }

    public function beforeValidate($event)
    {
        // ...
    }
}