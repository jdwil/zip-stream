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


/**
 * Class Options
 * @author JD Williams <me@jdwilliams.xyz>
 */
class Options
{
    /**
     * @var string
     */
    private $crc32;

    /**
     * @var int
     */
    private $compressedSize;

    /**
     * @var int
     */
    private $size;

    /**
     * @var \DateTime
     */
    private $time;

    /**
     * Options constructor.
     * @param int $crc32
     * @param int $compressedSize
     * @param int $size
     * @param \DateTime|null $time
     */
    public function __construct(
        int $crc32 = 0,
        int $compressedSize = 0,
        int $size = 0,
        \DateTime $time = null
    ) {
        $this->crc32 = $crc32;
        $this->compressedSize = $compressedSize;
        $this->size = $size;
        $this->time = $time ?? new \DateTime();
    }

    /**
     * @return int
     */
    public function getCrc32(): int
    {
        return $this->crc32;
    }

    /**
     * @return int
     */
    public function getCompressedSize(): int
    {
        return $this->compressedSize;
    }

    /**
     * @return int
     */
    public function getSize(): int
    {
        return $this->size;
    }

    /**
     * @return \DateTime
     */
    public function getTime(): \DateTime
    {
        return $this->time;
    }
}