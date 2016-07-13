<?php

namespace Tests\AppBundle\Controller;

use AppBundle\Service\KeyGenerator;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;

class KeyGeneratorTest extends TestCase
{
    public function testGenerateOk()
    {
        $generator = new KeyGenerator(100000, 999999);

        $this->assertNotEmpty($key = $generator->generate());
        $this->assertNotEquals($key, $generator->generate());
    }
}
