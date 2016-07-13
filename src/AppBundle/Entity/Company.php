<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="company")
 */
class Company
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
     * @var Address
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Address", cascade={"persist"})
     * @ORM\JoinTable(
     *     name="company_address",
     *     joinColumns={@ORM\JoinColumn(name="id_company", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="id_address", referencedColumnName="id", unique=true)}
     * )
     */
    protected $addresses;

    /**
     * @var Contact[]
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Contact", cascade={"persist"})
     * @ORM\JoinTable(
     *     name="company_contact",
     *     joinColumns={@ORM\JoinColumn(name="id_company", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="id_contact", referencedColumnName="id", unique=true)}
     * )
     */
    protected $contacts;

    /**
     * @param string $name
     */
    public function __construct($name)
    {
        $this->contacts  = new ArrayCollection();
        $this->addresses = new ArrayCollection();

        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return CarService
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Address
     */
    public function getAddress()
    {
        return $this->addresses;
    }

    /**
     * @param Address $address
     *
     * @return Company
     */
    public function addAddress(Address $address)
    {
        $this->addresses[] = $address;

        return $this;
    }

    /**
     * @return Contact[]
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
        return sprintf('%s:{%s,`%s`,`%s`}', static::class, $this->id, $this->name, $this->createdAt->format('Y-m-d H:i'));
    }
}
