<?php

namespace spec\JDWil\ZipStream\Stream;

use JDWil\ZipStream\Stream\ReadStream;
use JDWil\ZipStream\Stream\WriteStream;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ReadStreamSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedThrough('forFile', ['php://memory', 'rw+']);
        $this->open();
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(ReadStream::class);
    }

    public function it_can_copy_data_to_a_stream(WriteStream $stream)
    {
        $handle = $this->getHandle()->getWrappedObject();
        fwrite($handle, 'foo');
        rewind($handle);
        $this->copyToStream($stream);
        $stream->write('foo')->shouldHaveBeenCalled();
    }
}
