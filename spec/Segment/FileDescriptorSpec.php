<?php

namespace spec\JDWil\ZipStream\Segment;

use JDWil\ZipStream\Segment\File;
use JDWil\ZipStream\Segment\FileDescriptor;
use JDWil\ZipStream\Stream\WriteStream;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class FileDescriptorSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith(new File(10, 'foo', 10, 10, 10));
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(FileDescriptor::class);
    }

    public function it_should_write_a_file_descriptor(WriteStream $stream)
    {
        $this->write($stream)->shouldEqual(16);
        $stream->write(Argument::any())->shouldHaveBeenCalled();
    }
}
