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

namespace JDWil\ZipStream\Stream;

use JDWil\ZipStream\Exception\StreamException;
use JDWil\ZipStream\ZipStream;

/**
 * Class OutputStream
 * @author JD Williams <me@jdwilliams.xyz>
 */
class WriteStream extends AbstractStream
{
    /**
     * @var \stdClass
     */
    private $crc32Params;

    /**
     * @var \stdClass
     */
    private $sizeParams;

    /**
     * @var array
     */
    private $filters;

    private function __construct()
    {
        $this->filters = [];
    }

    /**
     * @param string $filePath
     * @param string $mode
     * @return WriteStream
     */
    public static function forFile(string $filePath, string $mode = 'ab+'): WriteStream
    {
        $ret = new WriteStream();
        $ret->filePath = $filePath;
        $ret->mode = $mode;

        return $ret;
    }

    /**
     * @throws StreamException
     */
    public function open()
    {
        if (!$this->handle = fopen($this->filePath, $this->mode)) {
            throw new StreamException('Could not open file for writing');
        }
    }


    /**
     * @param string $data
     * @throws StreamException
     */
    public function write(string $data)
    {
        if (!is_resource($this->handle)) {
            throw new StreamException('OutputStream::write called but stream is not open');
        }

        fwrite($this->handle, $data);
    }

    public function addCompressionFilters()
    {
        $this->crc32Params = new \stdClass();
        $this->filters[] = stream_filter_append(
            $this->handle,
            ZipStream::CRC32_FILTER,
            STREAM_FILTER_WRITE,
            $this->crc32Params
        );

        $this->filters[] = stream_filter_append(
            $this->handle,
            ZipStream::COMPRESSION_FILTER,
            STREAM_FILTER_WRITE
        );

        $this->sizeParams = new \stdClass();
        $this->filters[] = stream_filter_append(
            $this->handle,
            ZipStream::SIZE_FILTER,
            STREAM_FILTER_WRITE,
            $this->sizeParams
        );
    }

    public function removeCompressionFilters()
    {
        foreach (array_reverse($this->filters) as $filter) {
            stream_filter_remove($filter);
        }
        $this->filters = [];
    }

    /**
     * @return int
     */
    public function getCrc32(): int
    {
        return $this->crc32Params->crc32;
    }

    /**
     * @return int
     */
    public function getSize(): int
    {
        return $this->sizeParams->size;
    }
}