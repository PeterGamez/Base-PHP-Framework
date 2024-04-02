<?php

namespace System\Helper\Console;

use System\Helper\Database;

class Model
{
    public static $description = [
        "make" => "Create new model",
        "clearTrash" => "Clear trash",
        "clearAllTrash" => "Clear all trash"
    ];

    final public static function make(string $model = null): void
    {
        if (empty($model)) {
            die("Please enter table name.");
        }

        $table = self::parse($model);

        if (file_exists(__ROOT__ . "/app/Models/$model.php")) {
            die("Model $model already exists.\n");
        }
        $content = <<<EOF
<?php

namespace App\Models;

use System\Database\Model;

class $model extends Model
{
    public static \$table = '$table';
}

EOF;
        file_put_contents(__ROOT__ . "/app/Models/$model.php", $content);

        echo "Model $model created successfully.\n";
    }

    final public static function clearTrash(string $model = null): void
    {
        if (config('database.trash.enabled') == false) {
            die("Trash is disabled.\n");
        }

        if (empty($model)) {
            die("Please enter table name.");
        }

        $table = self::parse($model);

        $db = new Database();
        $db->clearTrash($table);

        echo "Trash cleared successfully.\n";
    }

    final public static function clearAllTrash(): void
    {
        if (config('database.trash.enabled') == false) {
            die("Trash is disabled.\n");
        }

        $db = new Database();
        $tables = $db->getAllTables();

        foreach ($tables as $table) {
            $db->clearTrash($table);
        }

        echo "Trash cleared successfully.\n";
    }

    private static function parse($table): string
    {
        $model = lcfirst($table); /* change first character to lowercase */
        $model = preg_replace('/(?<!^)[A-Z]/', '_$0', $model); /* add underscore before uppercase */
        $model = strtolower($model); /* change uppercase to lowercase */

        return $model;
    }
}
