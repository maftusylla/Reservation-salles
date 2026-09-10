<?php

declare(strict_types=1);

namespace Tests\Integration;

use PHPUnit\Framework\TestCase;

abstract class IntegrationTestCase extends TestCase
{
    protected function setUp(): void
    {
        $demarrerEloquent = require dirname(__DIR__, 2) . '/config/eloquent.php';
        $demarrerEloquent();
    }
}