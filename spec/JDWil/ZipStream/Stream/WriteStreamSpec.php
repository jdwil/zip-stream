<?php

namespace spec\JDWil\ZipStream\Stream;

use JDWil\ZipStream\Filter\Crc32Filter;
use JDWil\ZipStream\Filter\SizeFilter;
use JDWil\ZipStream\Stream\WriteStream;
use JDWil\ZipStream\ZipStream;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class WriteStreamSpec extends ObjectBehavior
{
    public function let()
    {
        stream_filter_register(ZipStream::CRC32_FILTER, Crc32Filter::class);
        stream_filter_register(ZipStream::SIZE_FILTER, SizeFilter::class);
        $this->beConstructedThrough('forFile', ['php://memory', 'rw']);
        $this->open();
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(WriteStream::class);
    }

    public function it_can_write_data_to_a_stream()
    {
        $this->write('foo');
        $handle = $this->getHandle()->getWrappedObject();
        rewind($handle);
        $data = fread($handle, 8012);
        expect($data)->to->equal('foo');
    }

    public function it_can_write_calculate_crc32_and_track_the_length()
    {
        $this->addCompressionFilters();
        $this->write('foobar');
        $this->close();
        $this->getCrc32()->shouldEqual(2666930069);
        $this->getSize()->shouldEqual(8);
    }
}
