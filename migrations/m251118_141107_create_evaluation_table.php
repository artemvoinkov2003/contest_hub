<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%evaluation}}`.
 */
class m251118_141107_create_evaluation_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%evaluation}}', [
            'id' => $this->primaryKey(),
            'application_id' => $this->integer()->notNull(),
            'expert_id' => $this->integer()->notNull(),
            'status' => $this->string(20)->defaultValue('draft'), 
            'total_score' => $this->decimal(5,2)->defaultValue(0),
            'notes' => $this->text(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ]);

        $this->addForeignKey(
            'fk-evaluation-application_id',
            'evaluation',
            'application_id',
            'application',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-evaluation-expert_id',
            'evaluation',
            'expert_id',
            'user',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%evaluation}}');
        $this->dropForeignKey('fk-evaluation-expert_id', 'evaluation');
        $this->dropForeignKey('fk-evaluation-application_id', 'evaluation');
    }
}
