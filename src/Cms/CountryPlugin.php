<?php

declare(strict_types=1);

namespace Baraja\Country\Cms;


use Baraja\Plugin\BasePlugin;

final class CountryPlugin extends BasePlugin
{
	public function getName(): string
	{
		return 'Country';
	}


	public function getIcon(): ?string
	{
		return 'signpost-split';
	}
}
