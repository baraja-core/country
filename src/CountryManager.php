<?php

declare(strict_types=1);

namespace Baraja\Country;


use Baraja\Country\Entity\Country;
use Baraja\Doctrine\EntityManager;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;

final class CountryManager
{
	public function __construct(
		private EntityManager $entityManager,
	) {
	}


	public function getByCode(string $code): Country
	{
		try {
			return $this->entityManager->getRepository(Country::class)
				->createQueryBuilder('country')
				->where('country.code = :code OR country.isoCode = :code')
				->setParameter('code', $code)
				->setMaxResults(1)
				->getQuery()
				->getSingleResult();
		} catch (NoResultException | NonUniqueResultException $e) {
			if ($this->sync() === true) {
				return $this->getByCode($code);
			}
			throw $e;
		}
	}


	public function sync(): bool
	{
		return (new CountrySynchronizer($this->entityManager))->run();
	}
}
