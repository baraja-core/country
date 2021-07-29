<?php

declare(strict_types=1);

namespace Baraja\Country\Entity;


use Baraja\Doctrine\Identifier\IdentifierUnsigned;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;

#[Entity]
#[Table(name: 'core__country')]
final class Country
{
	use IdentifierUnsigned;

	#[Column(type: 'string', length: 2, unique: true)]
	private string $code;

	#[Column(type: 'string', length: 3, unique: true)]
	private string $isoCode;

	#[Column(type: 'string', length: 64)]
	private string $name;

	#[Column(type: 'string', length: 2)]
	private string $continent;

	#[Column(type: 'string', length: 64)]
	private string $capital;

	#[Column(type: 'string', length: 16)]
	private string $phone;

	#[Column(type: 'string', length: 3)]
	private string $currency;

	#[Column(type: 'boolean')]
	private bool $active = true;


	public function __construct(string $code, string $isoCode)
	{
		$this->setCode($code);
		$this->setIsoCode($isoCode);
	}


	public function getCode(): string
	{
		return $this->code;
	}


	public function setCode(string $code): void
	{
		$this->code = $code;
	}


	public function getIsoCode(): string
	{
		return $this->isoCode;
	}


	public function setIsoCode(string $isoCode): void
	{
		$this->isoCode = $isoCode;
	}


	public function getName(): string
	{
		return $this->name;
	}


	public function setName(string $name): void
	{
		$this->name = $name;
	}


	public function getContinent(): string
	{
		return $this->continent;
	}


	public function setContinent(string $continent): void
	{
		$this->continent = $continent;
	}


	public function getCapital(): string
	{
		return $this->capital;
	}


	public function setCapital(string $capital): void
	{
		$this->capital = $capital;
	}


	public function getPhone(): string
	{
		return $this->phone;
	}


	public function setPhone(string $phone): void
	{
		$this->phone = $phone;
	}


	public function getCurrency(): string
	{
		return $this->currency;
	}


	public function setCurrency(string $currency): void
	{
		$this->currency = $currency;
	}


	public function isActive(): bool
	{
		return $this->active;
	}


	public function setActive(bool $active): void
	{
		$this->active = $active;
	}
}
