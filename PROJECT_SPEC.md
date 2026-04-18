# PROJECT SPEC – Reformas Salamanca (v2 2026-04-04)

Documento maestro para construir y hacer crecer la web corporativa de la empresa de reformas y construcción localizada en Salamanca.

## 1. Enfoque general
- Fase 1: sitio one-page (home con anclas) orientado a conversión local y sin portfolio propio.
- Fase 2: activar rutas adicionales (`/servicios/:slug`, `/zonas/:slug`, blog/guías) sin rehacer la base.
- Estrategia de confianza: transparencia, proceso claro, seguimiento diario y garantías; nunca mostrar obras ajenas ni casos inventados.

## 2. Objetivos de negocio
- Conseguir solicitudes de presupuesto cualificadas en Salamanca y alrededores.
- Convertir visitas en contactos: formulario, clic a teléfono, WhatsApp.
- Posicionamiento SEO local para servicios clave.
- Preparar el terreno para incorporar portfolio real cuando exista.

## 3. Propuesta de valor
- Reformas integrales y parciales con equipo propio multidisciplinar.
- Presupuesto en 72 h y plan de obra con hitos.
- Seguimiento diario: parte con texto, checklist y fotos enviado por WhatsApp/email.
- Penalización por retraso pactada en contrato.
- Limpieza y entrega final incluida; garantías por escrito; licencias gestionadas.

## 4. Restricciones de contenido
- Prohibido mostrar proyectos de terceros como propios.
- Testimonios solo si son verificables; mientras tanto, espacio preparado.
- Métricas internas permitidas (ej.: % obras en plazo, tiempos medios) siempre honestas.

## 5. Público objetivo
- Prioritario: particulares/familias en Salamanca y alrededores.
- Secundario: propietarios que preparan vivienda para alquiler/venta y pequeños negocios/locales.
- Miedos a resolver: retrasos, sobrecostes, mala comunicación, limpieza deficiente.

## 6. Arquitectura de información
- Fase 1 (one-page `/` con anclas): Hero, Razones, Servicios, Proceso, Seguimiento diario, Métricas/Testimonios, FAQ, CTA, Footer legal.
- Rutas mínimas adicionales: `/contacto` (ancla o alias), `/aviso-legal`, `/privacidad`, `/politica-cookies`.
- Fase 2 (extensión): `/servicios`, `/servicios/:slug`, `/nosotros`, `/proceso`, `/zonas/:slug`, `/blog/:slug`, `/reformas-<servicio>-salamanca`.

## 7. Contenidos a producir (fase 1)
- Hero: H1 “Reformas integrales en Salamanca con seguimiento diario”; subtítulo “Presupuesto en 72 h, penalización si nos retrasamos”.
- Razones (tarjetas): equipo propio, seguimiento diario, penalización por retraso, presupuesto claro, limpieza y entrega, garantía, licencias.
- Servicios (tarjetas + ancla detalle): integrales, cocinas, baños, locales/hostelería, cubiertas/fachadas, obra nueva unifamiliar (opcional).
- Proceso: Visita 24 h → Presupuesto 72 h → Plan de obra e hitos → Ejecución con parte diario → Entrega y limpieza.
- Seguimiento diario: explicación + mock del parte diario (fecha, avance %, checklist, fotos).
- Métricas y testimonios: placeholders hasta tener datos; enlazar Google Reviews cuando existan.
- FAQ: licencias, plazos, formas de pago/financiación, zona de servicio, garantía, limpieza final.
- CTA repetidos + teléfono local + WhatsApp.
- Footer: datos legales (razón social, CIF, dirección, email, teléfono), enlaces legales y Google Reviews.

## 8. SEO y analítica
- Title home: “Reformas integrales en Salamanca | [Marca]”; H1 único “Reformas en Salamanca”.
- Meta description orientada a servicio + zona + CTA.
- Schema `LocalBusiness` con dirección, teléfono, horario, área de servicio.
- Sitemap y robots en build; canónicas por ruta.
- Eventos: envío de contacto, clic teléfono, clic WhatsApp; consentimiento de cookies.

## 9. UX/UI
- Paleta: terracota (piedra Villamayor) + gris grafito + acento ocre.
- Tipografía: Manrope o Work Sans (títulos peso 700, cuerpo 400–500).
- Fondos con textura ligera/geométrica; iconos lineales; botones con borde suave y sombra sutil.
- Sticky CTA en móvil (llamar / WhatsApp / pedir presupuesto).
- Accesibilidad: contraste ≥ 4.5:1, foco visible, navegación por teclado.

