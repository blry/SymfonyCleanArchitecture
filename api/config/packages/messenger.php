<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use App\Domain\User\Event\UserCreatedEvent;

return static function (ContainerConfigurator $container) {
    $container->extension('framework', [
        'messenger' => [
            'buses' => [
                'useCase.bus' => [
                    'middleware' => [
                        'dispatch_after_current_bus',
                        //'doctrine_transaction'
                    ],
                    'default_middleware' => [
                        'enabled' => true
                    ]
                ],
                'domain.event.bus' => [
                    'middleware' => [
                        'dispatch_after_current_bus',
                        //'doctrine_transaction'
                    ],
                    'default_middleware' => [
                        'enabled' => true,
                        'allow_no_handlers' => true
                    ]
                ],
                'integration.event.bus' => [
                    'middleware' => [
                        'dispatch_after_current_bus'
                    ],
                    'default_middleware' => [
                        'enabled' => true,
                        'allow_no_handlers' => true
                    ]
                ]
            ],
            'default_bus' => 'useCase.bus',

            'transports' => [
                'sync' => 'sync://',
                'async' => [
                    'dsn' => $_ENV['MESSENGER_TRANSPORT_DSN'],
                    'retry_strategy' => [
                        'max_retries' => 2,
                        'delay' => 3000,
                        'multiplier' => 2,
                    ]
                ]
            ],

            'routing' => [
                # all messages by default use sync transport
                \Symfony\Component\Mailer\Messenger\SendEmailMessage::class => ['async'],
                \Symfony\Component\Notifier\Message\MessageInterface::class => ['async'],
                \App\Application\Common\IntegrationEvent\IntegrationEventInterface::class => ['async'],
                \App\Domain\User\Event\UserCreatedEvent::class => ['async'],
            ],
        ],
    ]);
};
