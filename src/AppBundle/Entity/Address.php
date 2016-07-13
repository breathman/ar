<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="address")
 */
class Address
{
    use Common\Identifiable;
    use Common\Stringable;

    /**
     * @var string
     *
     * @ORM\Column(name="region", type="string", length=250, nullable=false)
     */
    protected $region;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=250, nullable=false)
     */
    protected $city;

    /**
     * @var string
     *
     * @ORM\Column(name="street", type="string", length=250, nullable=false)
     */
    protected $street;

    /**
     * @var string
     *
     * @ORM\Column(name="house", type="string", length=1024, nullable=true)
     */
    protected $house;

    /**
     * @param string $region
     * @param string $city
     * @param string $street
     * @param string $house
     */
    public function __construct($region, $city, $street, $house = null)
    {
        $this->region = $region;
        $this->city   = $city;
        $this->street = $street;
        $this->house  = $house;
    }

    /**
     * @return string
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @return string
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * @return string
     */
    public function getHouse()
    {
        return $this->house;
    }

    /**
     * {@inheritdoc}
     */
    public function toString()
    {
        return sprintf('%s:{%s,`%s`,`%s`,`%s`,`%s`,`%s`}', static::class, $this->id, $this->region, $this->city, $this->street, $this->house);
    }
}
