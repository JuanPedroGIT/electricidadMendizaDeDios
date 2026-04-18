<?php

declare(strict_types=1);

namespace App\Infrastructure\Data;

final class SiteContent
{
    public static function services(): array
    {
        return [
            [
                'slug' => 'reformas-integrales',
                'name' => 'Reformas integrales',
                'summary' => 'Coordinación completa con plan de obra, licencias y acabados llave en mano.',
                'benefits' => ['Plan semanal de hitos', 'Presupuesto cerrado', 'Equipo propio'],
            ],
            [
                'slug' => 'cocinas',
                'name' => 'Cocinas',
                'summary' => 'Instalaciones nuevas, mobiliario a medida y encimeras resistentes.',
                'benefits' => ['Optimización de espacio', 'Entrega lista para usar', 'Instalaciones verificadas'],
            ],
            [
                'slug' => 'banos',
                'name' => 'Baños',
                'summary' => 'Impermeabilización, fontanería y acabados fáciles de limpiar.',
                'benefits' => ['Plato de ducha seguro', 'Ventilación y humedad controlada'],
            ],
            [
                'slug' => 'locales-hosteleria',
                'name' => 'Locales y hostelería',
                'summary' => 'Adaptamos a normativa, fases para abrir antes y coordinación de industriales.',
                'benefits' => ['Cronograma por fases', 'Licencias y seguridad', 'Instalaciones certificadas'],
            ],
            [
                'slug' => 'cubiertas-fachadas',
                'name' => 'Cubiertas y fachadas',
                'summary' => 'Rehabilitación, aislamiento y mejoras de eficiencia energética.',
                'benefits' => ['Garantía de estanqueidad', 'Menos andamios', 'Aislamiento térmico'],
            ],
            [
                'slug' => 'obra-nueva-unifamiliar',
                'name' => 'Obra nueva unifamiliar',
                'summary' => 'Estructura, instalaciones y acabados llave en mano.',
                'benefits' => ['Memoria de calidades', 'Control de calidad', 'Plan de obra completo'],
            ],
        ];
    }

    public static function faqs(): array
    {
        return [
            [
                'question' => '¿Quién tramita las licencias en Salamanca?',
                'answer' => 'Gestionamos la documentación y la presentamos. Informamos de tasas y plazos según el tipo de obra.',
            ],
            [
                'question' => '¿En qué zonas trabajáis?',
                'answer' => 'Salamanca capital y alfoz (Santa Marta, Carbajosa, Villamayor, Cabrerizos). Consultar otras zonas.',
            ],
            [
                'question' => '¿Qué plazos manejáis?',
                'answer' => 'Visita en 24 h, presupuesto en 72 h y plan semanal antes de empezar. Baños 10-14 días, cocinas 2-3 semanas, integral 6-10 semanas según alcance.',
            ],
            [
                'question' => '¿Cómo se paga la obra?',
                'answer' => 'Señal al aceptar, hitos por avance y último pago tras la entrega. Financiación opcional con entidades locales.',
            ],
            [
                'question' => '¿Incluye limpieza final?',
                'answer' => 'Sí, con retirada de escombros y limpieza profesional antes de la entrega.',
            ],
            [
                'question' => '¿Qué garantía ofrecéis?',
                'answer' => 'Garantía por escrito sobre instalaciones y acabados, atención prioritaria a incidencias.',
            ],
        ];
    }

    public static function siteSettingsPublic(): array
    {
        return [
            'brand' => 'Construcciones Salamanca',
            'tagline' => 'Reformas integrales en Salamanca con seguimiento diario',
            'phone' => '+34923123456',
            'phone_display' => '923 123 456',
            'whatsapp' => 'https://wa.me/34923123456?text=Hola,%20quiero%20un%20presupuesto%20para%20una%20reforma%20en%20Salamanca',
            'email' => 'hola@construccionessalamanca.es',
            'address' => 'Dirección pendiente de confirmar · Salamanca',
            'cta' => 'Pide presupuesto en 1 minuto',
        ];
    }
}
