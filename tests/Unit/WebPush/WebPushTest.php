<?php

declare(strict_types=1);

namespace Tests\SpearDevs\SyliusPushNotificationsPlugin\Unit\WebPush;

use PHPUnit\Framework\TestCase;
use SpearDevs\SyliusPushNotificationsPlugin\WebPush\WebPush;

final class WebPushTest extends TestCase
{
    public function testGetTitle()
    {
        //Given
        $title = 'Test Title';
        $content = 'Test Content';

        //When
        $webPush = new WebPush($title, $content);
        $result = $webPush->getTitle();

        //Then
        $this->assertEquals($title, $result);
    }

    public function testGetContent(): void
    {
        //Given
        $title = 'Test Title';
        $content = 'Test Content';

        //When
        $webPush = new WebPush($title, $content);
        $result = $webPush->getContent();

        //Then
        $this->assertEquals($content, $result);
    }
}
