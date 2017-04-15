<?php

namespace spec\JDWil\ZipStream\Segment;

use JDWil\ZipStream\Segment\CentralDirectoryFileHeader;
use JDWil\ZipStream\Segment\File;
use JDWil\ZipStream\Stream\WriteStream;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class CentralDirectoryFileHeaderSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith(new File(10, 'foo', 10, 10, 10));
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(CentralDirectoryFileHeader::class);
    }

    public function it_should_write_the_CDFH(WriteStream $stream)
    {
        $this->write($stream)->shouldEqual(49);
        //$stream->write('UEsBAgoACgAAAAgA5ZiOSgoAAAAKAAAACgAAAAMAAAAAAAAAAAAgAAAACgAAAA==')->shouldHaveBeenCalled();
        $stream->write(Argument::any())->shouldHaveBeenCalledTimes(2);
    }
}
