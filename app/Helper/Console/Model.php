<?php

namespace App\Helper\Console;

use App\Helper\Database;

class Model
{
    public static $description = [
        "make" => "Create new model",
        "clearTrash" => "Clear trash",
        "clearAllTrash" => "Clear all trash",
    ];

    final public static function make($table)
    {
        $model = self::parse($table);

        if (file_exists(__ROOT__ . "/app/Models/$table.php")) die("Model $table already exists.\n");
        $content = <<<EOF
<?php

namespace App\Models;

use Database\Model;

class $model extends Model
{
    public static \$table = '$table';
}

EOF;
        $db = new Database();
        $db->create($table);

        file_put_contents(__ROOT__ . "/app/Models/$table.php", $content);

        echo "Model $table created successfully.\n";
    }

    final public static function clearTrash($table)
    {
        $model = self::parse($table);

        $db = new Database();
        $db->clearTrash($model);

        echo "Trash cleared successfully.\n";
    }

    final public static function clearAllTrash()
    {
        $db = new Database();
        $tables = $db->getAllTables();

        foreach ($tables as $table) {
            $db->clearTrash($table);
        }

        echo "Trash cleared successfully.\n";
    }

    private static function parse($table)
    {
        $model = lcfirst($table); // change first character to lowercase
        $model = preg_replace('/(?<!^)[A-Z]/', '_$0', $model); // add underscore before uppercase
        $model = strtolower($model); // change uppercase to lowercase

        return $model;
    }
}
