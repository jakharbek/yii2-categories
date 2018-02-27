Categories
==========
Categories

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist jakharbek/yii2-categories "*"
```

or add

```
"jakharbek/yii2-categories": "*"
```

to the require section of your `composer.json` file.


Usage
-----

Once the extension is installed, simply use it in your code by  :

You need to connect a controller or an action to the controller

controller

```php

    'controllerMap' => [ 
        'categories' => 'jakharbek\categories\controllers\CategoriesController'
    ],

```

action

```php

   public function actions()
       {
           return [ 
               'categories' => [
                   'class' => 'jakharbek\categories\actions\CategoriesAction'
               ] 
           ];
       }

```

You must have an extension
```
jakharbek/yii2-langs
```

You need to connect i18n for translations

```php
 'jakhar-categories' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@vendor/jakharbek/yii2-categories/src/messages',
                    'sourceLanguage' => 'en',
                    'fileMap' => [
                        'jakhar-categories'       => 'main.php',
                    ],
                ],
```

and migrate the database

```php
yii migrate --migrationPath=@vendor/jakharbek/yii2-categories/src/migrations
```



Update (Active Record) - Single
-----

example with Posts elements

You must connect behavior to your database model (Active Record)
```php
 'category_model'=> [
                        'class' => CategoryModelBehavior::className(),
                        'attribute' => 'categoriesform',
                        'separator' => ',',
                        ],
```

example

```php
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
```

Afterwards you need to add your widget form.
```php
jakharbek\categories\widgets\CategoriesWidget::widget
```
example
```php
echo jakharbek\categories\widgets\CategoriesWidget::widget([
  'selected' => $model->categoriesSelected(),
  'model_db' => $model,'name' => 'Posts[categoriesform]'
  ]);
```

and of course do not forget to prescribe links for your model

```php
    public function getPostscategories()
    {
        return $this->hasMany(Postscategories::className(), ['post_id' => 'post_id']);
    }


    public function getCategories()
    {
        return $this->hasMany(Categories::className(), ['id' => 'id'])->viaTable('postscategories', ['post_id' => 'post_id']);
    }
```

It's all!

