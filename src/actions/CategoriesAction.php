<?php
namespace jakharbek\categories\actions;

use Yii;
use yii\base\Action;

class CategoriesAction extends Action{

    public function run(){

        return $this->controller->render('@vendor/jakharbek/yii2-categories/src/views/categories');
    }

}