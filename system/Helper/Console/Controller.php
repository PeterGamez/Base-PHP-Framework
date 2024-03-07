<?php

namespace System\Helper\Console;

class Controller
{
    public static $description = [
        "make" => "Create new controller"
    ];

    final public static function make(string $controller = null): void
    {
        if (empty($controller)) die("Please enter controller name.");

        if (file_exists(__ROOT__ . "/app/Controllers/$controller.php")) die("Controller $controller already exists.\n");
        $content = <<<EOF
<?php

namespace App\Controllers;

use System\Controller\Controller;
use System\Router\Request;

class $controller extends Controller
{
    public static function index(Request \$request)
    {
    }
}

EOF;

        file_put_contents(__ROOT__ . "/app/Controllers/$controller.php", $content);

        echo "Controller $controller created successfully.\n";
    }
}
