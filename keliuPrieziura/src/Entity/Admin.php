<?php

namespace App\Entity;

use App\Repository\AdminRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AdminRepository::class)
 */
class Admin
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="boolean")
     */
    private $toggled;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getToggled(): ?bool
    {
        return $this->toggled;
    }

    public function setToggled(bool $toggled): self
    {
        $this->toggled = $toggled;

        return $this;
    }
}
