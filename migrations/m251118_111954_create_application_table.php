<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%application}}`.
 */
class m251118_111954_create_application_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%application}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'contest_id' => $this->integer()->notNull(),
            'nomination_id' => $this->integer()->notNull(),
            'age_category_id' => $this->integer()->notNull(),
            'name' => $this->string(100)->notNull(),
            'surname' => $this->string(100)->notNull(),
            'patronymic' => $this->string(100),
            'work_name' => $this->string(255)->notNull(),
            'file_path' => $this->string(255)->notNull(),
            'institution' => $this->string(255),
            'leader' => $this->string(255),
            'status' => $this->string(20)->defaultValue('new'),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);

         $this->addForeignKey(
            'fk-application-user_id',
            '{{%application}}',
            'user_id',
            '{{%user}}',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-application-contest_id',
            '{{%application}}',
            'contest_id',
            '{{%contest}}',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-application-nomination_id',
            '{{%application}}',
            'nomination_id',
            '{{%nomination}}',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-application-age_category_id',
            '{{%application}}',
            'age_category_id',
            '{{%age_category}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%application}}');
    }
}
