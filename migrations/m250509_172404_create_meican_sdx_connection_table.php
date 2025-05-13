<?php

use yii\db\Migration;

/**
 * Handles the creation for table `meican_sdx_connection_table`.
 */
class m250509_172404_create_meican_sdx_connection_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
                   $this->execute("
        CREATE TABLE `meican_sdx_connection` (
        `id` int(11) NOT NULL,
        `user_id` int(11) NOT NULL,
        `ownership` varchar(100) NOT NULL,
        `service_id` varchar(100) NOT NULL,
        `created_at` datetime NOT NULL,
        `deleted_at` datetime DEFAULT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
                ");


                $this->execute("
        ALTER TABLE `meican_sdx_connection`
        ADD PRIMARY KEY (`id`);
                ");
                $this->execute("
        ALTER TABLE `meican_sdx_connection`
        MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
                ");
                $this->execute("
        ALTER TABLE `meican_sdx_connection`
        ADD CONSTRAINT `meican_sdx_connection_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `meican_user` (`id`);
        COMMIT;
                ");
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('meican_sdx_connection');
    }
}
