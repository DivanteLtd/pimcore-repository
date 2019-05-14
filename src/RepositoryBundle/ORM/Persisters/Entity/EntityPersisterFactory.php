<?php
/**
 * @category    tigerspike
 * @date        14/05/2019
 * @author      Michał Bolka <michal.bolka@gmail.com>
 */

namespace RepositoryBundle\ORM\Persisters\Entity;

use Pimcore\Model\FactoryInterface;
use RepositoryBundle\Common\Persistence\Mapping\ClassMetadataInterface;
use RepositoryBundle\Common\PimcoreEntityManagerInterface;

/**
 * Class EntityPersisterFactory
 * @package RepositoryBundle\ORM\Persisters\Entity
 */
class EntityPersisterFactory
{
    /**
     * @param PimcoreEntityManagerInterface $em
     * @param ClassMetadataInterface        $classMetadata
     * @param FactoryInterface              $factory
     * @return BasicPimcoreEntityPersister
     */
    public function getEntityPersiter(
        PimcoreEntityManagerInterface $em,
        ClassMetadataInterface $classMetadata,
        FactoryInterface $factory
    ) {
        $persister = new BasicPimcoreEntityPersister($em, $classMetadata, $factory);
        return $persister;
    }
}
