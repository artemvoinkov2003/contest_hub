<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%expert_assignment}}`.
 */
class m251118_140345_create_expert_assignment_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%expert_assignment}}', [
            'id' => $this->primaryKey(),
            'expert_id' => $this->integer()->notNull(),
            'contest_id' => $this->integer()->notNull(),
            'nomination_id' => $this->integer()->notNull(),
            'age_category_id' => $this->integer()->notNull(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);

        $this->addForeignKey(
            'fk-expert_assignment-expert_id',
            'expert_assignment',
            'expert_id',
            'user',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-expert_assignment-contest_id',
            'expert_assignment',
            'contest_id',
            'contest',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-expert_assignment-nomination_id',
            'expert_assignment',
            'nomination_id',
            'nomination',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-expert_assignment-age_category_id',
            'expert_assignment',
            'age_category_id',
            'age_category',
            'id',
            'CASCADE'
        );

        $this->insert('expert_assignment', [
            'expert_id' => 1,
            'contest_id' => 1,
            'nomination_id' => 1,
            'age_category_id' => 3,
        ]);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%expert_assignment}}');
        $this->dropForeignKey('fk-expert_assignment-age_category_id', 'expert_assignment');
        $this->dropForeignKey('fk-expert_assignment-nomination_id', 'expert_assignment');
        $this->dropForeignKey('fk-expert_assignment-contest_id', 'expert_assignment');
        $this->dropForeignKey('fk-expert_assignment-expert_id', 'expert_assignment');
    }
}
