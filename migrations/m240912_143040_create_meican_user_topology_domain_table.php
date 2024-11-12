<?php

use yii\db\Migration;

class m240912_143040_create_meican_user_topology_domain_table extends Migration
{
    public function up()
    {
                $this->execute("
                CREATE TABLE `meican_user_topology_domain` (
                        `id` int(11) NOT NULL,
                        `user_id` int(11) NOT NULL,
                        `domain` text CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL
                      ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
                ");


                $this->execute("
                ALTER TABLE `meican_user_topology_domain`
                ADD PRIMARY KEY (`id`),
                ADD KEY `user_id_2` (`user_id`);
                ");
                $this->execute("
                ALTER TABLE `meican_user_topology_domain`
                MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
                ");
                $this->execute("
                ALTER TABLE `meican_user_topology_domain`
                ADD CONSTRAINT `meican_user_topology_domain_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `meican_user` (`id`);
                ");
    }

    public function down()
    {
        echo "m240912_143040_create_meican_user_topology_domain_table cannot be reverted.\n";

        return false;
    }
}
