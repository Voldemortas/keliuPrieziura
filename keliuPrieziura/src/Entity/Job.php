<?php

namespace App\Entity;

use App\Repository\JobRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Mapping\ClassMetadata;

/**
 * @ORM\Entity(repositoryClass=JobRepository::class)
 */
class Job
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Section::class, inversedBy="jobs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $section;

    /**
     * @ORM\Column(type="float")
     */
    private $distance;

    /**
     * @ORM\ManyToOne(targetEntity=Cipher::class, inversedBy="jobs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $cipher;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSection(): ?Section
    {
        return $this->section;
    }

    public function setSection(?Section $section): self
    {
        $this->section = $section;

        return $this;
    }

    public function getDistance(): ?float
    {
        return $this->distance;
    }

    public function setDistance(float $distance): self
    {
        $this->distance = $distance;

        return $this;
    }

    public function getCipher(): ?Cipher
    {
        return $this->cipher;
    }

    public function setCipher(?Cipher $cipher): self
    {
        $this->cipher = $cipher;

        return $this;
    }

    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('distance', new Assert\GreaterThan([
            'value' => 0,
            'message' => 'Turi būti daugiau už 0',
        ]));
        $metadata->addPropertyConstraint('distance', new Assert\LessThanOrEqual([
            'propertyPath' => 'section.distance',
            'message' => 'Turi būti mažiau už visą atsumą arba jam lygu',
        ]));
    }
}
