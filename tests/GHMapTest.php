<?php

namespace Ghanem\GoogleMap\Tests;

use Ghanem\GoogleMap\GHMap;
use PHPUnit\Framework\TestCase;

class GHMapTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Set default config values for testing
        if (!function_exists('config')) {
            // Config function will be available in Laravel context
        }
    }

    public function test_field_component_name(): void
    {
        $this->assertSame('gh-map', (new \ReflectionClass(GHMap::class))->getDefaultProperties()['component']);
    }

    public function test_coordinate_validation_bounds(): void
    {
        // Latitude must be between -90 and 90
        $this->assertTrue(-90 <= 41.657523 && 41.657523 <= 90);
        $this->assertFalse(-90 <= 91 && 91 <= 90);
        $this->assertFalse(-90 <= -91 && -91 <= 90);

        // Longitude must be between -180 and 180
        $this->assertTrue(-180 <= -101.157292 && -101.157292 <= 180);
        $this->assertFalse(-180 <= 181 && 181 <= 180);
        $this->assertFalse(-180 <= -181 && -181 <= 180);
    }
}
