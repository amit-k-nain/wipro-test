<?php

namespace App\Entity;

use App\Repository\ApiTokenRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ApiTokenRepository::class)
 */
class ApiToken
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $token;

    /**
     * @ORM\Column(type="datetime")
     */
    private $expire_at;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $client_ip;

    public function __construct($client_ip)
    {
        $this->token = bin2hex(random_bytes(60));
        $this->expire_at = new \DateTime('+1 hour');
        $this->client_ip = $client_ip;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(string $token): self
    {
        $this->token = $token;

        return $this;
    }

    public function getExpireAt(): ?\DateTimeInterface
    {
        return $this->expire_at;
    }

    public function setExpireAt(\DateTimeInterface $expire_at): self
    {
        $this->expire_at = $expire_at;

        return $this;
    }

    public function isExpired(): bool
    {
        return $this->getExpireAt() <= new \DateTime();
    }

    public function getClientIp(): ?string
    {
        return $this->client_ip;
    }

    public function setClientIp(string $client_ip): self
    {
        $this->client_ip = $client_ip;

        return $this;
    }
}
