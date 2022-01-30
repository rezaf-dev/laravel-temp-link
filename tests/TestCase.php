<?php

namespace RezafDev\LaravelTempLink\Tests;


use RezafDev\LaravelTempLink\LaravelTempLinkServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
  public function setUp(): void
  {
    parent::setUp();
  }

  protected function getPackageProviders($app)
  {
    return [
            LaravelTempLinkServiceProvider::class
    ];
  }

  protected function getEnvironmentSetUp($app)
  {
    // perform environment setup
  }
}
