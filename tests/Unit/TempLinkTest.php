<?php

namespace RezafDev\LaravelTempLink\Tests\Unit;

use Illuminate\Support\Facades\Storage;
use RezafDev\LaravelTempLink\TempLink;
use RezafDev\LaravelTempLink\Tests\TestCase;

class TempLinkTest extends TestCase
{
    function test_generate_temp_link()
    {
        /** @var RezafDev\LaravelTempLink\TempLink $tempLink */
        $tempLink = $this->app->get(TempLink::class);
        file_put_contents(storage_path('logs/test.log'), 'test');
        $link = $tempLink->generateTempLink(storage_path('logs/test.log'));
        $temp_file_path = Storage::disk('public')->path(ltrim($link, '/storage/'));
        $this->assertFileExists($temp_file_path);
        $this->assertStringEqualsFile($temp_file_path, 'test');
    }

    function test_delete_expired_links()
    {
        /** @var RezafDev\LaravelTempLink\TempLink $tempLink */
        $tempLink = $this->app->get(TempLink::class);
        file_put_contents(storage_path('logs/test.log'), 'test');
        $link1 = $tempLink->generateTempLink(storage_path('logs/test.log'), -1);
        $link2 = $tempLink->generateTempLink(storage_path('logs/test.log'), 200);
        $link3 = $tempLink->generateTempLink(storage_path('logs/test.log'), -1000);
        $temp_file_path1 = Storage::disk('public')->path(ltrim($link1, '/storage/'));
        $temp_file_path2 = Storage::disk('public')->path(ltrim($link2, '/storage/'));
        $temp_file_path3 = Storage::disk('public')->path(ltrim($link3, '/storage/'));
        $tempLink->removeExpiredLinks();
        $this->assertFileDoesNotExist($temp_file_path1);
        $this->assertFileExists($temp_file_path2);
        $this->assertFileDoesNotExist($temp_file_path3);
    }
}