<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%contest}}`.
 */
class m251118_111220_create_contest_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%contest}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull(),
            'description' => $this->text(),
            'image' => $this->string(255),
            'start_date' => $this->date()->notNull(),
            'end_date' => $this->date()->notNull(),
            'status' => $this->boolean()->defaultValue(1),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);

        $this->batchInsert('{{%contest}}', ['name', 'description', 'start_date', 'end_date', 'status'], [
            ['Весенний конкурс искусств', 'Ежегодный весенний конкурс для творческой молодежи', '2025-03-01', '2025-05-31', 1],
            ['Осенний фестиваль талантов', 'Крупнейший фестиваль творчества и искусства', '2025-09-01', '2025-11-30', 1],
        ]);
    }

        

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%contest}}');
    }
}
