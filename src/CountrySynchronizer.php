<?php

declare(strict_types=1);

namespace Baraja\Country;


use Baraja\Country\Entity\Country;
use Baraja\Doctrine\EntityManager;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;

final class CountrySynchronizer
{
	private const DEFAULT_ACTIVE = [
		'CZE',
	];

	private string $isoCodes = 'http://country.io/iso3.json';

	private string $names = 'http://country.io/names.json';

	private string $continent = 'http://country.io/continent.json';

	private string $capital = 'http://country.io/capital.json';

	private string $phoneCode = 'http://country.io/phone.json';

	private string $currency = 'http://country.io/currency.json';


	public function __construct(
		private EntityManager $entityManager,
	) {
	}


	public function run(): bool
	{
		try {
			$countries = (int) $this->entityManager->getRepository(Country::class)
				->createQueryBuilder('country')
				->select('COUNT(country.id)')
				->getQuery()
				->getSingleScalarResult();
		} catch (NoResultException | NonUniqueResultException) {
			$countries = 0;
		}
		if ($countries > 0) {
			return false;
		}

		$return = [];
		foreach ($this->download($this->isoCodes) as $code => $isoCode) {
			$country = new Country($code, $isoCode);
			$country->setActive(isset(self::DEFAULT_ACTIVE[$isoCode]));
			$this->entityManager->persist($country);
			$return[$code] = $country;
		}
		foreach ($this->download($this->names) as $code => $name) {
			if (isset($return[$code]) === false) {
				continue;
			}
			$return[$code]->setName($name);
		}
		foreach ($this->download($this->continent) as $code => $continent) {
			if (isset($return[$code]) === false) {
				continue;
			}
			$return[$code]->setContinent($continent);
		}
		foreach ($this->download($this->capital) as $code => $capital) {
			if (isset($return[$code]) === false) {
				continue;
			}
			$return[$code]->setCapital($capital);
		}
		foreach ($this->download($this->phoneCode) as $code => $phoneCode) {
			if (isset($return[$code]) === false) {
				continue;
			}
			$return[$code]->setPhone($phoneCode);
		}
		foreach ($this->download($this->currency) as $code => $currency) {
			if (isset($return[$code]) === false) {
				continue;
			}
			$return[$code]->setCurrency($currency);
		}
		$this->entityManager->flush();

		return true;
	}


	/**
	 * @return array<string, string>
	 */
	private function download(string $url): array
	{
		$data = (string) file_get_contents($url);
		if (!$data) {
			throw new \InvalidArgumentException('API response for URL "' . $url . '" is empty.');
		}
		try {
			return (array) json_decode($data, true, 512, JSON_THROW_ON_ERROR);
		} catch (\Throwable $e) {
			throw new \InvalidArgumentException('Invalid json response: ' . $e->getMessage(), $e->getCode(), $e);
		}
	}
}
