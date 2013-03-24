<?php
namespace Album\Entity;

use Doctrine\ORM\EntityRepository;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;

class AlbumRepository extends EntityRepository
{
    /** @var \DoctrineModule\Stdlib\Hydrator\DoctrineObject */
    protected $hydrator;


    /**
     * @return \DoctrineModule\Stdlib\Hydrator\DoctrineObject
     */
    public function getHydrator()
    {
        if (is_null($this->hydrator)) {
            $this->hydrator = new DoctrineHydrator($this->getEntityManager(), '\Album\Entity\Album');
        }

        return $this->hydrator;
    }
}