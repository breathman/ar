<?php

namespace AppBundle\Type;

use Doctrine\ORM\Mapping as ORM;
use ReflectionClass;
use InvalidArgumentException;

abstract class Enum
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @return ReflectionClass
     */
    protected static function getClass()
    {
        return new ReflectionClass(static::class);
    }

    /**
     * @return array
     */
    public static function all()
    {
        return array_values(self::getClass()->getConstants());
    }

    /**
     * @param mixed   $name
     * @param mixed[] $arguments
     *
     * @return static
     *
     * @throws InvalidArgumentException
     */
    public static function __callStatic($name, array $arguments)
    {
        $class = self::getClass();
        if (empty($id = $class->getConstant($name))) {
            throw new InvalidArgumentException(sprintf('Не найдена константа `%s` в `%s`', $name, $class->getName()));
        }

        return new static($id);
    }

    /**
     * @param string $id
     *
     * @throws InvalidArgumentException
     */
    public function __construct($id)
    {
        if (! in_array($id, self::all(), true)) {
            throw new InvalidArgumentException(sprintf('Не найдена константа `%s` в `%s`', $id, static::class));
        }

        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param Enum $other
     *
     * @return bool
     */
    public function equals(Enum $other)
    {
        if ((get_class($other) === static::class) or is_subclass_of($other, static::class)) {
            return ($other->getId() === $this->id);
        }

        return false;
    }
}
