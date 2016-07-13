<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="carservice")
 */
class CarService
{
    use Common\Identifiable;
    use Common\TimeTrackable;
    use Common\Stringable;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=250, nullable=false)
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(name="ticker", type="string", length=50, nullable=false)
     */
    protected $ticker;

    /**
     * @var Company
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Company")
     * @ORM\JoinColumn(name="id_company", referencedColumnName="id")
     */
    protected $company;

    /**
     * @var Address
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Address")
     * @ORM\JoinColumn(name="id_address", referencedColumnName="id")
     * @ORM\OrderBy({"id"="DESC"})
     */
    protected $address;

    /**
     * @var Contact[]
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Contact", cascade={"persist"})
     * @ORM\JoinTable(
     *     name="carservice_contact",
     *     joinColumns={@ORM\JoinColumn(name="id_carservice", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="id_contact", referencedColumnName="id", unique=true)}
     * )
     * @ORM\OrderBy({"id"="DESC"})
     */
    protected $contacts;

    /**
     * @param string $ticker
     * @param string $name
     */
    public function __construct($ticker, $name)
    {
        $this->ticker   = $ticker;
        $this->name     = $name;

        $this->contacts = new ArrayCollection();
    }

    /**
     * @param Order $order
     *
     * @return bool
     */
    public function canSubscribe(Order $order)
    {
        return ! $order->hasCarService($this);
    }

    /**
     * @param Order $order
     *
     * @return CarServiceOrder
     */
    public function subscribe(Order $order)
    {
        return $order->addCarService($this);
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
    public function getTicker()
    {
        return $this->ticker;
    }

    /**
     * @return Address
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param Address $address
     *
     * @return CarService
     */
    public function addAddress(Address $address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * @return Contact[]|Collection
     */
    public function getContacts()
    {
        return $this->contacts;
    }

    /**
     * @param Contact $contact
     *
     * @return CarService
     */
    public function addContact(Contact $contact)
    {
        $this->contacts[] = $contact;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function toString()
    {
        return sprintf('%s:{%s,`%s`,`%s`}', static::class, $this->id, $this->ticker, $this->name);
    }
}
