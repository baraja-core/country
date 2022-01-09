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

		throw new \InvalidArgumentException(sprintf('Country flag for code "%s" does not exist.', $code));
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
			throw new \LogicException(sprintf('Emoji map file does not exist. Path "%s" given.', $source));
		}
		/** @var array<string, string> $map */
		$map = json_decode((string) file_get_contents($source), true, 512, JSON_THROW_ON_ERROR);
		self::$map = $map;
	}
}
