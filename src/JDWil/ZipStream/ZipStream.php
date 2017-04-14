<?php
declare(strict_types=1);

/*
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the MIT license. For more information, see
 * <http://www.doctrine-project.org>.
 */

namespace JDWil\ZipStream;

use JDWil\ZipStream\Exception\UnmetDependenciesException;
use JDWil\ZipStream\Filter\Crc32Filter;
use JDWil\ZipStream\Filter\SizeFilter;
use JDWil\ZipStream\Segment\CentralDirectoryFileHeader;
use JDWil\ZipStream\Segment\EndOfCentralDirectoryRecord;
use JDWil\ZipStream\Segment\File;
use JDWil\ZipStream\Segment\FileDescriptor;
use JDWil\ZipStream\Segment\FileHeader;
use JDWil\ZipStream\Stream\ReadStream;
use JDWil\ZipStream\Stream\WriteStream;

/**
 * Class ZipStream
 * @author JD Williams <me@jdwilliams.xyz>
 */
class ZipStream implements ZipStreamInterface
{
    const CRC32_FILTER = 'crc32';
    const SIZE_FILTER = 'data.size';
    const COMPRESSION_FILTER = 'zlib.deflate';

    /**
     * @var WriteStream
     */
    private $outputStream;

    /**
     * @var int
     */
    private $offset;

    /**
     * @var int
     */
    private $cdrStart;
    /**
     * @var int
     */
    private $cdrLength;

    /**
     * @var File[]
     */
    private $files;

    /**
     * ZipStream constructor.
     * @throws UnmetDependenciesException
     */
    private function __construct()
    {
        if (!function_exists('gzcompress')) {
            throw new UnmetDependenciesException('zlib is required to use this library.');
        }

        $this->offset = $this->cdrLength = $this->cdrStart = 0;
        $this->files = [];
        stream_filter_register(self::CRC32_FILTER, Crc32Filter::class);
        stream_filter_register(self::SIZE_FILTER, SizeFilter::class);
    }

    /**
     * @param string $filePath
     * @return ZipStreamInterface
     */
    public static function forFile(string $filePath): ZipStreamInterface
    {
        $ret = new ZipStream();
        $ret->outputStream = WriteStream::forFile($filePath);
        $ret->outputStream->open();

        return $ret;
    }

    /**
     * @param string $name
     * @param string $data
     * @param Options|null $options
     * @return ZipStreamInterface
     */
    public function addFile(string $name, string $data, Options $options = null): ZipStreamInterface
    {
        $options = $options ?? new Options();

        $this->beginFile($name, $options);
        $this->addFilePart($data);
        $this->endFile();

        return $this;
    }

    /**
     * @param string $name
     * @param string $filePath
     * @param Options|null $options
     * @return ZipStreamInterface
     */
    public function addFileFromDisk(string $name, string $filePath, Options $options = null): ZipStreamInterface
    {
        $options = $options ?? new Options();

        $stream = ReadStream::forFile($filePath);
        $this->addFileFromStream($name, $stream, $options);
        $stream->close();

        return $this;
    }

    /**
     * @param string $name
     * @param ReadStream $stream
     * @param Options|null $options
     * @return ZipStreamInterface
     */
    public function addFileFromStream(string $name, ReadStream $stream, Options $options = null): ZipStreamInterface
    {
        $options = $options ?? new Options();

        if (!$stream->isOpen()) {
            $stream->open();
        }

        $this->beginFile($name, $options);
        $stream->copyToStream($this->outputStream);
        $file = end($this->files);
        $file->size += $stream->position();
        $this->endFile();

        return $this;
    }

    /**
     * @param string $name
     * @param Options|null $options
     * @return ZipStreamInterface
     */
    public function beginFile(string $name, Options $options = null): ZipStreamInterface
    {
        $options = $options ?? new Options();

        $file = new File(
            $this->offset,
            $name,
            $options->getSize(),
            $options->getCompressedSize(),
            $options->getCrc32()
        );
        $this->files[] = $file;

        $header = new FileHeader($name, $options);
        $this->offset += $header->write($this->outputStream);

        $this->outputStream->addCompressionFilters();

        return $this;
    }

    /**
     * @param string $data
     * @return ZipStreamInterface
     */
    public function addFilePart(string $data): ZipStreamInterface
    {
        $file = end($this->files);
        $file->size += strlen($data);
        $this->outputStream->write($data);

        return $this;
    }

    /**
     * @return ZipStreamInterface
     */
    public function endFile(): ZipStreamInterface
    {
        $this->outputStream->reset();

        $file = end($this->files);
        $file->crc32 = $this->outputStream->getCrc32();
        $file->compressedSize = $this->outputStream->getSize();
        $this->offset += $this->outputStream->getSize();

        $fileDescriptor = new FileDescriptor($file);
        $this->offset += $fileDescriptor->write($this->outputStream);

        return $this;
    }

    public function close()
    {
        $this->cdrStart = $this->offset;

        foreach ($this->files as $file) {
            $cdoh = new CentralDirectoryFileHeader($file);
            $length = $cdoh->write($this->outputStream);
            $this->offset += $length;
            $this->cdrLength += $length;
        }

        $eocd = new EndOfCentralDirectoryRecord();
        $this->offset += $eocd->write($this->outputStream, count($this->files), $this->cdrStart, $this->cdrLength);

        $this->outputStream->close();
    }
}