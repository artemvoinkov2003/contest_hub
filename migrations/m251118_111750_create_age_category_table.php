<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%age_category}}`.
 */
class m251118_111750_create_age_category_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%age_category}}', [
            'id' => $this->primaryKey(),
            'contest_id' => $this->integer()->notNull(),
            'name' => $this->string(255)->notNull(),
        ]);

        $this->addForeignKey(
            'fk-age_category-contest_id',
            '{{%age_category}}',
            'contest_id',
            '{{%contest}}',
            'id',
            'CASCADE'
        );

        $this->batchInsert('{{%age_category}}', ['contest_id', 'name'], [
            [1, 'Дети 6-9 лет'],
            [1, 'Дети 10-13 лет'],
            [1, 'Юноши 14-17 лет'],
            [2, 'Молодежь 18-25 лет'],
            [2, 'Взрослые 26-35 лет'],
            [2, 'Профессионалы 36+ лет'],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%age_category}}');
    }
}
