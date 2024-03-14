<?php

namespace System\Helper\Console;

use System\Helper\Database;

class Migrate
{
    public static $description = [
        "run" => "Run all migrate",
        "make" => "Create new migrate"
    ];

    public function __construct()
    {
        $class = get_called_class();
        if ($class === 'Migrate') die("Can't run Migrate class directly.\n");

        $class::up();
    }

    public static function run(): void
    {
        $migrations = scandir(__ROOT__ . "/database/migrations");
        $migrations = array_filter($migrations, function ($file) {
            return preg_match('/.php$/', $file);
        });
        $migrations = array_values($migrations);

        foreach ($migrations as $migration) {
            require_once __ROOT__ . "/database/migrations/$migration";
        }

        echo "Migrate run successfully.\n";
    }

    public static function make(string $model = null): void
    {
        if (empty($model)) die("Please enter migrate name.");

        if (file_exists(__ROOT__ . "/database/migrations/$model.php")) die("Migrate $model already exists.\n");
        $content = <<<EOF
<?php

use System\Helper\Console\Migrate;
use System\Helper\Schema;

return new class extends Migrate
{
    public function up(): void
    {
        Schema::dropIfExists('$model');

        Schema::create('$model', function () {
            return "CREATE TABLE `$model` (
                    `id` int(5) NOT NULL AUTO_INCREMENT,
                    `create_at` timestamp NOT NULL DEFAULT current_timestamp(),
                    `create_by` int(5) NOT NULL,
                    `update_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
                    `update_by` int(5) NOT NULL,
                    `isTrash` enum('0','1') NOT NULL DEFAULT '0',
                    PRIMARY KEY (`id`)
            );";
        });
    }
};

EOF;

        file_put_contents(__ROOT__ . "/database/migrations/$model.php", $content);

        echo "Migrate $model created successfully.\n";
    }
}
