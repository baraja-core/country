<?php

declare(strict_types=1);

namespace Baraja\Country;


interface CountryManagerAccessor
{
	public function get(): CountryManager;
}
