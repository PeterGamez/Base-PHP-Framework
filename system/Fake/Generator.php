<?php

namespace System\Fake;

use Exception;

class Generator
{
    static $name = null;

    final public function username(): string
    {
        self::loadClass('en_US', 'Person');
        $Person = 'System\Fake\Provider\en_US\Person';

        $names = [
            ...$Person::$firstNameMale,
            ...$Person::$firstNameFemale,
        ];

        $name = $names[array_rand($names)];
        self::$name = strtolower($name);

        return self::$name;
    }

    final public function email(): string
    {
        if (self::$name) {
            $name = self::$name;
            unset(self::$name);
            return $name . '@example.com';
        } else {
            self::loadClass('en_US', 'Person');
            $Person = 'System\Fake\Provider\en_US\Person';

            $names = [
                ...$Person::$firstNameMale,
                ...$Person::$firstNameFemale,
            ];

            $name = $names[array_rand($names)];
            return strtolower($name) . '@example.com';
        }
    }

    /**
     * Generate a random name (English)
     */
    final public function name(): string
    {
        self::loadClass('en_US', 'Person');
        $Person = 'System\Fake\Provider\en_US\Person';

        $names = [
            ...$Person::$firstNameMale,
            ...$Person::$firstNameFemale,
        ];

        $name = $names[array_rand($names)];

        return self::$name;
    }

    final public function firstName($lang = 'en_US'): string
    {
        self::loadClass($lang, 'Person');
        $Person = 'System\Fake\Provider\\' . $lang . '\Person';

        $names = [
            ...$Person::$firstNameMale,
            ...$Person::$firstNameFemale,
        ];

        $name = $names[array_rand($names)];
        return $name;
    }

    final public function lastName($lang = 'en_US'): string
    {
        self::loadClass($lang, 'Person');
        $Person = 'System\Fake\Provider\\' . $lang . '\Person';

        $names = $Person::$lastName;

        $name = $names[array_rand($names)];
        return $name;
    }

    final public function fullName($lang = 'en_US'): string
    {
        self::loadClass($lang, 'Person');
        $Person = 'System\Fake\Provider\\' . $lang . '\Person';

        $firstNames = [
            ...$Person::$firstNameMale,
            ...$Person::$firstNameFemale,
        ];
        $lastNames = $Person::$lastName;

        $name = $firstNames[array_rand($firstNames)] . ' ' . $lastNames[array_rand($lastNames)];
        return $name;
    }

    /**
     * Generate a random phone number (Thailand)
     */
    final public function phoneNumber(): string
    {
        $phone = ['6', '8', '9'];
        $number = '0' . $phone[array_rand($phone)] . rand(10000000, 99999999);
        return $number;
    }

    private static function loadClass($lang, $class): void
    {
        // Check File
        if (!file_exists(__DIR__ . "/Provider/$lang/$class.php")) {
            throw new Exception("Language $lang not supported");
        }

        require_once __DIR__ . "/Provider/$lang/$class.php";

        if (!class_exists("System\Fake\Provider\\$lang\\$class")) {
            throw new Exception("Class $class not found in $lang");
        }
    }
}
