<?php

namespace App\Support;

/**
 * Resolves StyleX style keys to compiled class-name hashes.
 *
 * StyleX compiles `stylex.create()` entries in resources/js/stylex.js into
 * deterministic atomic classes. scripts/generate-stylex-map.mjs dumps the
 * key => class-list map into app/Support/stylex-map.php; this class reads it
 * so Blade templates can reference semantic keys instead of raw hashes.
 */
final class Stylex
{
    /** @var array<string, string>|null */
    private static ?array $map = null;

    /**
     * Join the compiled classes for the given style keys, plus any literal
     * classes passed along.
     *
     * Accepts style keys, raw class names (any string not present in the map),
     * and nested arrays; `false`, `null` and empty strings are skipped so
     * conditional classes can be written inline. Duplicates are collapsed.
     *
     * @param  array<int|string, mixed>|string|bool|null  ...$args
     */
    public static function cls(string|array|bool|null ...$args): string
    {
        $map = self::map();
        $seen = [];
        $classes = [];

        foreach (self::flatten($args) as $arg) {
            if (! is_string($arg) || $arg === '') {
                continue;
            }

            $argument = $map[$arg] ?? $arg;

            foreach (explode(' ', $argument) as $class) {
                if ($class !== '' && ! isset($seen[$class])) {
                    $seen[$class] = true;
                    $classes[] = $class;
                }
            }
        }

        return implode(' ', $classes);
    }

    /**
     * Determine whether the given style key exists in the compiled map.
     */
    public static function has(string $key): bool
    {
        return isset(self::map()[$key]);
    }

    /** @return array<string, string> */
    public static function map(): array
    {
        if (self::$map !== null) {
            return self::$map;
        }

        $path = __DIR__.'/stylex-map.php';

        self::$map = is_file($path) ? require $path : [];

        return self::$map;
    }

    /**
     * Yield every argument flattened into a single list.
     *
     * @param  iterable<mixed>  $args
     */
    private static function flatten(iterable $args): iterable
    {
        foreach ($args as $arg) {
            if (is_array($arg)) {
                yield from self::flatten($arg);
            } else {
                yield $arg;
            }
        }
    }
}
