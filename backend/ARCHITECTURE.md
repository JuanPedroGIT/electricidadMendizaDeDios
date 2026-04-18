# Hexagonal Architecture & CQRS Refactoring

This document describes the refactored architecture of the Construcciones Salamanca backend.

## Architecture Overview

The application follows **Hexagonal Architecture (Clean Architecture)** principles combined with **CQRS** (Command Query Responsibility Segregation) pattern.

```
┌─────────────────────────────────────────────────────────────────┐
│                      PRESENTATION LAYER                          │
│  ┌─────────────────┐ ┌─────────────────┐ ┌──────────────────┐  │
│  │   Controllers   │ │    DTOs/Forms   │ │ EventSubscribers │  │
│  └────────┬────────┘ └─────────────────┘ └──────────────────┘  │
└───────────┼───────────────────────────────────────────────────────┘
            │ HTTP Requests / Responses
            ▼
┌─────────────────────────────────────────────────────────────────┐
│                      APPLICATION LAYER                           │
│  ┌─────────────────────────────────────────────────────────────┐  │
│  │                    CQRS - Command Side                      │  │
│  │  ┌─────────────┐  ┌─────────────┐  ┌──────────────────┐  │  │
│  │  │  Commands   │  │   Handlers  │  │ Domain Events      │  │  │
│  │  └──────┬──────┘  └──────┬──────┘  └──────────────────┘  │  │
│  │         └────────────────┘                                 │  │
│  └─────────────────────────────────────────────────────────────┘  │
│  ┌─────────────────────────────────────────────────────────────┐  │
│  │                     CQRS - Query Side                       │  │
│  │  ┌─────────────┐  ┌─────────────┐  ┌──────────────────┐  │  │
│  │  │   Queries   │  │   Handlers  │  │   DTOs/ViewModel │  │  │
│  │  └──────┬──────┘  └──────┬──────┘  └──────────────────┘  │  │
│  │         └────────────────┘                                 │  │
│  └─────────────────────────────────────────────────────────────┘  │
│  ┌─────────────────────────────────────────────────────────────┐  │
│  │                  Application Services                       │  │
│  │         ┌─────────────┐  ┌─────────────┐                  │  │
│  │         │  CommandBus │  │   QueryBus  │                  │  │
│  │         └─────────────┘  └─────────────┘                  │  │
│  └─────────────────────────────────────────────────────────────┘  │
└─────────────────────────────────────────────────────────────────┘
            │
            ▼
┌─────────────────────────────────────────────────────────────────┐
│                        DOMAIN LAYER                            │
│  ┌─────────────────┐ ┌─────────────────┐ ┌──────────────────┐  │
│  │    Entities     │ │  Value Objects  │ │ Domain Services  │  │
│  │                 │ │  (Uuid, Email...) │ │ (AuthService...) │  │
│  └────────┬────────┘ └─────────────────┘ └──────────────────┘  │
│           │                                                      │
│  ┌────────┴───────────────────────────────────────────────────┐   │
│  │              Repository Interfaces (Ports)               │   │
│  └───────────────────────────────────────────────────────────┘   │
└─────────────────────────────────────────────────────────────────┘
            │
            ▼
┌─────────────────────────────────────────────────────────────────┐
│                     INFRASTRUCTURE LAYER                         │
│  ┌─────────────────┐ ┌─────────────────┐ ┌──────────────────┐  │
│  │  Doctrine ORM   │ │  External Svcs  │ │   DataSeeders    │  │
│  │  Repositories   │ │  (Mailer, etc)  │ │                  │  │
│  │  (Adapters)     │ │                 │ │                  │  │
│  └─────────────────┘ └─────────────────┘ └──────────────────┘  │
│  ┌──────────────────────────────────────────────────────────┐ │
│  │              Bus Implementations (Symfony Messenger)        │ │
│  │         ┌──────────────────┐  ┌──────────────────┐        │ │
│  │         │  SymfonyCommandBus │  │  SymfonyQueryBus │        │ │
│  │         └──────────────────┘  └──────────────────┘        │ │
│  └────────────────────────────────────────────────────────────┘ │
└─────────────────────────────────────────────────────────────────┘
```

## Directory Structure

