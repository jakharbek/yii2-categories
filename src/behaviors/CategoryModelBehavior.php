<?php
namespace jakharbek\categories\behaviors;

/**
 *
 * @author Jakhar <javhar_work@mail.ru>
 *
 */

use jakharbek\categories\models\Categories;
use Yii;
use yii\base\Behavior;
use yii\db\ActiveRecord;

/**
 * Class CategoryModelBehavior
 * @package jakharbek\categories\behaviors
 * Поведение который добавлаются к behaviors к модели Active Record (Model)
 *
 * @example
 *
 * ```php
        use jakharbek\categories\behaviors\CategoryModelBehavior;

        class Posts extends ActiveRecord
        {
            private $_categoriesform;

            public function behaviors()
            {
                 ...
                        'category_model'=> [
                        'class' => CategoryModelBehavior::className(),
                        'attribute' => 'categoriesform',
                        'separator' => ',',
                        ],
                 ...
            }

            ...

            public function getCategoriesform(){
                return $this->_categoriesform;
            }
            public function setCategoriesform($value){
                return $this->_categoriesform = $value;
            }
        }
 *
 *
 * ```
 */
class CategoryModelBehavior extends Behavior
{
    /**
     * @var string
     * данные который используется в форме (Active Form)
     */
    public $attribute = "categoriesform";
    /**
     * @var string
     * тип данных или сепаратор который разделает данные
     * типы array
     * сепаратор который разделает данные
     */
    public $separator = "array";

    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_UPDATE  => 'beforeInsertData',
            ActiveRecord::EVENT_AFTER_INSERT  => 'afterInsertData',
        ];
    }
    private function getData($categories){
        $data = [];
        if($this->separator == "array")
        {
            if(is_array($categories))
            {
                return $categories;
            }
        }
        if($this->separator !== "array")
        {
            if(!is_array($categories))
            {
                $selecteds = explode($this->separator,$categories);
                if(strlen(implode('',$selecteds)) == 0){return false;}

                foreach ($selecteds as $selected)
                {
                    $data[] = Categories::findOne($selected);
                }
                 return $data;
            }
        }
        return $categories;
    }
    private function unlinkData(){
        $categories = $this->owner->categories;
        if(count($categories) == 0){return false;}
        foreach ($categories as $category):
            $this->owner->unlink('categories',$category,true);
        endforeach;
    }
    public function beforeInsertData(){
        $model = $this->owner;

            //$model::getDb()->transaction(function($db) use ($model) {
                $this->unlinkData();
                $categories = $this->getData($this->owner->{$this->attribute});
                if(!$categories){return true;}
                foreach ($categories as $category):
                    $this->owner->link('categories', $category);
                endforeach;
            //}
    }
    public function afterInsertData(){
        $model = $this->owner;
            //$model::getDb()->transaction(function($db) use ($model) {
            $categories = $this->getData($this->owner->{$this->attribute});
            if(!$categories){return true;}
            foreach ($categories as $category):
                $this->owner->link('categories', $category);
            endforeach;

            //}
    }
    public function categoriesSelected(){
        $categories = $this->owner->categories;
        $data = [];
        if(count($categories) == 0){return [];}
        foreach ($categories as $category):
            $data[$category->id] = $category;
        endforeach;
        return $data;
    }
}