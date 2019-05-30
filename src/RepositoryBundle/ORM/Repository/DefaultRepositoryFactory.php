<?php
/**
 * @category    tigerspike
 * @date        12/05/2019
 * @author      Michał Bolka <michal.bolka@gmail.com>
 */
namespace RepositoryBundle\ORM\Repository;

use Doctrine\Common\Persistence\ObjectRepository;
use RepositoryBundle\ORM\Mapping\ClassMetadataInterface;
use RepositoryBundle\ORM\PimcoreEntityManagerInterface;
use RepositoryBundle\ORM\PimcoreEntityRepository;

/**
 * Class DefaultRepositoryFactory
 * @package RepositoryBundle\Repository
 */
class DefaultRepositoryFactory implements RepositoryFactoryInterface
{
    /**
     * The list of EntityRepository instances.
     *
     * @var ObjectRepository[]
     */
    private $repositoryList = [];
    /**
     * {@inheritdoc}
     */
    public function getRepository(PimcoreEntityManagerInterface $entityManager, $entityName)
    {
        $repositoryHash = $entityManager->getClassMetadata($entityName)->getName() . spl_object_hash($entityManager);
        if (isset($this->repositoryList[$repositoryHash])) {
            return $this->repositoryList[$repositoryHash];
        }
        $this->repositoryList[$repositoryHash] = $this->createRepository($entityManager, $entityName);
        return $this->repositoryList[$repositoryHash];
    }

    /**
     * Create a new repository instance for an entity class.
     *
     * @param \RepositoryBundle\ORM\PimcoreEntityManagerInterface $entityManager The EntityManager instance.
     * @param string                                              $entityName The name of the entity.
     *
     * @return ObjectRepository
     * @throws \ReflectionException
     */
    private function createRepository(PimcoreEntityManagerInterface $entityManager, $entityName)
    {
        /* @var $metadata ClassMetadataInterface */
        $metadata            = $entityManager->getClassMetadata($entityName);
        $repositoryClassName = $metadata->getCustomRepositoryClassName()
            ?: $entityManager->getDefaultRepositoryClassName();
        $reflection = new \ReflectionClass($repositoryClassName);
        if (!$reflection->isSubclassOf(PimcoreEntityRepository::class)) {
            throw new \InvalidArgumentException('Repository must extends PimcoreEntityRepository');
        }
        return new $repositoryClassName($entityManager, $metadata);
    }
}
