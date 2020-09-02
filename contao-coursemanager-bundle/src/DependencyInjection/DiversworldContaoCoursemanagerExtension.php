<?php

/**
 * This file is part of a diversworld Contao Bundle.
 *
 * (c) Eckhard becker 2020 <info@diversworld.eu>
 * @author     Eckhard becker
 * @package    Course Manager
 * @license    GPL-3.0-or-later
 * @see        https://github.com/diversworld/contao-coursemanager-bundle
 *
 */

declare(strict_types=1);

namespace Diversworld\ContaoCoursemanagerBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

/**
 * Class DiversworldContaoCoursemanagerExtension
 *
 * @package Diversworld\ContaoCoursemanagerBundle\DependencyInjection
 */
class DiversworldContaoCoursemanagerExtension extends Extension
{

    /**
     * @param array $configs
     * @param ContainerBuilder $container
     * @throws \Exception
     */
    public function load(array $configs, ContainerBuilder $container): void
    {

        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__ . '/../Resources/config')
        );

        $loader->load('parameters.yml');
        $loader->load('services.yml');
        $loader->load('listener.yml');
    }
}
