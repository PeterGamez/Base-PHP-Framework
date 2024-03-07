<?php

namespace System\Helper\Console;

use System\Helper\Database;

class Seeder
{
    public static $description = [
        "run" => "Run seeder",
        "make" => "Create new seeder",
    ];

    public function __construct()
    {
        $class = get_called_class();
        if ($class === 'Seeder') die("Can't run Seeder class directly.\n");

        $class::up();
    }

    public static function run(string $seeder = null): void
    {
        if (empty($seeder)) die("Please enter seeder name.");

        $file = __ROOT__ . "/database/seeders/$seeder.php";
        if (!file_exists($file)) die("Seeder $seeder not found.\n");

        require_once $file;

        echo "Seeder $seeder run successfully.\n";
    }

    public static function make(string $seeder = null): void
    {
        if (empty($seeder)) die("Please enter seeder name.");

        if (file_exists(__ROOT__ . "/database/seeders/$seeder.php")) die("Seeder $seeder already exists.\n");
        $content = <<<EOF
<?php

use System\Helper\Console\Seeder;
use System\Helper\Factory;

return new class extends Seeder
{
    public function up(): void
    {
        Factory::create('$seeder', 10, function () {
            return [
                'name' => Factory::fake()->name()
            ];
        });
    }
};


EOF;

        file_put_contents(__ROOT__ . "/database/seeders/$seeder.php", $content);

        echo "Seeder $seeder created successfully.\n";
    }
}
