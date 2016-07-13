<?php

namespace AppBundle\Dto;

use Symfony\Component\Validator\Constraints as Assert;
use Closure;
use SplFileInfo;

class OrderEdit
{
    /**
     * @var string
     *
     * @Assert\Type(type="string")
     * @Assert\Length(min=2, max=50)
     */
    protected $key;

    /**
     * @var int[]
     *
     * @Assert\Type(type="array")
     * @Assert\All(@Assert\Type(type="numeric", message="Работы должны быть заданы идентификаторами"))
     */
    protected $works;

    /**
     * @var string
     *
     * @Assert\Type(type="string")
     * @Assert\Length(min=2, max=50)
     */
    protected $carNumber;

    /**
     * @var string
     *
     * @Assert\Type(type="string")
     * @Assert\Length(min=2, max=250)
     */
    protected $carBrand;

    /**
     * @var string
     *
     * @Assert\Type(type="string")
     * @Assert\Length(min=2, max=250)
     */
    protected $carModel;

    /**
     * @var string
     *
     * @Assert\Type(type="string")
     * @Assert\Length(min=2, max=50)
     */
    protected $phone;

    /**
     * @var string
     *
     * @Assert\Email()
     * @Assert\Length(min=2, max=50)
     */
    protected $email;

    /**
     * @var string
     *
     * @Assert\Type(type="string")
     * @Assert\Length(min=2, max=50)
     */
    protected $name;

    /**
     * @var string
     *
     * @Assert\Type(type="string")
     * @Assert\Length(max=1024)
     */
    protected $note;

    /**
     * @var SplFileInfo[]
     *
     * @Assert\All(@Assert\File(maxSize="2M", mimeTypes={"image/*"}))
     */
    protected $files;

    /**
     * @param string $key
     * @param int[]  $works
     * @param string $carNumber
     * @param string $carBrand
     * @param string $carModel
     * @param string $name
     * @param string $phone
     * @param string $email
     * @param SplFileInfo[] $files
     * @param string $note
     */
    public function __construct(
        $key,
        $works,
        $carNumber,
        $carBrand,
        $carModel,
        $name,
        $phone,
        $email,
        $files,
        $note
    ) {
        $this->key       = $key;
        $this->works     = $works;
        $this->carNumber = $carNumber;
        $this->carBrand  = $carBrand;
        $this->carModel  = $carModel;
        $this->phone     = $phone;
        $this->email     = $email;
        $this->name      = $name;
        $this->note      = $note;
        $this->files     = $files;
    }


    /**
     * @return bool
     */
    public function hasCar()
    {
        return ($this->carNumber or $this->carBrand or $this->carModel);
    }

    /**
     * @return bool
     */
    public function hasWorks()
    {
        return (is_array($this->works) and count($this->works));
    }

    /**
     * @param int $limit
     *
     * @return bool
     */
    public function isWorksOverLimit($limit)
    {
        return (is_array($this->works) and (count($this->works) > $limit));
    }

    /**
     * @return bool
     */
    public function hasFiles()
    {
        return (is_array($this->files) and count($this->getFiles()));
    }

    /**
     * @param int $limit
     *
     * @return bool
     */
    public function isFilesOverLimit($limit)
    {
        return (is_array($this->files) and (count($this->getFiles()) > $limit));
    }

    /**
     * @param Closure(SplFileInfo, string, string) $callback
     *
     * @return array
     */
    public function mapFiles(Closure $callback)
    {
        return array_map(function(SplFileInfo $file) use ($callback) {
            return call_user_func($callback, $file, null, uniqid('autoname-'));
        },
            $this->getFiles()
        );
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @return int[]
     */
    public function getWorks()
    {
        return $this->works;
    }

    /**
     * @param Closure $callback
     *
     * @return array
     */
    public function mapWorks(Closure $callback)
    {
        return array_map($callback, $this->works);
    }

    /**
     * @return string
     */
    public function getCarNumber()
    {
        return $this->carNumber;
    }

    /**
     * @return string
     */
    public function getCarBrand()
    {
        return $this->carBrand;
    }

    /**
     * @param string $carBrand
     */
    public function setCarBrand($carBrand)
    {
        $this->carBrand = $carBrand;
    }

    /**
     * @return string
     */
    public function getCarModel()
    {
        return $this->carModel;
    }

    /**
     * @param string $carModel
     */
    public function setCarModel($carModel)
    {
        $this->carModel = $carModel;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * @return SplFileInfo[]
     */
    public function getFiles()
    {
        return (array) $this->files;
    }

    /**
     * @return bool
     */
    public function isNew()
    {
        return ! (bool) $this->key;
    }
}
