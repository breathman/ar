<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="contact_type")
 */
final class ContactType extends AbstractStatus
{
    const PHONE = 'phone';
    const EMAIL = 'email';
    const SKYPE = 'skype';

    const OTHER = 'other';
}
