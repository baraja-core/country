<?php

declare(strict_types=1);

namespace Baraja\Country;


final class CountryToEmoji
{
	/** @var array<string, string> */
	private static array $map = [];


	public static function getByCode(string $code): string
	{
		self::load();
		$code = strtoupper($code);
		if (isset(self::$map[$code])) {
			return self::$map[$code];
		}

		throw new \InvalidArgumentException('Country flag for code "' . $code . '" does not exist.');
	}


	/**
	 * @return string[]
	 */
	public static function getMap(): array
	{
		return self::$map;
	}


	private static function load(): void
	{
		if (self::$map !== []) {
			return;
		}
		$source = __DIR__ . '/../flag-emoji.json';
		if (!is_file($source)) {
			throw new \LogicException('Emoji map file does not exist. Path "' . $source . '" given.');
		}
		/** @var array<string, string> $map */
		$map = json_decode((string) file_get_contents($source), true, 512, JSON_THROW_ON_ERROR);
		self::$map = $map;
	}
}
