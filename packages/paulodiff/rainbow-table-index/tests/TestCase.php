<?php

// https://github.com/austinheap/laravel-database-encryption/blob/master/tests/TestCase.php
namespace RainbowTableIndex\Tests;

class TestCase extends \Orchestra\Testbench\TestCase
{
  public function setUp(): void
  {
    parent::setUp();
    // additional setup
  }
/*
  protected function getPackageProviders($app)
  {
    return [
      BlogPackageServiceProvider::class,
    ];
  }
  */

  protected function getEnvironmentSetUp($app)
  {
    // perform environment setup
  }
}
