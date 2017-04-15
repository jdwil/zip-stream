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
 * and is licensed under the MIT license.
 */

namespace JDWil\ZipStream\Segment;


use JDWil\ZipStream\Stream\WriteStream;

/**
 * Class FileDescriptor
 * @author JD Williams <me@jdwilliams.xyz>
 */
class FileDescriptor
{
    const DATA_DESCRIPTOR_SIGNATURE = 0x08074b50;

    /**
     * @var File
     */
    private $file;

    /**
     * FileDescriptor constructor.
     * @param File $file
     */
    public function __construct(File $file)
    {
        $this->file = $file;
    }

    /**
     * @param WriteStream $stream
     * @return int
     * @throws \JDWil\ZipStream\Exception\StreamException
     */
    public function write(WriteStream $stream): int
    {
        $data = pack(
            'VVVV',
            self::DATA_DESCRIPTOR_SIGNATURE,
            $this->file->crc32,
            $this->file->compressedSize,
            $this->file->size
        );

        $stream->write($data);

        return strlen($data);
    }
}
