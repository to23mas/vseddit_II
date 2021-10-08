<?php
declare(strict_types=1);
/**
 * @author Tomáš Míčka
 */

namespace App\Facade;

use Nettrine\ORM\EntityManagerDecorator;
use Tom\Application\Pals\Entity\Data\PalsEntity;
use Tom\Application\Pals\Repository\Data\PalsRepository;

class PalsFacade {

    protected EntityManagerDecorator $entityManager;

    protected PalsRepository $palsRepository;

    /**
     * @param EntityManagerDecorator $entityManager
     * @param PalsRepository $palsRepository
     */
    public function __construct(EntityManagerDecorator $entityManager) {
        $this->entityManager = $entityManager;
        $this->palsRepository = $entityManager->getRepository(PalsEntity::class);
    }

    public function getAll(): array {
        return $this->palsRepository->getAllAsArray();
    }

    public function getSingleResultAsArray(int $id): ?array {
        return $this->palsRepository->getOneAsArray($id);
    }

    public function delete(int $id):void {
        $pal = $this->palsRepository->findOneBy(['palId' => $id]);
        $this->entityManager->remove($pal);
        $this->entityManager->flush();

    }

    public function create(array $values) {
        $pal = new PalsEntity($values['firstName'], $values['lastName']);
        $this->entityManager->persist($pal);
        $this->entityManager->flush();
    }
}