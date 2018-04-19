<?php

namespace Victoire\Widget\FormBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class VictoireWidgetFormExtension extends Extension implements PrependExtensionInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        foreach ($config as $key => $value) {
            $container->setParameter(
                'victoire_widget_form.'.$key, $value
            );
        }

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }

    /**
     * Allow an extension to prepend the extension configurations.
     */
    public function prepend(ContainerBuilder $container)
    {
        if ($container->hasParameter('victoire_widget_form.recaptcha_public_key')) {
            $twigConfig['globals']['recaptcha_public_key'] = $container->getParameter('victoire_widget_form.recaptcha_public_key');
            $container->prependExtensionConfig('twig', $twigConfig);
            $configs = $container->getExtensionConfig($this->getAlias());
            $config = $this->processConfiguration(new Configuration(), $configs);
        }
    }
}
