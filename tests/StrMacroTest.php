<?php
namespace Laravel\Jetstream\Tests;

use Illuminate\Support\Str;

class StrMacroTest extends OrchestraTestCase
{
    public function test_names_can_be_anonymized()
    {
        $this->assertTrue(Str::anonymizeName('Taylor Otwell') === 'T O');
    }
}