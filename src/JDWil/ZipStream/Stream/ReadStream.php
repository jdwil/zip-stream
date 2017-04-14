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

/**
 * Class ReadStream
 * @author JD Williams <me@jdwilliams.xyz>
 */
class ReadStream extends AbstractStream
{
    private function __construct() {}

    /**
     * @param string $filePath
     * @return ReadStream
     */
    public static function forFile(string $filePath): ReadStream
    {
        $ret = new ReadStream();
        $ret->filePath = $filePath;

        return $ret;
    }

    public function open(): void
    {
        if (!$this->handle = fopen($this->filePath, 'rb')) {
            throw new StreamException('Could not open file for reading');
        }
    }

    /**
     * @param WriteStream $stream
     */
    public function copyToStream(WriteStream $stream)
    {
        while (!feof($this->handle)) {
            $stream->write(fread($this->handle, 8192));
        }
    }

    /**
     * @return int
     */
    public function position(): int
    {
        return ftell($this->handle);
    }
}