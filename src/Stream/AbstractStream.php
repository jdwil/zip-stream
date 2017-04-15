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
 * and is licensed under the MIT license.
 */

namespace JDWil\ZipStream\Stream;

/**
 * Class AbstractStream
 * @author JD Williams <me@jdwilliams.xyz>
 */
abstract class AbstractStream implements StreamInterface
{
    /**
     * @var string
     */
    protected $filePath;

    /**
     * @var resource
     */
    protected $handle;

    /**
     * @var string
     */
    protected $mode;

    /**
     * @return bool
     */
    public function isOpen(): bool
    {
        return is_resource($this->handle);
    }

    abstract public function open();

    public function close()
    {
        fclose($this->handle);
    }

    public function reset()
    {
        $this->close();
        $this->open();
    }

    /**
     * @return resource
     */
    public function getHandle()
    {
        return $this->handle;
    }
}
