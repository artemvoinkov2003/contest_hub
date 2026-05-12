<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%nomination}}`.
 */
class m251118_111441_create_nomination_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%nomination}}', [
            'id' => $this->primaryKey(),
            'contest_id' => $this->integer()->notNull(),
            'name' => $this->string(255)->notNull(),
        ]);

        $this->addForeignKey(
            'fk-nomination-contest_id',
            '{{%nomination}}',
            'contest_id',
            '{{%contest}}',
            'id',
            'CASCADE'
        );

        $this->batchInsert('{{%nomination}}', ['contest_id', 'name'], [
            [1, 'Живопись'],
            [1, 'Графика'],
            [1, 'Скульптура'],
            [2, 'Фотография'],
            [2, 'Дизайн'],
            [2, 'Декоративно-прикладное искусство'],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%nomination}}');
        
    }
}
