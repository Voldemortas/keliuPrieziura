<?php

namespace App\Entity;

use App\Repository\SectionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Mapping\ClassMetadata;

/**
 * @ORM\Entity(repositoryClass=SectionRepository::class)
 */
class Section
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Road::class, inversedBy="sections")
     * @ORM\JoinColumn(nullable=false)
     */
    private $road;

    /**
     * @ORM\Column(type="float")
     */
    private $start;

    /**
     * @ORM\OneToMany(targetEntity=Job::class, mappedBy="section")
     */
    private $jobs;

    /**
     * @ORM\Column(type="float")
     */
    private $finish;

    public function __construct()
    {
        $this->jobs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRoad(): ?Road
    {
        return $this->road;
    }

    public function setRoad(?Road $road): self
    {
        $this->road = $road;

        return $this;
    }

    public function getStart(): ?float
    {
        return $this->start;
    }

    public function setStart(float $start): self
    {
        $this->start = $start;

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

    public function getFinish(): ?float
    {
        return $this->finish;
    }

    public function setFinish(float $finish): self
    {
        $this->finish = $finish;

        return $this;
    }

    public function getSelectName(): ?string
    {
        return $this->start  . '–' . $this->finish . 'km';
    }

    public function getDistance(): ?float
    {
        $start = $this->start;
        $finish = $this->finish;
        return $finish - $start;
    }

    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('start', new Assert\GreaterThanOrEqual([
            'value' => 0,
            'message' => 'Turi būti daugiau arba lygu 0',
        ]));

        $metadata->addPropertyConstraint('finish', new Assert\GreaterThan([
            'value' => 0,
            'message' => 'Turi būti daugiau už 0',
        ]));

        $metadata->addPropertyConstraint('start', new Assert\LessThan([
            'propertyPath' => 'finish',
            'message' => 'Pradžia turi būt mažesnė už pabaigą',
        ]));
    }
}
