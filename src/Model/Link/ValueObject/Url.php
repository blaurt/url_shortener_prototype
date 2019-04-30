<?php

declare(strict_types=1);

namespace App\Model\Link\ValueObject;

use App\Exception\InvalidUrlException;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Embeddable()
 */
final class Url
{
    /**
     * @ORM\Column(type="string")
     */
    private $url;

    public function __construct(string $url)
    {
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            throw new InvalidUrlException(sprintf('%s - not a valid url', $url));
        }

        $this->url = rtrim($url, '/');
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->url;
    }
}
