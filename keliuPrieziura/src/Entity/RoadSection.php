<?php

namespace App\Entity;

use App\Repository\RoadSectionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RoadSectionRepository::class)
 */
class RoadSection
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=4)
     */
    private $roadNumber;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="float")
     */
    private $sectionStart;

    /**
     * @ORM\Column(type="float")
     */
    private $sectionFinish;

    /**
     * @ORM\Column(type="integer")
     */
    private $level;

    /**
     * @ORM\Column(type="string", length=25)
     */
    private $type;

    /**
     * @ORM\OneToMany(targetEntity=Job::class, mappedBy="section")
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

    public function getRoadNumber(): ?string
    {
        return $this->roadNumber;
    }

    public function setRoadNumber(string $roadNumber): self
    {
        $this->roadNumber = $roadNumber;

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

    public function getSectionStart(): ?float
    {
        return $this->sectionStart;
    }

    public function setSectionStart(float $sectionStart): self
    {
        $this->sectionStart = $sectionStart;

        return $this;
    }

    public function getSectionFinish(): ?float
    {
        return $this->sectionFinish;
    }

    public function setSectionFinish(float $sectionFinish): self
    {
        $this->sectionFinish = $sectionFinish;

        return $this;
    }

    public function getLevel(): ?int
    {
        return $this->level;
    }

    public function setLevel(int $level): self
    {
        $this->level = $level;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

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
            $job->setSection($this);
        }

        return $this;
    }

    public function removeJob(Job $job): self
    {
        if ($this->jobs->removeElement($job)) {
            // set the owning side to null (unless already changed)
            if ($job->getSection() === $this) {
                $job->setSection(null);
            }
        }

        return $this;
    }


    public function getSelectName(): ?string
    {
        return $this->roadNumber . ' (' . $this->name . ' ' . $this->sectionStart . '—' . $this->sectionFinish . 'km)';
    }
}
