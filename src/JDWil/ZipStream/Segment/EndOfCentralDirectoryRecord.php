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

use JDWil\ZipStream\Stream\WriteStream;

/**
 * Class EndOfCentralDirectoryRecord
 * @author JD Williams <me@jdwilliams.xyz>
 */
class EndOfCentralDirectoryRecord
{
    const EOCD_SIGNATURE = 0x406054b50;
    const DISK = 0x00;

    /**
     * @param WriteStream $stream
     * @param int $numFiles
     * @param int $cdrStart
     * @param int $cdrLength
     * @return int
     */
    public function write(WriteStream $stream, int $numFiles, int $cdrStart, int $cdrLength): int
    {
        $data = pack(
            'VvvvvVVv',
            self::EOCD_SIGNATURE,
            self::DISK,
            self::DISK,
            $numFiles,
            $numFiles,
            $cdrLength,
            $cdrStart,
            0
        );

        $stream->write($data);

        return strlen($data);
    }
}