<?php

namespace WMIP2C\Database\Seeders\Components;

use WMIP2C\Common\Services\SingletonTrait;

abstract class AbstractDatabaseSeeders
{
    use SingletonTrait;

    public function run(): void
    {
        foreach ($this->seeders() as $seederName) {
            (new $seederName())->run();
        }
    }

    abstract protected function seeders(): array;
}