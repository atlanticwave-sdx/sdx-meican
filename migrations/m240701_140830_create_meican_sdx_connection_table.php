<?php

use yii\db\Migration;

class m240701_140830_create_meican_sdx_connection_table extends Migration
{
    public function up()
    {
                $this->execute("
        CREATE TABLE `meican_sdx_connections` (
        `sdx_connection_id` int(11) NOT NULL,
        `user_id` int(11) NOT NULL,
        `service_id` varchar(100) NOT NULL,
        `created_at` datetime NOT NULL,
        `deleted_at` datetime DEFAULT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
                ");


                $this->execute("
        ALTER TABLE `meican_sdx_connections`
        ADD PRIMARY KEY (`sdx_connection_id`),
        ADD UNIQUE KEY `user_id` (`user_id`);
                ");
                $this->execute("
        ALTER TABLE `meican_sdx_connections`
        MODIFY `sdx_connection_id` int(11) NOT NULL AUTO_INCREMENT;
                ");
                $this->execute("
        ALTER TABLE `meican_sdx_connections`
        ADD CONSTRAINT `meican_sdx_connections_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `meican_user` (`id`);
        COMMIT;
                ");
    }

    public function down()
    {
        echo "m240701_140830_create_meican_sdx_connection_table cannot be reverted.\n";

        return false;
    }
}