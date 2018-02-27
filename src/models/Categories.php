<?php

namespace jakharbek\categories\models;

use Yii;

use yii\behaviors\SluggableBehavior;
use yii\helpers\ArrayHelper;
use jakharbek\langs\components\Lang;
use jakharbek\langs\components\ModelBehavior;
use jakharbek\posts\models\Posts;

/**
 * This is the model class for table "category".
 *
 * @property int $id
 * @property int $root
 * @property int $lft
 * @property int $rgt
 * @property int $lvl
 * @property string $name
 * @property string $icon
 * @property int $icon_type
 * @property bool $active
 * @property bool $selected
 * @property bool $disabled
 * @property bool $readonly
 * @property bool $visible
 * @property bool $collapsed
 * @property bool $movable_u
 * @property bool $movable_d
 * @property bool $movable_l
 * @property bool $movable_r
 * @property bool $removable
 * @property bool $removable_all
 */
class Categories extends \kartik\tree\models\Tree
{

    public function behaviors()
    {
        return ArrayHelper::merge(
            parent::behaviors(),
            [
                'slug' => [
                    'class' => SluggableBehavior::className(),
                    'attribute' => 'name',
                    'slugAttribute' => 'slug',
                    'ensureUnique' => true
                ],
                'lang' => [
                    'class' => ModelBehavior::className(),
                ],
            ]
        );
    }
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'categories';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $rules = parent::rules();
        $rules[] =  [
                        ['name','description','slug'],
                        'string'
                    ];
        $rules[] =  [
                        ['type','lang_hash'],
                        'safe'
                    ];
        $rules[] =  [
                        ['lang'],
                        'default','value' => Lang::getLangId()
                    ];
        return $rules;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'root' => 'Root',
            'lft' => 'Lft',
            'rgt' => 'Rgt',
            'lvl' => 'Lvl',
            'active' => 'Active',
            'selected' => 'Selected',
            'disabled' => 'Disabled',
            'readonly' => 'Readonly',
            'visible' => 'Visible',
            'collapsed' => 'Collapsed',
            'movable_u' => 'Movable U',
            'movable_d' => 'Movable D',
            'movable_l' => 'Movable L',
            'movable_r' => 'Movable R',
            'removable' => 'Removable',
            'removable_all' => 'Removable All',
            'name' => 'Name',
            'icon' => 'Icon',
            'icon_type' => 'Icon Type',
            'slug' => 'Slug',
            'description' => 'Description',
            'image' => 'Image',
            'type' => 'Type',
            'lang' => 'Lang',
            'lang_hash' => 'Lang hash'
        ];
    }

    /**
     * @inheritdoc
     * @return CategoriesQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CategoriesQuery(get_called_class());
    }
}
