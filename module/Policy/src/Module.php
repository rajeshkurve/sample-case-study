<?php

namespace Policy;

use Laminas\Db\Adapter\AdapterInterface;
use Laminas\Db\ResultSet\ResultSet;
use Laminas\Db\TableGateway\TableGateway;
use Laminas\ModuleManager\Feature\ConfigProviderInterface;

class Module implements ConfigProviderInterface
{
    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    public function getServiceConfig()
    {
        return [
            'factories' => [
                Model\PolicyTable::class => function($container) {
                    $tableGateway = $container->get(Model\PolicyTableGateway::class);
                    return new Model\PolicyTable($tableGateway);
                },
                Model\PolicyTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\Policy());
                    return new TableGateway('policy', $dbAdapter, null, $resultSetPrototype);
                },
            ],
        ];
    }

    public function getControllerConfig()
    {
        return [
            'factories' => [
                Controller\PolicyController::class => function($container) {
                    return new Controller\PolicyController(
                        $container->get(Model\PolicyTable::class)
                    );
                },
            ],
        ];
    }
}
