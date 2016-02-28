<?php
/**
 * Created by PhpStorm.
 * User: alois - mumeraalois@gmail.com
 * Date: 10/25/2015
 * Time: 9:56 PM
 */

namespace Website\Model\Paginator\Repository;


use Application\Model\Constants;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;

class UserRepository extends EntityRepository
{
    /**
     * Counts how many users there are in the database
     *
     * @return int
     */
    public function count()
    {
        $query = $this->getEntityManager()->createQueryBuilder();
        $query->select(array('u.userId'))
            ->from(Constants::ENTITY_USERS, 'u');

        $result = $query->getQuery()->getResult();

        return count($result);
    }

    /**
     * Returns a list of users
     *
     * @param int $offset           Offset
     * @param int $itemCountPerPage Max results
     *
     * @return array
     */
    public function getItems($offset, $itemCountPerPage)
    {
        $query = $this->getEntityManager()->createQueryBuilder();
        $query->select(
            array('u'))
            ->from(Constants::ENTITY_USERS, 'u')
            ->where($query->expr()->orX(
                $query->expr()->gt('u.userId', '?1')
            ))
            ->setParameter(1 ,2)
            ->setFirstResult($offset)
            ->setMaxResults($itemCountPerPage);

        $result = $query->getQuery()->getResult();//Query::HYDRATE_ARRAY);

        return $result;
    }
}