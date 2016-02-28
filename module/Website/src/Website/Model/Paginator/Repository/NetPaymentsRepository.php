<?php
/**
 * Created by PhpStorm.
 * User: alois - mumeraalois@gmail.com
 * Date: 10/26/2015
 * Time: 11:01 AM
 */

namespace Website\Model\Paginator\Repository;


use Application\Model\Constants;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\Tools\Pagination\Paginator;

class NetPaymentsRepository extends EntityRepository
{
    /**
     * Counts how many users there are in the database
     *
     * @return int
     */
    public function count()
    {
        $query = $this->getEntityManager()->createQueryBuilder();
        $query->select(array('u.id'))
            ->from(Constants::ENTITY_FROM_NETTCASH_SERVER, 'u');

        $result = $query->getQuery()->getResult();

        return count($result);
    }

    /**
     * Returns a list of payments
     *
     * @param int $offset           Offset
     * @param int $itemCountPerPage Max results
     *
     * @return array
     */
    public function getItems($offset, $itemCountPerPage)
    {
        $query = $this->getEntityManager()->createQueryBuilder();
        $query->select(array('u'))
            ->from(Constants::ENTITY_FROM_NETTCASH_SERVER, 'u')
            ->setFirstResult($offset)
            ->setMaxResults($itemCountPerPage);

        $result = $query->getQuery()->getResult();//Query::HYDRATE_ARRAY

        return $result;
    }
}