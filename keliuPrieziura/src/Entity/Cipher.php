<?php

namespace App\Entity;

use App\Repository\CipherRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CipherRepository::class)
 */
class Cipher
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $cipher;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity=RoadType::class, inversedBy="ciphers")
     */
    private $type;

    /**
     * @ORM\ManyToOne(targetEntity=Metric::class, inversedBy="ciphers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $metric;

    /**
     * @ORM\OneToMany(targetEntity=Job::class, mappedBy="cipher")
     */
    private $jobs;

    public function __construct()
    {
        $this->jobs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCipher(): ?string
    {
        return $this->cipher;
    }

    public function setCipher(string $cipher): self
    {
        $this->cipher = $cipher;

        return $this;
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

    public function getType(): ?RoadType
    {
        return $this->type;
    }

    public function setType(?RoadType $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getMetric(): ?Metric
    {
        return $this->metric;
    }

    public function setMetric(?Metric $metric): self
    {
        $this->metric = $metric;

        return $this;
    }

    /**
     * @return Collection|Job[]
     */
    public function getJobs(): Collection
    {
        return $this->jobs;
    }

    public function addJob(Job $job): self
    {
        if (!$this->jobs->contains($job)) {
            $this->jobs[] = $job;
            $job->setCipher($this);
        }

        return $this;
    }

    public function removeJob(Job $job): self
    {
        if ($this->jobs->removeElement($job)) {
            // set the owning side to null (unless already changed)
            if ($job->getCipher() === $this) {
                $job->setCipher(null);
            }
        }

        return $this;
    }

    public function getSelectName(): ?string
    {
        return $this->cipher  . ' ' . $this->name . ' (' . $this->metric->getName() . ')';
    }
}
