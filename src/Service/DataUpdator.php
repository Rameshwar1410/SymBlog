<?php
declare (strict_types = 1);

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;

/**
 * Used to update given data
 */
class DataUpdator
{
    /** @var EntityManagerInterface $entityManager An instance of EntityManagerInterface */
    private $entityManager;

    /**
     * Constructor to initialize variable
     *
     * @param EntityManagerInterface $entityManager An instance of EntityManagerInterface
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Data update
     */
    public function update(): void
    {
        $this->entityManager->flush();
    }
}
