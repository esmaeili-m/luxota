<?php

namespace Modules\Category\tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use \Illuminate\Foundation\Testing\RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
    }
}