```
backend/src/
├── Domain/                          # Domain Layer - Business Logic
│   ├── Contact/
│   │   ├── Entity/                  # Domain Entities
│   │   │   └── ContactLead.php
│   │   └── Repository/              # Repository Interfaces (Ports)
│   │       └── ContactLeadRepositoryInterface.php
│   ├── Service/
│   │   ├── Entity/
│   │   │   └── Service.php
│   │   └── Repository/
│   │       └── ServiceRepositoryInterface.php
│   ├── Faq/
│   │   ├── Entity/
│   │   │   └── Faq.php
│   │   └── Repository/
│   │       └── FaqRepositoryInterface.php
│   ├── SiteSetting/
│   │   ├── Entity/
│   │   │   └── SiteSetting.php
│   │   └── Repository/
│   │       └── SiteSettingRepositoryInterface.php
│   ├── Admin/
│   │   ├── Entity/
│   │   │   └── AdminSession.php
│   │   └── Service/
│   │       └── AuthenticationService.php
│   └── Shared/
│       └── ValueObject/             # Value Objects
│           ├── Uuid.php
│           ├── DateTime.php
│           ├── Email.php
│           └── Phone.php
│
├── Application/                     # Application Layer - Use Cases
│   ├── Shared/
│   │   ├── Command/
│   │   │   ├── CommandInterface.php
│   │   │   └── CommandHandlerInterface.php
│   │   ├── Query/
│   │   │   ├── QueryInterface.php
│   │   │   └── QueryHandlerInterface.php
│   │   ├── Bus/
│   │   │   ├── CommandBusInterface.php
│   │   │   └── QueryBusInterface.php
│   │   └── Event/
│   │       └── EventDispatcherInterface.php
│   ├── Contact/
│   │   ├── Command/                 # Commands & Handlers
│   │   │   ├── CreateContactLeadCommand.php
│   │   │   └── CreateContactLeadCommandHandler.php
│   │   └── Event/
│   │       └── ContactLeadCreatedEvent.php
│   ├── Service/
│   │   ├── Command/
│   │   │   ├── CreateServiceCommand.php
│   │   │   ├── CreateServiceCommandHandler.php
│   │   │   ├── UpdateServiceCommand.php
│   │   │   └── UpdateServiceCommandHandler.php
│   │   ├── Query/                   # Queries & Handlers
│   │   │   ├── GetServicesQuery.php
│   │   │   ├── GetServicesQueryHandler.php
│   │   │   ├── GetServiceBySlugQuery.php
│   │   │   └── GetServiceBySlugQueryHandler.php
│   │   └── DTO/                     # Data Transfer Objects
│   │       └── ServiceDTO.php
│   ├── Faq/
│   │   ├── Command/
│   │   │   ├── CreateFaqCommand.php
│   │   │   ├── CreateFaqCommandHandler.php
│   │   │   ├── UpdateFaqCommand.php
│   │   │   └── UpdateFaqCommandHandler.php
│   │   ├── Query/
│   │   │   ├── GetFaqsQuery.php
│   │   │   └── GetFaqsQueryHandler.php
│   │   └── DTO/
│   │       └── FaqDTO.php
│   ├── SiteSetting/
│   │   ├── Command/
│   │   │   ├── UpdateSiteSettingsCommand.php
│   │   │   └── UpdateSiteSettingsCommandHandler.php
│   │   └── Query/
│   │       ├── GetPublicSiteSettingsQuery.php
│   │       └── GetPublicSiteSettingsQueryHandler.php
│   └── Admin/
│       ├── Command/
│       │   ├── LoginAdminCommand.php
│       │   ├── LoginAdminCommandHandler.php
│       │   ├── LogoutAdminCommand.php
│       │   └── LogoutAdminCommandHandler.php
│       ├── Query/
│       │   ├── GetAdminSessionQuery.php
│       │   └── GetAdminSessionQueryHandler.php
│       └── DTO/
│           └── AdminSessionDTO.php
│
├── Infrastructure/                  # Infrastructure Layer
│   ├── Bus/                         # Bus Implementations
│   │   ├── SymfonyCommandBus.php
│   │   └── SymfonyQueryBus.php
│   ├── Event/
│   │   └── SymfonyEventDispatcher.php
│   ├── EventListener/
│   │   └── ContactLeadCreatedEventListener.php
│   ├── Data/
│   │   └── SiteContent.php          # Static data/content
│   ├── DataSeeder/
│   │   └── DatabaseSeeder.php
│   └── Persistence/
│       └── Doctrine/
│           ├── Entity/              # ORM Entities
│           │   ├── ContactLeadOrmEntity.php
│           │   ├── ServiceOrmEntity.php
│           │   ├── FaqOrmEntity.php
│           │   └── SiteSettingOrmEntity.php
│           └── Repository/          # Repository Implementations
│               ├── DoctrineContactLeadRepository.php
│               ├── DoctrineServiceRepository.php
│               ├── DoctrineFaqRepository.php
│               └── DoctrineSiteSettingRepository.php
│
└── Presentation/                      # Presentation Layer
    ├── Controller/
    │   ├── PublicApiController.php
    │   ├── AdminApiController.php
    │   └── HealthController.php
    └── EventSubscriber/
        ├── AdminAuthSubscriber.php
        └── SecurityHeadersSubscriber.php
```

## CQRS Implementation

### Commands (Write Operations)

Commands represent write operations that change the state of the system:

- `CreateContactLeadCommand` - Creates a new contact lead
- `CreateServiceCommand` / `UpdateServiceCommand` - Manage services
- `CreateFaqCommand` / `UpdateFaqCommand` - Manage FAQs
- `UpdateSiteSettingsCommand` - Update site settings
- `LoginAdminCommand` / `LogoutAdminCommand` - Admin authentication

### Queries (Read Operations)

Queries represent read operations that return data without side effects:

- `GetServicesQuery` - Returns all services
- `GetServiceBySlugQuery` - Returns a service by slug
- `GetFaqsQuery` - Returns all FAQs
- `GetPublicSiteSettingsQuery` - Returns public site settings
- `GetAdminSessionQuery` - Returns admin session info

### Bus Pattern

The Bus pattern decouples the controllers from the command/query handlers:

```php
// Command Bus usage
$this->commandBus->dispatch(new CreateContactLeadCommand(...));

// Query Bus usage
$services = $this->queryBus->ask(new GetServicesQuery());
```

## Dependency Flow

1. **Presentation Layer** → **Application Layer** (Bus interfaces)
2. **Application Layer** → **Domain Layer** (Entities, Repository interfaces)
3. **Infrastructure Layer** → **Application Layer** (implements interfaces)
4. **Infrastructure Layer** → **Domain Layer** (maps to/from entities)

## Key Principles

1. **Dependency Inversion**: Inner layers (Domain) do not depend on outer layers (Infrastructure)
2. **Single Responsibility**: Each class has a single reason to change
3. **Interface Segregation**: Repository interfaces define only what's needed
4. **CQRS**: Separate read and write paths for better scalability
5. **Domain Events**: Contact lead creation triggers email notification asynchronously

## Testing

Each layer can be tested independently:

- **Domain**: Unit tests for entities and value objects (no dependencies)
- **Application**: Unit tests with mocked repositories
- **Infrastructure**: Integration tests with database
- **Presentation**: Functional tests with HTTP requests
