<?php

namespace System\Helper\Console;

use ReflectionMethod;

class Module
{
    public static $description = [
        "list" => "List all modules",
    ];

    public static function list(): void
    {
        $Console = scandir(__ROOT__ . '/system/Helper/Console');
        $Console = array_filter($Console, function ($file) {
            return preg_match('/.php$/', $file);
        });
        $Console = array_map(function ($file) {
            return str_replace('.php', '', $file);
        }, $Console);
        $Console = array_values($Console);

        $message = [];
        $message = [...$message, YELLOW . "Available commands:\n" . WHITE];

        foreach ($Console as $command) {
            $Class = "System\\Helper\\Console\\$command";
            $command = strtolower($command);
            $methods = get_class_methods($Class);

            $message = [...$message, "  $command\n"];
            foreach ($methods as $method) {
                $args = new ReflectionMethod($Class, $method);
                if ($method == '__construct') {
                    continue;
                }

                $text = GREEN . "    $command:$method";

                foreach ($args->getParameters() as $arg) {
                    $text .= " <" . $arg->getName() . ">";
                }

                $message = [...$message, $text];

                $description = isset($Class::$description[$method]) ? $Class::$description[$method] : null;
                if ($description) {
                    for ($i = 0; $i < 40 - strlen($text); $i++) {
                        $message = [...$message, " "];
                    }
                    $message = [...$message, WHITE . $Class::$description[$method] . "\n"];
                } else {
                    $message = [...$message, "\n" . WHITE];
                }
            }
        }
        echo implode("", $message);
    }
}
