<?php

namespace App\Repository;

use App\Entity\ReservationBook;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ReservationBookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ReservationBook::class);
    }

    // Add custom methods for querying the reservation-books table if needed
}
