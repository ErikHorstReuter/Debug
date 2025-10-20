<?php declare(strict_types=1);

namespace Erik\Debug\Debug;

use JetBrains\PhpStorm\NoReturn;


class Debug
{
    static function dump(...$params): void
    {
        echo '<pre>';
        foreach ($params as $param) {
            $type = gettype($param);
            if ($type === 'object') {
                $type = get_class($param);
            } else if ($type === 'NULL') {
                $type = 'null';
            } else if ($type === 'boolean') {
                $type = $param ? 'true' : 'false';
            } else if ($type === 'integer') {
                $type = (string)$param;
            } else if ($type === 'double') {
                $type = (string)$param;
            } else if ($type === 'array') {
                $type = 'Array[' . count($param) . ']';
            } else if ($type === 'string') {
                $length = strlen($param);
                $type = "String($length)";
            }

            echo "<h6>$type</h6>";
            print_r($param);
            echo '<hr>';
        }
        echo '</pre>';
    }

    #[NoReturn]
    static function dd(...$params): void
    {
        self::dump(...$params);
        die;
    }

    #[NoReturn]
    static function ddd(...$params): void
    {
        self::debug();
        self::dd(...$params);
    }

    static function debug(): void
    {
        echo '<pre>';
        foreach (self::backtrace() as $item) {

            [
                'file' => $file,
                'line' => $line,
                'function' => $function,
                'class' => $class,
                'type' => $type,
                'args' => $args
            ] = $item;

            $argsStr = implode(', ', array_map(function ($arg) {
                if (is_array($arg)) {
                    return 'Array[' . count($arg) . ']';
                } elseif (is_object($arg)) {
                    return 'Object(' . get_class($arg) . ')';
                } elseif (is_null($arg)) {
                    return 'null';
                } elseif (is_bool($arg)) {
                    return $arg ? 'true' : 'false';
                } else {
                    return (string)$arg;
                }

            }, $args));

            $step = "{$file}:{$line} {$class}{$type}{$function}($argsStr)";
            print_r($step . PHP_EOL);

        }
        echo '</pre>';
    }

    static function backtrace(): array
    {
        return debug_backtrace();
    }

    #[NoReturn]
    static function marker(string|int|null $markerName = null): void
    {
        self::dd($markerName ?? 'BINGO');
    }
}
