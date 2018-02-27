<?php

namespace jakharbek\categories\models;

use Yii;
use yii\helpers\ArrayHelper;
use creocoder\nestedsets\NestedSetsQueryBehavior;
use jakharbek\langs\components\QueryBehavior;
/**
 * This is the ActiveQuery class for [[Categories]].
 *
 * @see Category
 */
class CategoriesQuery extends \yii\db\ActiveQuery
{
    public function behaviors() {
        return ArrayHelper::merge(parent::behaviors(),[
            NestedSetsQueryBehavior::className(),
            QueryBehavior::className(),
        ]);
    }

    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return Category[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Category|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
    public function type($type = null)
    {
        return $this->andWhere(['type' => $type]);
    }

    public function allTypes(){
        return [
            100 => 'Посты',
            200 => 'Страницы',
            300 => 'Кастинги'
        ];
    }
    /**
     * @inheritdoc
     * @method - Build Tree
     * @param Array keys - keys which has been selected
     * @example [12 => [...meta...],15 => [...meta...]]
     * @return Array data
    */
    public function buildTreeByRoot($keys = [],$type = null)
    {
        $i = 0;
        if($type == null){
            $roots = Categories::find()->lang()->roots()->asArray()->all();
        }else{
            $roots = Categories::find()->lang()->type($type)->roots()->asArray()->all();
        }
        foreach ($roots as $root):
            $data[$i]['key'] = $root['id'];
            $data[$i]['title'] = $root['name'];
            if(array_key_exists($data[$i]['key'],$keys)){
                $data[$i]['selected'] = true;
            }
            if(count($this->childrensById($root['id'])) > 0):
                $data[$i]['folder'] = true;
                $data[$i]['children'] = $this->childrensById($root['id'],$keys);
                $data[$i]['expanded'] = true;
            endif;
            $i++;
        endforeach;
        return $data;
    }
    /**
     * @inheritdoc
     * @method - Build Tree by id
     * @param Array keys - keys which has been selected
     * @example [12 => [...meta...],15 => [...meta...]]
     * @return Array data
     */
    public function childrensById($id = null,$keys = []){
        $i = 0;
        $count = Categories::findOne($id)->children(1)->count();
        if($count == 0){return [];}
        $data = [];
        $childs = Categories::findOne($id)->children(1)->asArray()->all();
        foreach ($childs as $child):
            $data[$i]['key'] = $child['id'];
            $data[$i]['title'] = $child['name'];
            if(array_key_exists($data[$i]['key'],$keys)){
                $data[$i]['selected'] = true;
            }
            if(count($this->childrensById($child['id'])) > 0):
                $data[$i]['folder'] = true;
                $data[$i]['children'] = $this->childrensById($child['id'],$keys);
                $data[$i]['expanded'] = true;
            endif;
            $i++;
        endforeach;
        return $data;
    }
}
