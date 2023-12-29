<?php

namespace App\Helper\Console;

class Controller
{
    public static $description = [
        "make" => "Create new controller"
    ];

    final public static function make($controller)
    {
        if (file_exists(__ROOT__ . "/app/Models/$controller.php")) die("Controller $controller already exists.\n");
        $content = <<<EOF
<?php

namespace App\Models;

use Database\Model;

class $controller
{
}

EOF;

        file_put_contents(__ROOT__ . "/app/Controllers/$controller.php", $content);

        echo "Controller $controller created successfully.\n";
    }
}
