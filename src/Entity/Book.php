<?php

namespace App\Entity;

use App\Repository\BookRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BookRepository::class)]
class Book
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title;

    #[ORM\Column(type: Types::BIGINT)]
    private ?string $isbn;

    #[ORM\Column]
    private ?int $obliged;

    #[ORM\ManyToOne(targetEntity: Course::class, inversedBy: "books")]
    #[ORM\JoinColumn(name: "course_id", referencedColumnName: "id", nullable: false)]
    private ?Course $course = null;

    /**
     * @param string|null $title
     * @param string|null $isbn
     * @param int|null $obliged
     */
    public function __construct(?string $title, ?string $isbn, ?int $obliged)
    {
        $this->title = $title;
        $this->isbn = $isbn;
        $this->obliged = $obliged;
    }

    public function __toString(): string
    {
        return "book id:" . $this->id . " title:" . $this->title .  " isbn:" . $this->isbn .  " obliged:" . $this->obliged . " course:" .  $this->course;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getIsbn(): ?string
    {
        return $this->isbn;
    }

    public function setIsbn(string $isbn): static
    {
        $this->isbn = $isbn;

        return $this;
    }

    public function getObliged(): ?int
    {
        return $this->obliged;
    }

    public function setObliged(int $obliged): static
    {
        $this->obliged = $obliged;

        return $this;
    }

    public function getCourse(): ?Course
    {
        return $this->course;
    }

    public function setCourse(Course $course): static
    {
        $this->course = $course;
        return $this;
    }
}
