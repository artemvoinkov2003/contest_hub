<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%evaluation_score}}`.
 */
class m251118_141300_create_evaluation_score_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%evaluation_score}}', [
            'id' => $this->primaryKey(),
            'evaluation_id' => $this->integer()->notNull(),
            'criteria_id' => $this->integer()->notNull(),
            'score' => $this->integer()->notNull(),
        ]);

        $this->addForeignKey(
            'fk-evaluation_score-evaluation_id',
            'evaluation_score',
            'evaluation_id',
            'evaluation',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-evaluation_score-criteria_id',
            'evaluation_score',
            'criteria_id',
            'criteria',
            'id',
            'CASCADE'
        );

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%evaluation_score}}');
        $this->dropForeignKey('fk-evaluation_score-criteria_id', 'evaluation_score');
        $this->dropForeignKey('fk-evaluation_score-evaluation_id', 'evaluation_score');
    }
}
