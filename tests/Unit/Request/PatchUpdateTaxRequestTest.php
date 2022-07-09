<?php

namespace App\Tests\Unit\Request;

use App\Request\PatchUpdateTaxRequest;
use PHPUnit\Framework\TestCase;

class PatchUpdateTaxRequestTest extends TestCase
{
    public function testPatchUpdateTaxRequest()
    {
        $request = new PatchUpdateTaxRequest();
        $request->setCurrency('USD');
        $request->setPercent(10);

        $this->assertEquals('USD', $request->getCurrency());
        $this->assertEquals(10, $request->getPercent());
    }
}
