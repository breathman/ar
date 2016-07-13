<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="file")
 */
class File
{
    use Common\Identifiable;
    use Common\TimeTrackable;
    use Common\Stringable;

    /**
     * @var string
     *
     * @ORM\Column(name="user_key", type="string", length=50, nullable=false)
     */
    protected $key;

    /**
     * @var int
     *
     * @ORM\Column(name="crc", type="integer", nullable=true)
     */
    protected $crc;

    /**
     * @var int
     *
     * @ORM\Column(name="size", type="integer", nullable=false)
     */
    protected $size;

    /**
     * @var string
     *
     * @ORM\Column(name="mime", type="string", length=50, nullable=false)
     */
    protected $mime;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=250, nullable=true)
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(name="note", type="string", length=1024, nullable=true)
     */
    protected $note;

    /**
     * @var string
     *
     * @ORM\Column(name="body", type="blob", nullable=false)
     */
    protected $body;

    /**
     * @param string $body
     * @param string $size
     * @param string $mime
     * @param string $name
     * @param string $note
     */
    public function __construct($body, $size, $mime, $name = null, $note = null)
    {
        $this->key  = md5(uniqid());
        $this->crc  = crc32($body);
        $this->size = $size;
        $this->mime = $mime;
        $this->name = $name;
        $this->note = $note;
        $this->body = $body;
    }

    /**
     * @param string $directory
     *
     * @return $this
     */
    public function setDirectory($directory)
    {
        $this->name = sprintf('/%s/%s', trim($directory, '\\/'), trim($this->name, '\\/'));

        return $this;
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @return string
     */
    public function getMime()
    {
        return $this->mime;
    }

    /**
     * @param bool $basename
     *
     * @return string
     */
    public function getName($basename = false)
    {
        return $basename ? basename($this->name) : $this->name;
    }

    /**
     * @return int
     */
    public function getCrc()
    {
        return $this->crc;
    }

    /**
     * @return int
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * @return string
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * @return string
     */
    public function getBody()
    {
        return is_resource($this->body) ? stream_get_contents($this->body) : $this->body;
    }

    /**
     * @return string
     */
    public function getBodyEncoded()
    {
        return ($body = $this->getBody()) ? base64_encode($body) : null;
    }

    /**
     * {@inheritdoc}
     */
    public function toString()
    {
        return sprintf('%s:{%s,`%s`,`%s`,`%s`}', static::class, $this->id, $this->mime, $this->size, $this->name);
    }
}
