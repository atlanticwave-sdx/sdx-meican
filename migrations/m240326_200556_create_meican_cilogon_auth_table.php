<?php

use yii\db\Migration;

class m240326_200556_create_meican_cilogon_auth_table extends Migration
{
    public function up()
    {
                $this->execute("
        CREATE TABLE `meican_cilogon_auth` (
            `id` int(11) NOT NULL,
            `user_id` int(11) NOT NULL,
            `token` varchar(2000) NOT NULL,
            `expiration` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
                ");


                $this->execute("
        ALTER TABLE `meican_cilogon_auth`
        ADD PRIMARY KEY (`id`),
        ADD KEY `user_id` (`user_id`);
                ");
                $this->execute("
        ALTER TABLE `meican_cilogon_auth`
        MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
                ");
                $this->execute("
        ALTER TABLE `meican_cilogon_auth`
        ADD CONSTRAINT `meican_cilogon_auth_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `meican_user` (`id`);
                ");
    }

    public function down()
    {
        echo "m240326_200556_create_meican_cilogon_auth_table cannot be reverted.\n";

        return false;
    }
}