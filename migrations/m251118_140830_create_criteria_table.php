<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%criteria}}`.
 */
class m251118_140830_create_criteria_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%criteria}}', [
            'id' => $this->primaryKey(),
            'nomination_id' => $this->integer()->notNull(),
            'name' => $this->string(255)->notNull(),
            'max_score' => $this->integer()->defaultValue(10),
            'order' => $this->integer()->defaultValue(0),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);

        $this->addForeignKey(
            'fk-criteria-nomination_id',
            'criteria',
            'nomination_id',
            'nomination',
            'id',
            'CASCADE'
        );

        $this->insert('criteria', [
            'nomination_id' => 1,
            'name' => 'Мастерство по направлению',
            'max_score' => 10,
            'order' => 1,
        ]);

        $this->insert('criteria', [
            'nomination_id' => 1,
            'name' => 'Артистизм / Раскрытие художественного образа',
            'max_score' => 10,
            'order' => 2,
        ]);

        $this->insert('criteria', [
            'nomination_id' => 1,
            'name' => 'Сценическая культура',
            'max_score' => 10,
            'order' => 3,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%criteria}}');
        $this->dropForeignKey('fk-criteria-nomination_id', 'criteria');
    }
}
