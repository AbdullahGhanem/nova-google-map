<?php

namespace Laravel\Nova;

class Nova
{
    public static function serving(callable $callback): void {}
    public static function script(string $name, string $path): void {}
    public static function style(string $name, string $path): void {}
    public static function provideToScript(array $data): void {}
}
