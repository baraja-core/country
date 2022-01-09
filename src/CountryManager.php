<?php

declare(strict_types=1);

namespace Baraja\Country;


use Baraja\Country\Entity\Country;
use Baraja\Country\Entity\CountryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;

final class CountryManager
{
	private CountryRepository $countryRepository;


	public function __construct(
		private EntityManagerInterface $entityManager,
	) {
		$countryRepository = $entityManager->getRepository(Country::class);
		assert($countryRepository instanceof CountryRepository);
		$this->countryRepository = $countryRepository;
	}


	/**
	 * @return array<int, Country>
	 */
	public function getAll(): array
	{
		$list = $this->countryRepository->getAll();
		if ($list === []) {
			$this->sync();
			$list = $this->getAll();
		}

		return $list;
	}


	/**
	 * @throws NoResultException|NonUniqueResultException
	 */
	public function getById(int $id): Country
	{
		return $this->countryRepository->getById($id);
	}


	public function getByCode(string $code): Country
	{
		try {
			return $this->countryRepository->getByCode($code);
		} catch (NoResultException | NonUniqueResultException $e) {
			if ($this->sync() === true) {
				return $this->getByCode($code);
			}
			throw $e;
		}
	}


	public function setActive(Country $country, bool $active = true): void
	{
		$country->setActive($active);
		$this->entityManager->flush();
	}


	public function sync(): bool
	{
		return (new CountrySynchronizer($this->entityManager))->run();
	}
}
