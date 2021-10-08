<?php
declare(strict_types=1);

namespace Tom\Application\Pals\Service;

use Tom\Application\Pals\Entity\Data\Pals;

use Doctrine\ORM\EntityRepository;

use Nettrine\ORM\EntityManagerDecorator;

class PalsService {

    protected EntityManagerDecorator $entityManager;
    protected EntityRepository $repository;

    public function __construct(EntityManagerDecorator $entityManagerDecorator)
    {
        $this->entityManager = $entityManagerDecorator;
        $this->repository = $entityManagerDecorator->getRepository(Pals::class);
    }

    public function saveToDB(array $data): void {


        $pal = new Pals($data[0], $data[1]);

        $this->entityManager->persist($pal);
        $this->entityManager->flush();
    }

    public function getAllAsArray(): array {
        $pals = $this->repository->findAll();

        $final = [];

        foreach($pals as $pal) {
            $final []= $pal->getArray();
        }
        return $final;

    }

    public function deleteOne(int $id): void {
        $pal = $this->repository->findOneBy(['palId' => $id]);
        $this->entityManager->remove($pal);
        $this->entityManager->flush();
    }

    public function getOne(int $id) {
        $pal = $this->repository->findOneBy(['palId' => $id]);
        return $pal->getArray();
    }

    public function updateOne(int $id, string $fname, string $lname): void{

        $pal = $this->repository->findOneBy(['palId' => $id]);
        $pal->setFirstName($fname);
        $pal->setLastName($lname);

        $this->entityManager->persist($pal);
        $this->entityManager->flush();
    }

}