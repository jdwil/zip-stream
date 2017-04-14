<?php

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

use JDWil\ZipStream\Stream\ReadStream;

/**
 * Interface ZipStreamInterface
 * @author JD Williams <me@jdwilliams.xyz>
 */
interface ZipStreamInterface
{
    /**
     * @param string $filePath
     * @return ZipStreamInterface
     */
    public static function forFile(string $filePath): ZipStreamInterface;

    /**
     * @param string $name
     * @param string $data
     * @param Options|null $options
     * @return ZipStreamInterface
     */
    public function addFile(string $name, string $data, Options $options = null): ZipStreamInterface;

    /**
     * @param string $name
     * @param string $filePath
     * @param Options|null $options
     * @return ZipStreamInterface
     */
    public function addFileFromDisk(string $name, string $filePath, Options $options = null): ZipStreamInterface;

    /**
     * @param string $name
     * @param ReadStream $stream
     * @param Options|null $options
     * @return ZipStreamInterface
     */
    public function addFileFromStream(string $name, ReadStream $stream, Options $options = null): ZipStreamInterface;

    /**
     * @param string $name
     * @param Options|null $options
     * @return ZipStreamInterface
     */
    public function beginFile(string $name, Options $options = null): ZipStreamInterface;

    /**
     * @param string $data
     * @return ZipStreamInterface
     */
    public function addFilePart(string $data): ZipStreamInterface;

    /**
     * @return ZipStreamInterface
     */
    public function endFile(): ZipStreamInterface;

    public function close();
}