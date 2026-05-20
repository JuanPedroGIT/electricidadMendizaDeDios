<?php

declare(strict_types=1);

namespace App\Infrastructure\Data;

final class SiteContent
{
    public static function services(): array
    {
        return [
            [
                'slug' => 'instalaciones-electricas',
                'name' => 'Instalaciones Eléctricas',
                'summary' => 'Instalaciones eléctricas completas para viviendas, locales y oficinas con normativa vigente.',
                'image' => 'https://images.unsplash.com/photo-1621905251189-08b45d6a269e?w=800&h=600&fit=crop',
                'benefits' => ['Normativa vigente', 'Presupuesto sin compromiso', 'Garantía de instalación'],
            ],
            [
                'slug' => 'cuadros-electricos',
                'name' => 'Cuadros Eléctricos',
                'summary' => 'Montaje, mantenimiento y actualización de cuadros eléctricos y protecciones.',
                'image' => 'https://images.unsplash.com/photo-1635335874521-7987db781153?w=800&h=600&fit=crop',
                'benefits' => ['Protección garantizada', 'Materiales certificados', 'Revisión periódica'],
            ],
            [
                'slug' => 'iluminacion-led',
                'name' => 'Iluminación LED',
                'summary' => 'Proyectos de iluminación eficiente, ahorro energético y domótica básica.',
                'image' => 'https://images.unsplash.com/photo-1565008447742-97f6f38c985c?w=800&h=600&fit=crop',
                'benefits' => ['Ahorro energético', 'Larga duración', 'Diseño personalizado'],
            ],
            [
                'slug' => 'mantenimiento-industrial',
                'name' => 'Mantenimiento Industrial',
                'summary' => 'Servicio técnico especializado para naves industriales y maquinaria eléctrica.',
                'image' => 'https://images.unsplash.com/photo-1596986952526-3be237187071?w=800&h=600&fit=crop',
                'benefits' => ['Reducción de paradas', 'Mantenimiento preventivo', 'Técnicos especializados'],
            ],
            [
                'slug' => 'sistemas-seguridad',
                'name' => 'Sistemas de Seguridad',
                'summary' => 'Instalación de alarmas, videovigilancia y control de accesos eléctricos.',
                'image' => 'https://images.unsplash.com/photo-1704737825103-3fd8cedf3cc0?w=800&h=600&fit=crop',
                'benefits' => ['Vigilancia 24h', 'Instalación discreta', 'Conexión a central'],
            ],
            [
                'slug' => 'boletines-electricos',
                'name' => 'Boletines Eléctricos',
                'summary' => 'Gestión de boletines, certificados de instalación y legalizaciones.',
                'image' => 'https://images.unsplash.com/photo-1597502310092-31cdaa35b46d?w=800&h=600&fit=crop',
                'benefits' => ['Trámite completo', 'Legalización rápida', 'Documentación incluida'],
            ],
            [
                'slug' => 'energia-solar',
                'name' => 'Energía Solar',
                'summary' => 'Asesoramiento e instalación de paneles fotovoltaicos para autoconsumo.',
                'image' => 'https://images.unsplash.com/photo-1635424824849-1b09bdcc55b1?w=800&h=600&fit=crop',
                'benefits' => ['Ahorro en factura', 'Energía limpia', 'Amortización garantizada'],
            ],
            [
                'slug' => 'urgencias-24h',
                'name' => 'Urgencias 24h',
                'summary' => 'Servicio rápido de reparación de averías eléctricas en toda Salamanca.',
                'image' => 'https://images.unsplash.com/photo-1621905251189-08b45d6a269e?w=800&h=600&fit=crop',
                'benefits' => ['Disponibilidad 24h', 'Respuesta rápida', 'Todas las zonas'],
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
