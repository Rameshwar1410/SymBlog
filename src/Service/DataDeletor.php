<?php
declare (strict_types = 1);

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;

/**
 * Used to delete given data
 */
class DataDeletor
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
     * Delete Data
     * 
     * @param object $data An instance of dynamic give class for delete data
     */
    public function remove($data): void
    {
        $this->entityManager->remove($data);
        $this->entityManager->flush();
    }
}
