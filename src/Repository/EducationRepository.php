<?php

namespace App\Repository;

use App\Entity\Education;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Education>
 *
 * @method Education|null find($id, $lockMode = null, $lockVersion = null)
 * @method Education|null findOneBy(array $criteria, array $orderBy = null)
 * @method Education[]    findAll()
 * @method Education[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EducationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Education::class);
    }

    public function save(Education $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Education $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Обновить образование в БД
     * @param Education $education - старое образование, проксированное Doctrine
     * @param Education $newEducation - новое образование
     * @param bool $flush - обновить ли состояние БД
     * @return void
     * @throws \Doctrine\Persistence\Mapping\MappingException
     * @throws \ReflectionException
     */
    public function update(Education $education, Education $newEducation, bool $flush = false): void
    {
        $metadata = $this->getEntityManager()->getMetadataFactory()->getMetadataFor($this->getClassName());

        $fieldNames = $metadata->getFieldNames();
        unset($fieldNames[0]);

        foreach ($fieldNames as $field) {
            $field = ucfirst($field);
            $setter = 'set' . $field;
            $getter = 'get' . $field;

            $education->$setter($newEducation->$getter());
        }

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
