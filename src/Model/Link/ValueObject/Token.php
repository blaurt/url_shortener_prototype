<?php

declare(strict_types=1);

namespace App\Model\Link\ValueObject;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Embeddable()
 */
final class Token
{
    /**
     * @ORM\Column(type="string", length=100)
     */
    private $token;

    public function __construct()
    {
        $this->token = base_convert(rand(1, 10000000), 10, 36);
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->token;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->token;
    }
}
