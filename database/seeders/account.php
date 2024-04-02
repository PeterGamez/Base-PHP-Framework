<?php

use System\Helper\Console\Seeder;
use System\Helper\Factory;

return new class extends Seeder {
    public function up(): void
    {
        Factory::count(5);

        Factory::create('Account', function () {
            return [
                'username' => Factory::fake()->username(),
                'email' => Factory::fake()->email(),
                'password' => password_hash('password', PASSWORD_DEFAULT),
                'create_by' => 1,
                'update_by' => 1,
            ];
        });
    }
};
