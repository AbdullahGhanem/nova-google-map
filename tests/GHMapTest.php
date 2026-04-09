<?php

namespace Ghanem\GoogleMap\Tests;

use Ghanem\GoogleMap\GHMap;
use PHPUnit\Framework\TestCase;

class GHMapTest extends TestCase
{
    public function test_field_component_name(): void
    {
        $this->assertSame('gh-map', (new \ReflectionClass(GHMap::class))->getDefaultProperties()['component']);
    }

    public function test_meta_keys_are_set_independently(): void
    {
        $reflection = new \ReflectionClass(GHMap::class);
        $instance = $reflection->newInstanceWithoutConstructor();
        $instance->meta = [];

        $instance->latitude(24.5);
        $this->assertSame(24.5, $instance->meta['latitude']);
        $this->assertArrayNotHasKey('latitude_field', $instance->meta);

        $instance->latitudeField('lat');
        $this->assertSame('lat', $instance->meta['latitude_field']);
        $this->assertSame(24.5, $instance->meta['latitude']);
    }

    public function test_hide_latitude_and_longitude(): void
    {
        $reflection = new \ReflectionClass(GHMap::class);
        $instance = $reflection->newInstanceWithoutConstructor();
        $instance->meta = [];

        $instance->hideLatitude();
        $instance->hideLongitude();

        $this->assertTrue($instance->meta['hide_latitude']);
        $this->assertTrue($instance->meta['hide_longitude']);
    }
}
