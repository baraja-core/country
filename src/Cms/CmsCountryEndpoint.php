<?php

declare(strict_types=1);

namespace Baraja\Country\Cms;


use Baraja\Country\CountryManagerAccessor;
use Baraja\StructuredApi\BaseEndpoint;

final class CmsCountryEndpoint extends BaseEndpoint
{
	public function __construct(
		private CountryManagerAccessor $countryManagerAccessor,
	) {
	}


	public function actionDefault(): void
	{
		$countries = [];
		foreach ($this->countryManagerAccessor->get()->getAll() as $country) {
			$countries[] = [
				'id' => $country->getId(),
				'flag' => $country->getFlag(),
				'code' => $country->getCode(),
				'isoCode' => $country->getIsoCode(),
				'name' => $country->getName(),
				'currency' => $country->getCurrency(),
				'capital' => $country->getCapital(),
				'continent' => $country->getContinent(),
				'active' => $country->isActive(),
			];
		}

		$this->sendJson(
			[
				'countries' => $countries,
			],
		);
	}


	public function postSetActive(int $id): void
	{
		$country = $this->countryManagerAccessor->get()->getById($id);
		$this->countryManagerAccessor->get()->setActive($country, !$country->isActive());
		$this->flashMessage(
			sprintf('Country has been marked as %s.', $country->isActive() ? 'active' : 'hidden'),
			self::FLASH_MESSAGE_SUCCESS,
		);
		$this->sendOk();
	}
}
