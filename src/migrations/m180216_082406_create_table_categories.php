<?php

use yii\db\Migration;

class m180216_082406_create_table_categories extends Migration
{
    const TABLE_NAME = '{{%categories}}';

    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }
        $this->createTable(self::TABLE_NAME, [
            '[[id]]' =>'pk',
            '[[root]]' => $this->integer(),
            '[[lft]]' => $this->integer()->notNull(),
            '[[rgt]]' => $this->integer()->notNull(),
            '[[lvl]]' => $this->smallInteger(5)->notNull(),
            '[[active]]' => $this->boolean()->notNull()->defaultValue(true),
            '[[selected]]' => $this->boolean()->notNull()->defaultValue(false),
            '[[disabled]]' => $this->boolean()->notNull()->defaultValue(false),
            '[[readonly]]' => $this->boolean()->notNull()->defaultValue(false),
            '[[visible]]' => $this->boolean()->notNull()->defaultValue(true),
            '[[collapsed]]' => $this->boolean()->notNull()->defaultValue(false),
            '[[movable_u]]' => $this->boolean()->notNull()->defaultValue(true),
            '[[movable_d]]' => $this->boolean()->notNull()->defaultValue(true),
            '[[movable_l]]' => $this->boolean()->notNull()->defaultValue(true),
            '[[movable_r]]' => $this->boolean()->notNull()->defaultValue(true),
            '[[removable]]' => $this->boolean()->notNull()->defaultValue(true),
            '[[removable_all]]' => $this->boolean()->notNull()->defaultValue(false),
            '[[name]]' => $this->string(500)->notNull(),
            '[[icon]]' => $this->string(255),
            '[[icon_type]]' => $this->smallInteger(1)->notNull()->defaultValue(1),
            '[[description]]' => $this->text(),
            '[[slug]]'=>$this->string(500),
            '[[type]]' => $this->integer(255),
            '[[lang_hash]]' => $this->string(255),
            '[[lang]]' => $this->integer(255),
        ], $tableOptions);

        $this->createIndex('tree_NK1', self::TABLE_NAME, 'root');
        $this->createIndex('tree_NK2', self::TABLE_NAME, 'lft');
        $this->createIndex('tree_NK3', self::TABLE_NAME, 'rgt');
        $this->createIndex('tree_NK4', self::TABLE_NAME, 'lvl');
        $this->createIndex('tree_NK5', self::TABLE_NAME, 'active');

        /*
      * Создание индекса для создание отношение:
      * Языка - langs
      */
        $this->createIndex(
            'idx-categories-langs-lang',
            '{{%categories}}',
            '[[lang]]'
        );
        //Создание отношение
        $this->addForeignKey(
            'fk-categories-langs-lang',
            '{{%categories}}',
            '[[lang]]',
            '{{%langs}}',
            '[[lang_id]]',
            'CASCADE'
        );
    }


    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable(self::TABLE_NAME);
    }
}