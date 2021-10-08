<?php
declare(strict_types=1);

namespace Tom\Application\Pals\Repository\Data;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityRepository;
use Tom\Application\Pals\Entity\Data\PalsEntity;

/**
 * @author Tomáš Míčka
 */
class PalsRepository extends EntityRepository {

    public function getAllAsArray(): array {
        $q = $this->createQueryBuilder('e');

        return $q->getQuery()->getArrayResult();
    }

    public function getOneAsArray(int $id): ?array {
        $q = $this->createQueryBuilder('e')
        ->where('e.palId = :id')
        ->setParameter('id', $id, Types::INTEGER);

        return $q->getQuery()->getOneOrNullResult(AbstractQuery::HYDRATE_ARRAY);
    }

}