<?php

declare(strict_types=1);

namespace Baraja\Country;


use Baraja\Country\Cms\CountryPlugin;
use Baraja\Doctrine\ORM\DI\OrmAnnotationsExtension;
use Baraja\Plugin\Component\VueComponent;
use Baraja\Plugin\PluginComponentExtension;
use Baraja\Plugin\PluginManager;
use Nette\DI\CompilerExtension;
use Nette\DI\Definitions\ServiceDefinition;

final class CountryExtension extends CompilerExtension
{
	public function beforeCompile(): void
	{
		$builder = $this->getContainerBuilder();
		PluginComponentExtension::defineBasicServices($builder);
		OrmAnnotationsExtension::addAnnotationPathToManager($builder, 'Baraja\Country\Entity', __DIR__ . '/Entity');

		$builder->addDefinition($this->prefix('countryManager'))
			->setFactory(CountryManager::class);

		$builder->addAccessorDefinition($this->prefix('countryManagerAccessor'))
			->setImplement(CountryManagerAccessor::class);

		/** @var ServiceDefinition $pluginManager */
		$pluginManager = $this->getContainerBuilder()->getDefinitionByType(PluginManager::class);
		$pluginManager->addSetup(
			'?->addComponent(?)', ['@self', [
				'key' => 'countryDefault',
				'name' => 'cms-country-default',
				'implements' => CountryPlugin::class,
				'componentClass' => VueComponent::class,
				'view' => 'default',
				'source' => __DIR__ . '/../template/default.js',
				'position' => 100,
				'tab' => 'Country',
				'params' => [],
			]]
		);
	}
}
