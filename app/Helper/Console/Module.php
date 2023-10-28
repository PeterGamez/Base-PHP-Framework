<?php

namespace App\Helper\Console;

use ReflectionMethod;

class Module
{
    public static $description = [
        "list" => "List all modules",
    ];

    public static function list(): void
    {
        $Console = scandir(__ROOT__ . '/app/Helper/Console');
        $Console = array_filter($Console, function ($file) {
            return preg_match('/.php$/', $file);
        });
        $Console = array_map(function ($file) {
            return str_replace('.php', '', $file);
        }, $Console);
        $Console = array_values($Console);

        echo YELLOW . "Available commands:\n" . WHITE;

        foreach ($Console as $command) {
            $Class = "App\\Helper\\Console\\$command";
            $command = strtolower($command);
            $methods = get_class_methods($Class);

            echo "  $command\n";
            foreach ($methods as $method) {
                $args = new ReflectionMethod($Class, $method);
                if ($method == '__construct') continue;

                $text = GREEN . "    $command:$method";

                foreach ($args->getParameters() as $arg) {
                    $text .= " <" . $arg->getName() . ">";
                }

                echo $text;

                $description = isset($Class::$description[$method]) ? $Class::$description[$method] : null;
                if ($description) {
                    for ($i = 0; $i < 40 - strlen($text); $i++) {
                        echo " ";
                    }
                    echo WHITE . $Class::$description[$method] . "\n";
                } else {
                    echo "\n" . WHITE;
                }
            }
        }
    }
}
