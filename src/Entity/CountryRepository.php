<?php

declare(strict_types=1);

namespace Baraja\Country\Entity;


use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;

final class CountryRepository extends EntityRepository
{
	/**
	 * @throws NoResultException|NonUniqueResultException
	 */
	public function getById(int $id): Country
	{
		$return = $this->createQueryBuilder('country')
			->where('country.id = :id')
			->setParameter('id', $id)
			->setMaxResults(1)
			->getQuery()
			->getSingleResult();
		assert($return instanceof Country);

		return $return;
	}


	/**
	 * @throws NoResultException|NonUniqueResultException
	 */
	public function getByCode(string $code): Country
	{
		$return = $this->createQueryBuilder('country')
			->where('country.code = :code OR country.isoCode = :code')
			->setParameter('code', $code)
			->setMaxResults(1)
			->getQuery()
			->getSingleResult();
		assert($return instanceof Country);

		return $return;
	}


	public function getCount(): int
	{
		try {
			/** @var int $count */
			$count = $this->createQueryBuilder('country')
				->select('COUNT(country.id)')
				->getQuery()
				->getSingleScalarResult();
		} catch (NoResultException | NonUniqueResultException) {
			$count = 0;
		}

		return $count;
	}


	/**
	 * @return array<int, Country>
	 */
	public function getAll(): array
	{
		/** @var array<int, Country> $list */
		$list = $this->createQueryBuilder('country')
			->orderBy('country.active', 'DESC')
			->addOrderBy('country.name', 'ASC')
			->getQuery()
			->getResult();

		return $list;
	}
}
