<?php

namespace App\Repository;

use App\Entity\Education;
use App\Entity\Summary;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Summary>
 *
 * @method Summary|null find($id, $lockMode = null, $lockVersion = null)
 * @method Summary|null findOneBy(array $criteria, array $orderBy = null)
 * @method Summary[]    findAll()
 * @method Summary[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SummaryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Summary::class);
    }

    public function save(Summary $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Summary $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Обновить резюме в БД
     * @param Education $education - старое резюме, проксированное Doctrine
     * @param Education $newEducation - новое резюме
     * @param bool $flush - обновить ли состояние БД
     * @return void
     * @throws \Doctrine\Persistence\Mapping\MappingException
     * @throws \ReflectionException
     */
    public function update(Summary $summary, Summary $newSummary, bool $flush = false): void
    {
        $metadata = $this->getEntityManager()->getMetadataFactory()->getMetadataFor($this->getClassName());

        $fieldNames = $metadata->getFieldNames();
        unset($fieldNames[0]);

        foreach ($fieldNames as $field) {
            $field = ucfirst($field);
            $setter = 'set' . $field;
            $getter = 'get' . $field;

            $summary->$setter($newSummary->$getter());
        }

        foreach ($summary->getEducations() as $education) {
            $summary->removeEducation($education);
        }

        foreach ($newSummary->getEducations() as $newEducation) {
            $educationRepository = $this->getEntityManager()->getRepository(Education::class);

            $education = $educationRepository->findOneBy(['id' => $newEducation->getId()]);
            if ($education !== null) {
                $educationRepository->update($education, $newEducation);
            } else {
                $summary->addEducation($newEducation);
                $this->getEntityManager()->persist($newEducation);
            }
        }

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
