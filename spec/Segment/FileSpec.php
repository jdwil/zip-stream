<?php

namespace spec\JDWil\ZipStream\Segment;

use JDWil\ZipStream\Segment\File;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class FileSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith(1, 'foo', 2, 3, 4);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(File::class);
    }

    public function it_is_a_simple_dto()
    {
        $this->name->shouldEqual('foo');
        $this->offset->shouldEqual(1);
        $this->size->shouldEqual(2);
        $this->compressedSize->shouldEqual(3);
        $this->crc32->shouldEqual(4);
    }
}
