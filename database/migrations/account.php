<?php

use System\Helper\Console\Migrate;
use System\Helper\Factory;
use System\Helper\Schema;

return new class extends Migrate
{
    public function up(): void
    {
        Schema::dropIfExists('account');

        Schema::create('account', function () {
            return "CREATE TABLE `account` (
                    `id` int(5) NOT NULL AUTO_INCREMENT,
                    `username` varchar(50) NOT NULL,
                    `password` varchar(100) NOT NULL,
                    `email` varchar(100) NOT NULL,
                    `role` enum('admin','user') NOT NULL DEFAULT 'user',
                    `create_at` timestamp NOT NULL DEFAULT current_timestamp(),
                    `create_by` int(5) NOT NULL,
                    `update_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
                    `update_by` int(5) NOT NULL,
                    `isTrash` enum('0','1') NOT NULL DEFAULT '0',
                    PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci";
        });

        Factory::create('account', function () {
            return [
                'username' => 'system',
                'password' => password_hash('system', PASSWORD_DEFAULT),
                'email' => 'system@example.com',
                'role' => 'admin',
                'create_by' => 1,
                'update_by' => 1,
            ];
        });
    }
};
