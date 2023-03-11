<?php

namespace App\Entity;

use App\Repository\SummaryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: SummaryRepository::class)]
class Summary
{
    public const NEW = 'new';
    public const INTERVIEW_SCHEDULED = 'interviewScheduled';
    public const ACCEPTED = 'accepted';
    public const REJECTED = 'rejected';

    public const STATUSES = [
        self::NEW,
        self::INTERVIEW_SCHEDULED,
        self::ACCEPTED,
        self::REJECTED,
    ];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 32)]
    #[Assert\Choice(self::STATUSES, message: 'Unknown status', groups: ['status'])]
    private ?string $status = null;

    #[ORM\Column(length: 255)]
    private ?string $profession = null;

    // Название города, возможно, с учетом области и региона
    #[ORM\Column(length: 80)]
    private ?string $city = null;

    // URI для загрузки аватарки в резюме
    #[ORM\Column(length: 512, nullable: true)]
    private ?string $avatarUrl = null;

    #[ORM\Column(length: 80)]
    private ?string $lastName = null;

    #[ORM\Column(length: 80)]
    private ?string $firstName = null;

    // Отчество
    #[ORM\Column(length: 80, nullable: true)]
    private ?string $patronymic = null;

    #[ORM\Column(length: 10, unique: true)]
    private ?string $phone = null;

    #[ORM\Column(length: 128, nullable: true)]
    private ?string $email = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $birthday = null;

    #[ORM\OneToMany(mappedBy: 'summary', targetEntity: Education::class)]
    private Collection $educations;

    // Желаемая зарплата
    #[ORM\Column(nullable: true)]
    private ?int $desiredSalary = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $keySkills = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $about = null;

    public function __construct(?int $id, ?string $status, ?string $profession, ?string $city, ?string $avatarUrl, ?string $lastName, ?string $firstName, ?string $patronymic, ?string $phone, ?string $email, ?\DateTimeInterface $birthday, ?int $desiredSalary, ?string $keySkills, ?string $about)
    {
        $this->id = $id;
        $this->status = $status;
        $this->profession = $profession;
        $this->city = $city;
        $this->avatarUrl = $avatarUrl;
        $this->lastName = $lastName;
        $this->firstName = $firstName;
        $this->patronymic = $patronymic;
        $this->phone = $phone;
        $this->email = $email;
        $this->birthday = $birthday;
        $this->educations = new ArrayCollection();
        $this->desiredSalary = $desiredSalary;
        $this->keySkills = $keySkills;
        $this->about = $about;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getProfession(): ?string
    {
        return $this->profession;
    }

    public function setProfession(string $profession): self
    {
        $this->profession = $profession;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getAvatarUrl(): ?string
    {
        return $this->avatarUrl;
    }

    public function setAvatarUrl(?string $avatarUrl): self
    {
        $this->avatarUrl = $avatarUrl;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getPatronymic(): ?string
    {
        return $this->patronymic;
    }

    public function setPatronymic(?string $patronymic): self
    {
        $this->patronymic = $patronymic;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getBirthday(): ?\DateTimeInterface
    {
        return $this->birthday;
    }

    public function setBirthday(\DateTimeInterface $birthday): self
    {
        $this->birthday = $birthday;

        return $this;
    }

    public function getDesiredSalary(): ?int
    {
        return $this->desiredSalary;
    }

    public function setDesiredSalary(?int $desiredSalary): self
    {
        $this->desiredSalary = $desiredSalary;

        return $this;
    }

    public function getKeySkills(): ?string
    {
        return $this->keySkills;
    }

    public function setKeySkills(?string $keySkills): self
    {
        $this->keySkills = $keySkills;

        return $this;
    }

    public function getAbout(): ?string
    {
        return $this->about;
    }

    public function setAbout(?string $about): self
    {
        $this->about = $about;

        return $this;
    }

    /**
     * @return Collection<int, Education>
     */
    public function getEducations(): Collection
    {
        return $this->educations;
    }

    public function addEducation(Education $education): self
    {
        if (!$this->educations->contains($education)) {
            $this->educations->add($education);
            $education->setSummary($this);
        }

        return $this;
    }

    public function removeEducation(Education $education): self
    {
        if ($this->educations->removeElement($education)) {
            // set the owning side to null (unless already changed)
            if ($education->getSummary() === $this) {
                $education->setSummary(null);
            }
        }

        return $this;
    }
}
