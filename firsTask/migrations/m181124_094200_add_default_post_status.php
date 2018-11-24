<?php

use yii\db\Migration;

/**
 * Class m181124_094200_add_default_post_status
 */
class m181124_094200_add_default_post_status extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('post', 'status',$this->smallInteger()->defaultValue(1));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m181124_094200_add_default_post_status cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m181124_094200_add_default_post_status cannot be reverted.\n";

        return false;
    }
    */
}
