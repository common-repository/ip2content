<?php

namespace WMIP2C\Database\Migrations\Components;

use WMIP2C\Common\Services\SingletonTrait;

abstract class AbstractDatabaseMigrations
{
    use SingletonTrait;

    public function run(): void
    {
        foreach ($this->migrations() as &$migrationClass) {
            (new $migrationClass())->up();
        }
    }

    public function drop(): void
    {
        global $wpdb;

        $wpdb->query('SET FOREIGN_KEY_CHECKS=0;');

        foreach (array_reverse($this->migrations()) as $migrationClass) {
            (new $migrationClass())->down();
        }

        $wpdb->query('SET FOREIGN_KEY_CHECKS=1;');
    }

    abstract public function migrations(): array;
}