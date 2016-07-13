<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="order_note")
 */
class OrderNote
{
    use Common\Identifiable;
    use Common\TimeTrackable;
    use Common\Stringable;

    /**
     * @var string
     *
     * @ORM\Column(name="body", type="string", length=1024, nullable=false)
     */
    protected $body;

    /**
     * @var Order
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Order", inversedBy="notes")
     * @ORM\JoinColumn(name="id_order", referencedColumnName="id")
     */
    protected $order;

    /**
     * @param string $body
     */
    public function __construct($body)
    {
        $this->body = $body;
    }

    /**
     * @param Order $order
     *
     * @return self
     */
    public function setOrder($order)
    {
        $this->order = $order;

        return $this;
    }

    /**
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * {@inheritdoc}
     */
    public function toString()
    {
        return sprintf('%s:{%s,`%s`,`%s`}', static::class, $this->id, $this->createdAt->format('Y-m-d H:i'), $this->body);
    }
}
