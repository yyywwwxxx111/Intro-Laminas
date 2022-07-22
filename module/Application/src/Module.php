<?php

declare(strict_types=1);

namespace Application;

use Laminas\ModuleManager\Feature\ConfigProviderInterface;
use Laminas\Db\Adapter\AdapterInterface;
use Laminas\Db\ResultSet\ResultSet;
use Laminas\Db\TableGateway\TableGateway;

class Module implements ConfigProviderInterface
{
//    public function getConfig(): array
//    {
//        /** @var array $config */
//        $config = include __DIR__ . '/../config/module.config.php';
//        return $config;
//    }
    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }
    public function getServiceConfig()
    {
        return [
            'factories' => [
                Model\EmailTable::class => function ($container) {
                    $tableGateway = $container->get(Model\EmailTableGateway::class);
                    return new Model\EmailTable($tableGateway);
                },
                Model\EmailTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\Email());
                    return new TableGateway('email', $dbAdapter, null, $resultSetPrototype);
                },
            ],
        ];
    }
    public function getControllerConfig()
    {
        return [
            'factories' => [
                Controller\IndexController::class => function ($container) {
                    return new Controller\IndexController(
                        $container->get(Model\EmailTable::class)
                    );
                },
            ],
        ];
    }
}
