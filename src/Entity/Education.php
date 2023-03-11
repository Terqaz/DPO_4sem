<?php

namespace App\Entity;

use App\Repository\EducationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Ignore;

#[ORM\Entity(repositoryClass: EducationRepository::class)]
class Education
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 64)]
    private ?string $type = null;

    // Название учебного учреждения
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $institution = null;

    // Название факультета
    #[ORM\Column(length: 150, nullable: true)]
    private ?string $faculty = null;

    // Специализация
    #[ORM\Column(length: 150, nullable: true)]
    private ?string $specialization = null;

    // Год окончания
    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    private ?int $graduationYear = null;

    #[ORM\ManyToOne(inversedBy: 'educations')]
    #[Ignore]
    private ?Summary $summary = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getInstitution(): ?string
    {
        return $this->institution;
    }

    public function setInstitution(?string $institution): self
    {
        $this->institution = $institution;

        return $this;
    }

    public function getFaculty(): ?string
    {
        return $this->faculty;
    }

    public function setFaculty(?string $faculty): self
    {
        $this->faculty = $faculty;

        return $this;
    }

    public function getSpecialization(): ?string
    {
        return $this->specialization;
    }

    public function setSpecialization(?string $specialization): self
    {
        $this->specialization = $specialization;

        return $this;
    }

    public function getGraduationYear(): ?int
    {
        return $this->graduationYear;
    }

    public function setGraduationYear(?int $graduationYear): self
    {
        $this->graduationYear = $graduationYear;

        return $this;
    }

    public function getSummary(): ?Summary
    {
        return $this->summary;
    }

    public function setSummary(?Summary $summary): self
    {
        $this->summary = $summary;

        return $this;
    }
}
