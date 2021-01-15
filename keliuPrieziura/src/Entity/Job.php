<?php

namespace App\Entity;

use App\Repository\JobRepository;
use Doctrine\ORM\Mapping as ORM;

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
     * @ORM\ManyToOne(targetEntity=Cipher::class, inversedBy="jobs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $cipher;

    /**
     * @ORM\ManyToOne(targetEntity=RoadSection::class, inversedBy="jobs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $section;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getSection(): ?RoadSection
    {
        return $this->section;
    }

    public function setSection(?RoadSection $section): self
    {
        $this->section = $section;

        return $this;
    }
}
