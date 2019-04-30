<?php

declare(strict_types=1);

namespace App\Model\Link\Entity;

use App\Model\Link\ValueObject\Token;
use App\Model\Link\ValueObject\Url;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LinkRepository")
 */
final class Link
{
    /**
     * @var UuidInterface
     *
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true, name="uuid")
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidGenerator")
     *
     */
    private $uuid;

    /**
     * @var Url
     *
     * @ORM\Embedded(class="App\Model\Link\ValueObject\Url", columnPrefix=false)
     */
    private $url;

    /**
     * @var Token
     *
     * @ORM\Embedded(class="App\Model\Link\ValueObject\Token", columnPrefix=false)
     */
    private $token;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @var int
     *
     * @ORM\Column(type="bigint")
     */
    private $views;

    /**
     * @param Url $url
     * @param Token $token
     *
     * @throws Exception
     */
    public function __construct(
        Url $url,
        Token $token
    ) {
        $this->uuid = Uuid::uuid4();
        $this->url = $url;
        $this->token = $token;
        $this->createdAt = new DateTime();
        $this->views = 0;
    }

    /**
     * @return UuidInterface
     */
    public function getId(): UuidInterface
    {
        return $this->uuid;
    }

    /**
     * @return Url
     */
    public function getUrl(): Url
    {
        return $this->url;
    }

    /**
     * @return Token
     */
    public function getToken(): Token
    {
        return $this->token;
    }

    /**
     * @return DateTime
     */
    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    /**
     * @return int
     */
    public function getViews(): int
    {
        return $this->views;
    }
}
