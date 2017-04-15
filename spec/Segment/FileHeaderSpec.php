<?php

namespace spec\JDWil\ZipStream\Segment;

use JDWil\ZipStream\Options;
use JDWil\ZipStream\Segment\FileHeader;
use JDWil\ZipStream\Stream\WriteStream;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class FileHeaderSpec extends ObjectBehavior
{
    public function let()
    {
        $time = new \DateTime('01/01/2017');
        $this->beConstructedWith('foo', new Options(10, 10, 10, $time));
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(FileHeader::class);
    }

    public function it_should_write_a_file_header(WriteStream $stream)
    {
        $this->write($stream)->shouldEqual(33);
        $stream->write(Argument::any())->shouldHaveBeenCalledTimes(2);
    }
}
