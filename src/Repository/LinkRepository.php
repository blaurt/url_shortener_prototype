<?php

declare(strict_types=1);

namespace App\Repository;

use App\Model\Link\Entity\Link;
use App\Model\Link\ValueObject\Url;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\DBALException;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Link|null find($id, $lockMode = null, $lockVersion = null)
 * @method Link|null findOneBy(array $criteria, array $orderBy = null)
 * @method Link[]    findAll()
 * @method Link[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LinkRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Link::class);
    }

    /**
     * @param Url $url
     * @return mixed
     * @throws NonUniqueResultException
     */
    public function findLinkByUrl(Url $url)
    {
        return $this
            ->createQueryBuilder('link')
            ->where('link.url.url = :url')
            ->setParameter('url', $url->getValue())
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @param string $token
     * @return mixed
     * @throws NonUniqueResultException
     */
    public function findUrlByToken(string $token)
    {
        return $this
            ->createQueryBuilder('link')
            ->where('link.token.token = :token')
            ->setParameter('token', $token)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @param $url
     *
     * @throws DBALException
     */
    public function updateViews(Url $url)
    {
        $sql = '
            UPDATE link
            SET views = views + 1
            WHERE url = :url
        ';

        $this->getEntityManager()->getConnection()->executeUpdate($sql, [
            'url' => $url->getValue()
        ]);
    }
}
