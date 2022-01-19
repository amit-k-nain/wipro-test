<?php

namespace App\Entity;

use App\Repository\RouterDetailsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RouterDetailsRepository::class)
 */
class RouterDetails
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=18, unique=true)
     */
    private $sapid;

    /**
     * @ORM\Column(type="string", length=14, unique=true)
     */
    private $hostname;

    /**
     * @ORM\Column(type="integer", unique=true)
     */
    private $loopback;

    /**
     * @ORM\Column(type="string", length=17, unique=true)
     */
    private $mac_address;

    /**
     * @ORM\Column(type="boolean")
     */
    private $status;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSapid(): ?string
    {
        return $this->sapid;
    }

    public function setSapid(string $sapid): self
    {
        $this->sapid = $sapid;

        return $this;
    }

    public function getHostname(): ?string
    {
        return $this->hostname;
    }

    public function setHostname(string $hostname): self
    {
        $this->hostname = $hostname;

        return $this;
    }

    public function getLoopback(): ?int
    {
        return $this->loopback;
    }

    public function setLoopback(int $loopback): self
    {
        $this->loopback = $loopback;

        return $this;
    }

    public function getMacAddress(): ?string
    {
        return $this->mac_address;
    }

    public function setMacAddress(string $mac_address): self
    {
        $this->mac_address = $mac_address;

        return $this;
    }

    public function toArray()
    {
        return [
            'id' => $this->getId(),
            'sapid' => $this->getSapid(),
            'hostname' => $this->getHostname(),
            'loopback' => $this->getLoopback(),
            'mac_address' => $this->getMacAddress(),
            'status' => $this->getStatus()
        ];
    }

    public function getStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): self
    {
        $this->status = $status;

        return $this;
    }

}
