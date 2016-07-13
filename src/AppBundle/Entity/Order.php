<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="`order`")
 */
class Order
{
    use Common\Identifiable;
    use Common\TimeTrackable;
    use Common\Stringable;

    /**
     * @var OrderStatus
     *
     * @ORM\Column(name="status", type="order_status", nullable=false)
     */
    protected $status;

    /**
     * @var string
     *
     * @ORM\Column(name="user_key", type="string", length=50, unique=true, nullable=false)
     */
    protected $userKey;

    /**
     * @var OrderNote
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\OrderNote", mappedBy="order", cascade={"persist"})
     * @ORM\OrderBy({"id"="DESC"})
     */
    protected $notes;

    /**
     * @var CarServiceOrder[]
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\CarServiceOrder", mappedBy="order", cascade={"persist"})
     */
    protected $carServiceOrders;

    /**
     * @var Contact[]
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Contact", cascade={"persist"})
     * @ORM\JoinTable(
     *     name="order_contact",
     *     joinColumns={@ORM\JoinColumn(name="id_order", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="id_contact", referencedColumnName="id", unique=true)}
     * )
     * @ORM\OrderBy({"id"="DESC"})
     */
    protected $contacts;

    /**
     * @var Car[]
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Car", mappedBy="order", cascade={"persist"})
     * @ORM\OrderBy({"id"="DESC"})
     */
    protected $cars;

    /**
     * @var Estimate[]
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Estimate", mappedBy="order", cascade={"persist"})
     * @ORM\OrderBy({"id"="DESC"})
     */
    protected $estimates;

    /**
     * @var File[]
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\File", cascade={"persist"}, fetch="EXTRA_LAZY")
     * @ORM\JoinTable(
     *     name="order_file",
     *     joinColumns={@ORM\JoinColumn(name="id_order", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="id_file", referencedColumnName="id", unique=true)}
     * )
     * @ORM\OrderBy({"id"="DESC"})
     */
    protected $files;

    /**
     * @param string $userKey
     */
    public function __construct($userKey)
    {
        $this->contacts  = new ArrayCollection();
        $this->estimates = new ArrayCollection();
        $this->notes     = new ArrayCollection();
        $this->files     = new ArrayCollection();

        $this->carServiceOrders = new ArrayCollection();

        $this->status  = OrderStatus::BLANK();
        $this->userKey = $userKey;
    }

    /**
     * @param CarService $carService
     *
     * @return bool
     */
    public function hasCarService(CarService $carService)
    {
        return (bool) $this->carServiceOrders
            ->matching(Criteria::create()->where(Criteria::expr()->eq('carService', $carService)))
            ->count();
    }

    /**
     * @param CarService $carService
     *
     * @return CarServiceOrder
     */
    public function addCarService(CarService $carService)
    {
        $this->carServiceOrders[] = ($carServiceOrder = new CarServiceOrder($carService, $this));

        return $carServiceOrder;
    }

    /**
     * @return CarService[]|Collection
     */
    public function getCarServices()
    {
        return $this->carServiceOrders->map(function(CarServiceOrder $carServiceOrder) {
            return $carServiceOrder->getCarService();
        });
    }

    /**
     * @return CarServiceOrder[]|Collection
     */
    public function getCarServiceOrders()
    {
        return $this->carServiceOrders;
    }

    /**
     * @return OrderNote[]|Collection
     */
    public function getNotes()
    {
        return $this->notes;
    }

    /**
     * @param OrderNote $note
     *
     * @return $this
     */
    public function addNote(OrderNote $note)
    {
        $this->notes[] = $note->setOrder($this);

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
     * @return $this
     */
    public function addContact(Contact $contact)
    {
        $this->contacts[] = $contact;

        return $this;
    }

    /**
     * @return Estimate[]|Collection
     */
    public function getEstimates()
    {
        return $this->estimates;
    }

    /**
     * @param Car      $car
     * @param Estimate $estimate
     *
     * @return $this
     */
    public function addEstimate(Car $car, Estimate $estimate)
    {
        $this->estimates[] = $estimate
            ->setOrder($this)
            ->setCar($car);

        return $this;
    }

    /**
     * @return Car[]|Collection
     */
    public function getCars()
    {
        return $this->cars;
    }

    /**
     * @param Car $car
     *
     * @return $this
     */
    public function addCar(Car $car)
    {
        $this->cars[] = $car->setOrder($this);

        return $this;
    }

    /**
     * @return File[]|Collection
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * @param File   $file
     * @param string $directory
     *
     * @return $this
     */
    public function addFile(File $file, $directory = null)
    {
        $this->files[] = $directory ? $file->setDirectory($directory) : $file;

        return $this;
    }

    /**
     * @param string $path
     * @param File[] $files
     *
     * @return $this
     */
    public function addDirectory($path, $files)
    {
        array_walk($files, function(File $file) use ($path) {
            $this->addFile($file, $path);
        });

        return $this;
    }

    /**
     * @return OrderStatus
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param OrderStatus $status
     *
     * @return bool
     */
    public function isStatus(OrderStatus $status)
    {
        return $status->equals($this->getStatus());
    }

    /**
     * @param OrderStatus[] $list
     *
     * @return bool
     */
    public function inStatus(array $list)
    {
        return (bool) count(array_filter($list, function(OrderStatus $status) {
            return $status->equals($this->status);
        }));
    }

    /**
     * @return string
     */
    public function getUserKey()
    {
        return $this->userKey;
    }

    /**
     * @return bool
     */
    public function canEdit()
    {
        return $this->inStatus([
            OrderStatus::BLANK(),
            OrderStatus::ESTIMATE(),
        ]);
    }

    /**
     * @return bool
     */
    public function canSubscribedOn()
    {
        return $this->inStatus([
            OrderStatus::ESTIMATE(),
        ]);
    }

    /**
     * @return $this
     */
    public function estimate()
    {
        $this->status = OrderStatus::ESTIMATE();

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function toString()
    {
        return sprintf('%s:{%s,`%s`,`%s`,`%s`}', static::class, $this->id, $this->status, $this->userKey, $this->createdAt->format('Y-m-d H:i'));
    }
}
