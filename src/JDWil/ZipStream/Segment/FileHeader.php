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

namespace JDWil\ZipStream\Segment;


use JDWil\ZipStream\Options;
use JDWil\ZipStream\Stream\WriteStream;
use JDWil\ZipStream\Util\DOSTime;

/**
 * Class FileHeader
 * @author JD Williams <me@jdwilliams.xyz>
 */
class FileHeader
{
    const HEADER_SIGNATURE = 0x04034b50;
    const VERSION = 0x000A;
    const COMPRESSION_METHOD = 0x08; // 8 = deflate, 0 = store

    /**
     * @var mixed
     */
    private $name;

    /**
     * @var Options
     */
    private $options;

    /**
     * FileHeader constructor.
     * @param string $name
     * @param Options $options
     */
    public function __construct(string $name, Options $options)
    {
        $this->name = preg_replace('/^\\/+/', '', $name);
        $this->options = $options;
    }

    /**
     * @param WriteStream $stream
     * @return int Length of data written
     */
    public function write(WriteStream $stream): int
    {
        $data = pack(
            'VvvvVVVVvv',
            self::HEADER_SIGNATURE,
            self::VERSION,
            (0 === $this->options->getCrc32()) ? 0x08 : 0,
            self::COMPRESSION_METHOD,
            DOSTime::fromDateTime($this->options->getTime()),
            $this->options->getCrc32(),
            $this->options->getCompressedSize(),
            $this->options->getSize(),
            strlen($this->name),
            0
        );

        $stream->write($data);
        $stream->write($this->name);

        return strlen($data) + strlen($this->name);
    }
}