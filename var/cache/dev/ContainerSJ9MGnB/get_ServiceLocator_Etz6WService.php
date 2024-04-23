<?php

namespace ContainerSJ9MGnB;

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

/**
 * @internal This class has been auto-generated by the Symfony Dependency Injection Component.
 */
class get_ServiceLocator_Etz6WService extends App_KernelDevDebugContainer
{
    /**
     * Gets the private '.service_locator.etz6_w_' shared service.
     *
     * @return \Symfony\Component\DependencyInjection\ServiceLocator
     */
    public static function do($container, $lazyLoad = true)
    {
        return $container->privates['.service_locator.etz6_w_'] = new \Symfony\Component\DependencyInjection\Argument\ServiceLocator($container->getService, [
            'entityManager' => ['services', 'doctrine.orm.default_entity_manager', 'getDoctrine_Orm_DefaultEntityManagerService', false],
            'reclamation' => ['privates', '.errored..service_locator.etz6_w_.App\\Entity\\Reclamation', NULL, 'Cannot autowire service ".service_locator.etz6_w_": it references class "App\\Entity\\Reclamation" but no such service exists.'],
        ], [
            'entityManager' => '?',
            'reclamation' => 'App\\Entity\\Reclamation',
        ]);
    }
}
