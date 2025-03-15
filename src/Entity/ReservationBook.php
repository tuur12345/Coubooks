<?php

namespace App\Entity;

use App\Repository\ReservationBookRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReservationBookRepository::class)]
class ReservationBook
{
    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: Reservation::class)]
    #[ORM\JoinColumn(name: "reservation_id", referencedColumnName: "id")]
    private ?Reservation $reservation = null;

    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: Book::class)]
    #[ORM\JoinColumn(name: "book_id", referencedColumnName: "id")]
    private ?Book $book = null;

    public function getReservation(): ?Reservation
    {
        return $this->reservation;
    }

    public function setReservation(?Reservation $reservation): self
    {
        $this->reservation = $reservation;
        return $this;
    }

    public function getBook(): ?Book
    {
        return $this->book;
    }

    public function setBook(?Book $book): self
    {
        $this->book = $book;
        return $this;
    }
}
