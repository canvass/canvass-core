<?php

namespace Tests\Canvass\Support;

use Canvass\Support\Str;
use Tests\TestCase;

class StrTest extends TestCase
{
    public function test_class_segment()
    {
        $this->assertEquals(
            'LocationSelect',
            Str::classSegment('location - select')
        );
    }

    public function test_slug()
    {
        $this->assertEquals(
            'testing-one-two-three',
            Str::slug('Testing--One Two!@#_Three')
        );
    }
}
