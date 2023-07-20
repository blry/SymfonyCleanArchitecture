# Symfony Clean Architecture

This repository serves as a foundational Clean Architecture template for PHP 7 and Symfony 6, drawing inspiration from [the ideas of Robert C. Martin (Uncle Bob) on Clean Architecture](https://blog.cleancoder.com/uncle-bob/2012/08/13/the-clean-architecture.html) and Domain-Driven Design (DDD) by Robert C. Martin.
Using Symfony OAuth2 Server bundle (league/oauth2-server-bundle), here you can see an example of users api.

The template demonstrates an exemplary implementation of a users API using the Symfony OAuth2 Server ([league/oauth2-server-bundle](https://github.com/thephpleague/oauth2-server-bundle)) and the Symfony OAuth2 Client ([knpuniversity/oauth2-client-bundle](https://github.com/knpuniversity/oauth2-client-bundle)) bundles.

It includes the implementation of the `social` grant, designed specifically to handle social login functionalities. By utilizing the `social` grant, users gain the ability to log in to the application using any OAuth2 client provider, such as Telegram. If you wish to integrate additional OAuth2 client providers, you can easily do so by modifying the configuration in [config/packages/knpu_oauth2_client.yaml](api/config/packages/knpu_oauth2_client.yaml).

[![Author](https://img.shields.io/badge/author-alexander.sterpu%40gmail.com-blue.svg)](https://github.com/blry)
[![GitHub release](https://img.shields.io/badge/Docker-compose-blue.svg?style=flat-square)](https://github.com/docker/compose/releases/latest)
[![GitHub repository](https://img.shields.io/badge/Traefik-purple.svg?style=flat-square)](https://github.com/traefik/traefik)
[![Docker hub](https://img.shields.io/badge/PHP-v8.2-brightgreen.svg?style=flat-square)](https://hub.docker.com/_/php)
[![GitHub repository](https://img.shields.io/badge/Symfony-v6.3-blue.svg?style=flat-square)](https://github.com/symfony/symfony)
[![Docker hub](https://img.shields.io/badge/MariaDB-v10-pink.svg?style=flat-square)](https://hub.docker.com/_/mariadb)
[![Docker hub](https://img.shields.io/badge/MailHog-v1-green.svg?style=flat-square)](https://hub.docker.com/r/mailhog/mailhog/)
[![Docker hub](https://img.shields.io/badge/Ofelia-red.svg?style=flat-square)](https://hub.docker.com/r/mcuadros/ofelia)

## Table Of Contents

- [Symfony Clean Architecture](#symfony-clean-architecture)
  * [Table Of Contents](#table-of-contents)
  * [Project Structure](#project-structure)
  * [Give a Star! :star:](#give-a-star-star)
- [Getting Started](#getting-started)
  * [Requirements](#requirements)
  * [Installation](#installation)
- [Learn more](#learn-more)
  * [Domain Events vs Integration Events vs Infrastructure Events](#domain-events-vs-integration-events-vs-infrastructure-events)
  * [How to make an Event to be asynchronous?](#how-to-make-an-event-to-be-asynchronous)
  * [Application Services vs Domain Services](#application-services-vs-domain-services)
  * [Related links](#related-links)

## Project structure

The project follows a Clean Architecture structure to ensure a clear separation of concerns. The main directories are:
- <b>api</b>: Contains the application source code.
    - <b>src/Domain</b>: The core domain logic and entities of the application.
    - <b>src/Application</b>:
        - `Bounded Context`<b>/UseCase</b>: Use Cases represent the actions which encapsulate the business logic and interact with the domain layer to achieve specific tasks.
        - `Bounded Context`<b>/DomainEventHandler</b>: Domain event handlers are responsible for handling domain events raised within the domain layer.
        - `Bounded Context`<b>/IntegrationEventHandler</b>: Integration event handlers are responsible for handling integration events that are received from other bounded contexts or external systems.
    - <b>src/Infrastructure</b>: External tools and frameworks integration, such as databases, third-party services, etc.
- <b>docker</b>: Contains configs related to docker containers
    - <b>shared</b>: Shared files

## Give a Star! :star:
If you like or are using this project to learn or start your solution, please give it a star. Thanks!

# Getting Started

## Requirements

Ensure you have the following installed:
- [Docker](https://docs.docker.com/get-docker/)
- [Docker Compose](https://docs.docker.com/compose/install/)

and these ports are open:
- `80`, `443`, `8080` for [Traefik](https://hub.docker.com/_/traefik)
- `8082` for [Nginx](https://hub.docker.com/_/nginx)
- `9000` for [PHP-FPM](https://hub.docker.com/_/php)
- `3306` for [MariaDB](https://hub.docker.com/_/mariadb)
- `1025`, `8025` for [MailHog](https://hub.docker.com/r/mailhog/mailhog) (an SMTP-testing server)

## Installation

1. Clone this repository to your local machine:
```bash
git clone https://github.com/pikaso443/retrans-live
```

2. Create `.env` file in the root folder and set <b>your</b> variables by copying [.env.dev_local](.env.dev_local)
- `TRAEFIK_API_HOST` - Traefik will use the variable to create a certificate with [Let's Encrypt](https://letsencrypt.org/about/). Make sure DNS records are set correctly.
- `TRAEFIK_TOKEN` - Traefik token

3. Create [secrets](https://docs.docker.com/compose/use-secrets/) in [docker/shared/secrets/](docker/shared/secrets/):
- `db_root_password`, `db_password` - DB root and application user passwords
- `oauth2_encryption.key`, `oauth2_private.key`, `oauth2_public.key` - OAuth2 Server Encryption, Private and Public keys
- `telegram_bot_token` - OAuth2 Client Telegram Bot token

4. Now you can run the project, install dependencies, run migrations and fixtures:
```bash
docker-compose up -d
docker-compose exec php-cli bash -ilc "cd /var/www/api && composer install && bin/console do:mi:mi --no-interaction"
docker-compose restart
docker-compose exec php-cli bash -ilc "cd /var/www/api && bin/console do:fi:lo --no-interaction"
```

5. Done!
- <b>Traefik</b> starts on port `80`, `443`, `8080`. Access `https://TRAEFIK_API_HOST` using browser
- <b>Nginx</b> starts on port `8082`: [localhost:8082/doc](http://localhost:8082/doc)
- <b>Mailhog</b> HTTP server starts on port `8025`: [localhost:8025](http://localhost:8025)

# Learn more

## Domain Events vs Integration Events vs Infrastructure Events

In the context of Domain-Driven Design (DDD) and event-driven architectures, Domain Events, Integration Events and Infrastructure Events are mechanisms for handling events and interactions within a system. However, they serve different purposes and are used in different contexts.

### Domain Events
<b>Domain Events</b> are a core concept in DDD and represent significant state changes or business occurrences within the domain model. They are used to communicate important business events or facts that have happened within the application's domain. Domain Events are raised from within the domain entities or aggregate roots and are typically used to trigger side effects or update other parts of the system.

Example of a Domain Event: [UserCreatedEvent](api/src/Domain/User/Event/UserCreatedEvent.php), which is raised when a new user is created. Other DomainEventHandlers within the same domain model can listen to this event (e.g. [SendEmailOnUserCreatedEventHandler](api/src/Application/User/Notifications/SendEmailOnUserCreatedEventHandler.php)).

### Integration Events
<b>Integration Events</b> are events that facilitate communication and integration between different bounded contexts or external systems. They are used to signal changes or facts to other parts of the system, often outside the core domain.

Example of a Integration Event: [UserCreatedIntegrationEvent](api/src/Application/IntegrationEvent/User/UserCreatedIntegrationEvent.php), which is mapped from [UserCreatedEvent](api/src/Domain/User/Event/UserCreatedEvent.php) in [IntegrationEventMapper](src/Application/User/IntegrationEventMapper.php) and dispatched by [DomainEventSubscriber](api/src/Infrastructure/EventSubscriber/Core/DomainEventSubscriber.php).
An [IntegrationEventHandler](api/src/Application/Stream/IntegrationEventHandler/User/CreateStreamerOnUserCreatedEventHandler.php) from another bounded context is subscribed to it.

### Infrastructure Events
<b>Infrastructure Events</b> are events which are raised by the framework (e.g. Doctrine's `LifecycleEventArgs` which is handled by [DomainEventSubscriber](api/src/Infrastructure/EventSubscriber/Core/DomainEventSubscriber.php)).

## How to make an Event to be asynchronous?

<b>Integration Events</b> are asynchronous <b>by default</b>. In order to make a <b>Domain Event</b> asynchronous, modify [messenger.php](api/config/packages/messenger.php):
```php
'routing' => [
    \Symfony\Component\Mailer\Messenger\SendEmailMessage::class => ['async'],
    \Symfony\Component\Notifier\Message\MessageInterface::class => ['async'],
    \App\Application\Common\IntegrationEvent\IntegrationEventInterface::class => ['async'],
    \App\Domain\User\Event\UserCreatedEvent::class => ['async'],
],
```

Also, you may want to make a Handler to work only with asynchronous events:
```php
#[AsMessageHandler(fromTransport: 'async')]
readonly class YourEventHandler implements HandlerInterface
{
    public function __invoke(Input $input): void {}
}
```

## Related links
- [The Clean Architecture](https://blog.cleancoder.com/uncle-bob/2012/08/13/the-clean-architecture.html)
- [Domain events: Design and implementation](https://learn.microsoft.com/en-us/dotnet/architecture/microservices/microservice-ddd-cqrs-patterns/domain-events-design-implementation)
- [Hexagonal architecture and Domain Driven Design ](https://dev.to/onepoint/hexagonal-architecture-and-domain-driven-design-fio)
- [From Domain to Integration Event](https://www.ledjonbehluli.com/posts/domain_to_integration_event/)
- [Domain Services vs Application Services](https://enterprisecraftsmanship.com/posts/domain-vs-application-services/)
- [Domain-Driven Design: Domain Events and Integration Events in .Net](https://betterprogramming.pub/domain-driven-design-domain-events-and-integration-events-in-net-5a2a58884aaa)
