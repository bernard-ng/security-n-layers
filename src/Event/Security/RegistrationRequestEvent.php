<?php

declare(strict_types=1);

namespace App\Event\Security;

use App\Data\Security\RegistrationData;

/**
 * Class AccountRegisteredEvent
 * @package App\Event\Security
 * @author bernard-ng <ngandubernard@gmail.com>
 */
final class RegistrationRequestEvent
{

    private RegistrationData $data;

    /**
     * RegistrationRequestEvent constructor.
     * @param RegistrationData $data
     */
    public function __construct(RegistrationData $data)
    {
        $this->data = $data;
    }

    /**
     * @return RegistrationData
     */
    public function getData(): RegistrationData
    {
        return $this->data;
    }
}
