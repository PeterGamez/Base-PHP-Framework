<?php

namespace App\Helper\Console;

class Config
{
    public static $description = [
        "make" => "Create new config",
    ];
    
    final public static function make()
    {
        $file_in_dir = scandir(__ROOT__ . '/config'); // scan file in config
        $file_in_dir = array_filter($file_in_dir, function ($file) { // filter .php.example
            return preg_match('/.php.example$/', $file);
        });
        $file_in_dir = array_map(function ($file) { // remove .php.example
            return str_replace('.php.example', '', $file);
        }, $file_in_dir);
        $file_in_dir = array_values($file_in_dir); // reset index

        for ($i = 0; $i < count($file_in_dir); $i++) {
            $config = $file_in_dir[$i];
            if (!file_exists(__ROOT__ . "/config/$config.php")) {
                copy(__ROOT__ . "/config/$config.php.example", __ROOT__ . "/config/$config.php");
                echo "Config $config created successfully.\n";
            } else {
                echo "Config $config already exists.\n";
            }
        }
    }
}
