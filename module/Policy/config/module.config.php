<?php
namespace Policy;

use Laminas\Router\Http\Segment;
use Laminas\ServiceManager\Factory\InvokableFactory;

return [

    'router' => [
        'routes' => [
            'policy' => [
                'type'    => Segment::class,
                'options' => [
                    'route' => '/policy[/:action[/:id]]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller' => Controller\PolicyController::class,
                        'action'     => 'index',
                        'page'       => 1
                    ],
                ],
            ],
        ],
    ],

    'view_manager' => [
        'template_path_stack' => [
            'album' => __DIR__ . '/../view',
        ],
    ],
];