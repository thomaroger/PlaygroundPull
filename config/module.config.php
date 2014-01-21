<?php
return array(
    'doctrine' => array(
        'driver' => array(
            'playgroundpull_entity' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => __DIR__ . '/../src/PlaygroundPull/Entity'
            ),
            
            'orm_default' => array(
                'drivers' => array(
                    'PlaygroundPull\Entity'  => 'playgroundpull_entity'
                )
            )
        )
    ),
    'router' => array(
        'routes' => array(
            'admin' => array(
                'child_routes' => array(
                    'playgroundpull' => array(
                        'type' => 'Literal',
                        'priority' => 1000,
                        'options' => array(
                            'route' => '/pull',
                            'defaults' => array(
                                'controller' => 'PlaygroundPull\Controller\Admin\Pull',
                                'action' => 'list',
                            ),
                        ),
                        'may_terminate' => true,
                        'child_routes' => array(
                            'add' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/add',
                                    'defaults' => array(
                                        'controller' => 'PlaygroundPull\Controller\Admin\Pull',
                                        'action'     => 'add',
                                    ),
                                ),
                            ),
                            'edit' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/edit[/:questionId]',
                                    'defaults' => array(
                                        'controller' => 'PlaygroundPull\Controller\Admin\Pull',
                                        'action'     => 'edit',
                                    ),
                                    'constraints' => array(
                                        'questionId' => '[0-9]*',
                                    ),
                                ),
                            ),
                            'remove' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/remove[/:questionId]',
                                    'defaults' => array(
                                        'controller' => 'PlaygroundPull\Controller\Admin\Pull',
                                        'action'     => 'remove',
                                    ),
                                    'constraints' => array(
                                        'questionId' => '[0-9]*',
                                    ),
                                ),
                            ),
                        ),
                    ),
                ),
            ),

            'frontend' => array(
                'child_routes' => array(
                    'playgroundpull_post' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => 'pull/post/[:answerId]',
                            'defaults' => array(
                                'controller' => 'PlaygroundPull\Controller\Frontend\Pull',
                                'action'     => 'post',
                            ),
                            'constraints' => array(
                                'answerId' => '[0-9]*',
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory',
        )
    ),
    'controllers' => array(
        'invokables' => array(
           "PlaygroundPull\Controller\Admin\Pull" => "PlaygroundPull\Controller\Admin\PullController",
           "PlaygroundPull\Controller\Frontend\Pull" => "PlaygroundPull\Controller\Frontend\PullController",
        ),
    ),
    'navigation' => array(
        'admin' => array(
            'playgroundPull' => array(
                'label' => 'Pull',
                'route' => 'admin/playgroundpull',
                'resource' => 'pull',
                'privilege' => 'index',
            ),
        ),
    ),
);