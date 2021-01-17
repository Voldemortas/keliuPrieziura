<?php

namespace App\Entity;

use App\Repository\RoadTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RoadTypeRepository::class)
 */
class RoadType
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=25)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=Road::class, mappedBy="type", orphanRemoval=true)
     */
    private $roads;

    /**
     * @ORM\OneToMany(targetEntity=Cipher::class, mappedBy="type")
     */
    private $ciphers;

    public function __construct()
    {
        $this->roads = new ArrayCollection();
        $this->ciphers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|Road[]
     */
    public function getRoads(): Collection
    {
        return $this->roads;
    }

    public function addRoad(Road $road): self
    {
        if (!$this->roads->contains($road)) {
            $this->roads[] = $road;
            $road->setType($this);
        }

        return $this;
    }

    public function removeRoad(Road $road): self
    {
        if ($this->roads->removeElement($road)) {
            // set the owning side to null (unless already changed)
            if ($road->getType() === $this) {
                $road->setType(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Cipher[]
     */
    public function getCiphers(): Collection
    {
        return $this->ciphers;
    }

    public function addCipher(Cipher $cipher): self
    {
        if (!$this->ciphers->contains($cipher)) {
            $this->ciphers[] = $cipher;
            $cipher->setType($this);
        }

        return $this;
    }

    public function removeCipher(Cipher $cipher): self
    {
        if ($this->ciphers->removeElement($cipher)) {
            // set the owning side to null (unless already changed)
            if ($cipher->getType() === $this) {
                $cipher->setType(null);
            }
        }

        return $this;
    }
}
