<?php

declare(strict_types=1);

namespace Baraja\Country;


use Baraja\Country\Entity\Country;
use Baraja\Country\Entity\CountryRepository;
use Doctrine\ORM\EntityManagerInterface;

final class CountrySynchronizer
{
	private const DEFAULT_ACTIVE = [
		'CZE',
	];

	private string $isoCodes = 'https://cdn.brj.cz/data/country/iso3.json';

	private string $names = 'https://cdn.brj.cz/data/country/names.json';

	private string $continent = 'https://cdn.brj.cz/data/country/continent.json';

	private string $capital = 'https://cdn.brj.cz/data/country/capital.json';

	private string $phoneCode = 'https://cdn.brj.cz/data/country/phone.json';

	private string $currency = 'https://cdn.brj.cz/data/country/currency.json';

	private CountryRepository $countryRepository;


	public function __construct(
		private EntityManagerInterface $entityManager,
	) {
		$countryRepository = $entityManager->getRepository(Country::class);
		assert($countryRepository instanceof CountryRepository);
		$this->countryRepository = $countryRepository;
	}


	public function run(): bool
	{
		if ($this->countryRepository->getCount() > 0) {
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
		if ($data === '') {
			throw new \InvalidArgumentException(sprintf('API response for URL "%s" is empty.', $url));
		}
		try {
			/** @var array<string, string> $return */
			$return = (array) json_decode($data, true, 512, JSON_THROW_ON_ERROR);

			return $return;
		} catch (\Throwable $e) {
			throw new \InvalidArgumentException(sprintf('Invalid json response: %s', $e->getMessage()), 500, $e);
		}
	}
}
