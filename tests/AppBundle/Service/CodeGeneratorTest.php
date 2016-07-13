<?php

namespace Tests\AppBundle\Controller;

use AppBundle\Service\CodeGenerator;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;

class CodeGeneratorTest extends TestCase
{
    public function testGenerateOk()
    {
        $generator = new CodeGenerator('QRCODE,H', 5);

        $this->assertNotEmpty($generator->generate('ABCDE'));
    }
}
