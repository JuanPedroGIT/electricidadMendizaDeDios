<?php

declare(strict_types=1);

namespace App\Application\Contact\Command;

use App\Application\Shared\Command\CommandHandlerInterface;
use App\Application\Shared\Command\CommandInterface;
use App\Domain\Contact\Entity\ContactLead;
use App\Domain\Contact\Repository\ContactLeadRepositoryInterface;
use App\Domain\Shared\ValueObject\Email;
use App\Domain\Shared\ValueObject\Phone;
use App\Application\Shared\Event\EventDispatcherInterface;
use App\Application\Contact\Event\ContactLeadCreatedEvent;
use App\Infrastructure\Logging\AuditLogger;

final class CreateContactLeadCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private ContactLeadRepositoryInterface $contactLeadRepository,
        private EventDispatcherInterface $eventDispatcher,
        private AuditLogger $auditLogger
    ) {
    }

    public function handle(CommandInterface $command): mixed
    {
        if (!$command instanceof CreateContactLeadCommand) {
            throw new \InvalidArgumentException('Invalid command type');
        }

        $email = Email::fromNullable($command->email);
        $phone = Phone::fromString($command->phone);

        $contactLead = ContactLead::create(
            $command->name,
            $phone,
            $email,
            $command->type,
            $command->area,
            $command->message,
            $command->ip,
            $command->userAgent
        );

        $this->contactLeadRepository->save($contactLead);

        // Audit logging
        $this->auditLogger->contactCreated(
            $contactLead->getId()->toString(),
            $contactLead->getName(),
            $contactLead->getEmail()?->toString(),
            $command->ip
        );

        $this->eventDispatcher->dispatch(new ContactLeadCreatedEvent(
            $contactLead->getId()->toString(),
            $contactLead->getName(),
            $contactLead->getPhone()->toString(),
            $contactLead->getEmail()?->toString(),
            $contactLead->getType(),
            $contactLead->getArea(),
            $contactLead->getMessage()
        ));
    }
}
