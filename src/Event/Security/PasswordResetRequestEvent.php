<?php
declare(strict_types=1);

namespace App\Event\Security;

use App\Data\Security\PasswordResetRequestData;

/**
 * Class PasswordResetRequestEvent
 * @package App\Event\Security
 * @author bernard-ng <ngandubernard@gmail.com>
 */
class PasswordResetRequestEvent
{

    private PasswordResetRequestData $data;

    /**
     * PasswordResetRequestEvent constructor.
     * @param PasswordResetRequestData $data
     */
    public function __construct(PasswordResetRequestData $data)
    {
        $this->data = $data;
    }

    /**
     * @return PasswordResetRequestData
     */
    public function getData(): PasswordResetRequestData
    {
        return $this->data;
    }
}
