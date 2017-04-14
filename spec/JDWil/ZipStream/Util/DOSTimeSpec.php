<?php

namespace spec\JDWil\ZipStream\Util;

use JDWil\ZipStream\Util\DOSTime;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class DOSTimeSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(DOSTime::class);
    }

    public function it_can_generate_dos_time()
    {
        $time = new \DateTime('01/01/2017');
        $this::fromDateTime($time)->shouldEqual(1243676672);
    }
}
