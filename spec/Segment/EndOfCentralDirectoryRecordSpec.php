<?php

namespace spec\JDWil\ZipStream\Segment;

use JDWil\ZipStream\Segment\EndOfCentralDirectoryRecord;
use JDWil\ZipStream\Stream\WriteStream;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class EndOfCentralDirectoryRecordSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(EndOfCentralDirectoryRecord::class);
    }

    public function it_should_write_the_EOCD_record(WriteStream $stream)
    {
        $this->write($stream, 5, 10, 10)->shouldEqual(22);
        $stream->write(Argument::any())->shouldHaveBeenCalled();
    }
}
