<?php

declare(strict_types=1);

namespace App\Infrastructure\EventListener;

use App\Application\Contact\Event\ContactLeadCreatedEvent;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

final readonly class ContactLeadCreatedEventListener
{
    public function __construct(
        private MailerInterface $mailer,
        #[Autowire('%env(NOTIFICATION_TO_EMAIL)%')]
        private string $notificationTo,
        #[Autowire('%env(NOTIFICATION_FROM_EMAIL)%')]
        private string $notificationFrom
    ) {
    }

    public function __invoke(ContactLeadCreatedEvent $event): void
    {
        try {
            $email = (new Email())
                ->from($this->notificationFrom)
                ->to($this->notificationTo)
                ->subject('Nuevo contacto desde web: ' . $event->name)
                ->html($this->buildEmailHtml($event));

            $this->mailer->send($email);
        } catch (\Exception $e) {
            // Log error but don't fail the request - user experience priority
            error_log('Failed to send contact notification: ' . $e->getMessage());
        }
    }

    private function buildEmailHtml(ContactLeadCreatedEvent $event): string
    {
        $typeLabels = [
            'presupuesto' => 'Solicitar presupuesto',
            'asesoria' => 'Asesoría técnica',
            'info' => 'Información general',
        ];
        $areaLabels = [
            'reforma' => 'Reforma',
            'construccion' => 'Construcción',
            'interiorismo' => 'Interiorismo',
            'otros' => 'Otros',
        ];

        $type = $typeLabels[$event->type] ?? $event->type ?? 'No especificado';
        $area = $areaLabels[$event->area] ?? $event->area ?? 'No especificado';

        $email = htmlspecialchars($event->email ?? '-', ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $message = htmlspecialchars($event->message ?? '-', ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $name = htmlspecialchars($event->name, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $phone = htmlspecialchars($event->phone, ENT_QUOTES | ENT_HTML5, 'UTF-8');

        return <<<HTML
        <h2>Nuevo contacto desde la web</h2>
        <table style="border-collapse: collapse; width: 100%; max-width: 600px;">
            <tr><td style="padding: 8px; border: 1px solid #ddd;"><strong>Nombre:</strong></td><td style="padding: 8px; border: 1px solid #ddd;">{$name}</td></tr>
            <tr><td style="padding: 8px; border: 1px solid #ddd;"><strong>Teléfono:</strong></td><td style="padding: 8px; border: 1px solid #ddd;">{$phone}</td></tr>
            <tr><td style="padding: 8px; border: 1px solid #ddd;"><strong>Email:</strong></td><td style="padding: 8px; border: 1px solid #ddd;">{$email}</td></tr>
            <tr><td style="padding: 8px; border: 1px solid #ddd;"><strong>Tipo:</strong></td><td style="padding: 8px; border: 1px solid #ddd;">{$type}</td></tr>
            <tr><td style="padding: 8px; border: 1px solid #ddd;"><strong>Área:</strong></td><td style="padding: 8px; border: 1px solid #ddd;">{$area}</td></tr>
            <tr><td style="padding: 8px; border: 1px solid #ddd;" colspan="2"><strong>Mensaje:</strong></td></tr>
            <tr><td style="padding: 8px; border: 1px solid #ddd;" colspan="2">{$message}</td></tr>
        </table>
        <p style="margin-top: 20px; color: #666; font-size: 12px;">Este mensaje fue enviado automáticamente desde el formulario de contacto.</p>
        HTML;
    }
}
