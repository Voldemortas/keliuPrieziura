<?php

namespace App\Entity;

use App\Repository\MetricRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MetricRepository::class)
 */
class Metric
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=5)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=Cipher::class, mappedBy="metric")
     */
    private $ciphers;

    public function __construct()
    {
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
            $cipher->setMetric($this);
        }

        return $this;
    }

    public function removeCipher(Cipher $cipher): self
    {
        if ($this->ciphers->removeElement($cipher)) {
            // set the owning side to null (unless already changed)
            if ($cipher->getMetric() === $this) {
                $cipher->setMetric(null);
            }
        }

        return $this;
    }
}