## 10. Stack técnico
- Backend: Symfony 7, PHP 8.3, arquitectura hexagonal, Doctrine ORM/Migrations, PostgreSQL.
- Frontend: Vue 3, TypeScript, Vite, Vue Router, SCSS; Vitest + Playwright/Cypress.
- Infra compartida (sin duplicar servicios):
  - Red: usar la red externa `shared-network` definida en `apps/infra/docker-compose.yml`.
  - PostgreSQL: reutilizar `shared-postgres-db` (host interno `shared-postgres-db`); crear BD/usuario propios `construcciones` con contraseña dedicada. Añadir las vars en `apps/infra/.env` y crear rol/DB en `apps/infra/init.sh`.
  - Redis: reutilizar `shared-redis` en la misma red.
  - Proxy: `infra-nginx` será el punto de entrada 80/443; agregar `server` en `apps/infra/nginx/conf.d` apuntando a los contenedores frontend/backend de este proyecto por red interna.
  - Puertos: los servicios del proyecto no publican puertos externos; solo exponen internamente para el proxy.
  - Compose del proyecto: declarar solo frontend/backend (y worker si aplica) y unirlos a `shared-network` (`external: true`); sin levantar postgres/redis locales.

## 11. Módulos backend (fase 1)
- `Contact`: recibir leads, validar, rate limit, almacenar, notificar.
- `Service`: lista y detalle de servicios; campos SEO.
- `SiteContent`: textos globales (hero, razones, proceso, seguimiento, CTA, datos contacto).
- `Faq`: preguntas frecuentes.
- `AdminAuth`: login admin, sesiones con expiración, rate limit login.
- `Project`: preparado pero no expuesto hasta tener portfolio real.

## 12. API inicial
- Público: `GET /health`, `GET /api/services`, `GET /api/services/{slug}`, `GET /api/site-settings/public`, `GET /api/faq`, `POST /api/contact`.
- Admin: `POST /api/admin/login`, `GET/POST/PUT services`, `GET/POST/PUT faq`, `GET/PUT site-settings`.
- Futuro: endpoints de `Project` y landings adicionales.

## 13. Frontend
- Rutas iniciales: `/` (one-page), alias `/contacto`; legales estáticas.
- Estructura: `pages/`, `components/ui|layout|features/`, `services/` (HTTP), `stores/`, `styles/`, `router/`.
- Sistema de diseño en `styles/`: `tokens.scss`, `mixins.scss`, `reset.scss`, `base.scss`, `utilities.scss`, `main.scss`.
- Componentes base: `Button`, `Card`, `Section`, `Icon`, `Tag`, `CTAStickyMobile`, `FormField`, `Hero`, `ProcessSteps`, `ServiceGrid`, `FollowUpMock`, `FAQList`.

## 14. Datos y migraciones
- Entidades: `ContactLead`, `Service`, `SiteSetting`, `Faq`, `Project` (inactivo).
- Una migration por cambio de esquema; seeds de dev para servicios/faq/contenido base.
- Campos clave ContactLead: nombre, teléfono, email, localidad, servicio, mensaje, presupuesto opcional, plazo opcional, canal preferido, source_page, created_at, status.

## 15. Seguridad y cumplimiento
- Hash de contraseñas nativo (`password_hash`), sesiones expirables, protección CSRF en admin, rate limit en login y contacto.
- Validación y sanitización en backend y frontend.
- Cabeceras: CSP ajustada, X-Frame-Options SAMEORIGIN, Referrer-Policy strict-origin-when-cross-origin, Permissions-Policy mínima.
- Textos legales: aviso legal, privacidad, cookies; banner de cookies con consentimiento.

## 16. Performance y accesibilidad
- Objetivo CWV: LCP < 2.5s, CLS < 0.1.
- Lazy load de imágenes, code splitting por ruta, imágenes WebP/avif responsivas.
- Lighthouse/axe en home y contacto; foco visible; estados hover/focus/active/disabled.

## 17. Testing
- Backend: unit + integration de `Contact` y `Service`.
- Frontend: Vitest (componentes), Playwright/Cypress (flujo de contacto), smoke de accesibilidad (axe).
- Cobertura inicial objetivo: 70% frontend; contratos API básicos.

## 18. Observabilidad y fiabilidad
- Logs centralizados; alertas en errores 5xx y ratio de spam.
- Health checks; backups diarios de DB (retención 7 días); procedimiento de restauración documentado.

## 19. Roadmap
- Fase 1 (MVP público + admin básico): home one-page, servicios, FAQ, contacto, legales; API pública; admin para servicios/faq/site-content; rate limits; seeds dev.
- Fase 2 (SEO y contenido): landings por servicio/localidad, blog/guías, schema LocalBusiness completo, métricas y testimonios reales.
- Fase 3 (portfolio real): activar módulo `Project`, fichas con fotos propias, casos de estudio.
- Fase 4 (optimización): mejora CWV, automatización de analítica/consent mode, tests ampliados, hardening de seguridad.

## 20. Principios
- Honestidad comercial (sin inventar proyectos ni cifras).
- Diseño y código escalables desde el primer día.
- Conversión clara en móvil y escritorio.
- Preparación para crecer sin rehacer la base.
