# CHECKLIST DE IMPLEMENTACIÓN (prioridades)

## Preparación (P0)
- [ ] Confirmar datos legales: razón social, CIF, dirección, teléfono, email, WhatsApp.
- [x] Definir dominio y subdominio interno para desarrollo (`construcciones.local`).
- [x] Añadir credenciales `construcciones` a `apps/infra/.env` y `apps/infra/init.sh`; recrear o migrar DB si procede.
- [x] Crear server block en `apps/infra/nginx/conf.d` para apuntar al frontend/backend de este proyecto.
- [x] Crear rol y base de datos `construcciones` en PostgreSQL compartido.

## Infra y contenedores (P0)
- [x] Crear `docker-compose.yml` del proyecto con servicios frontend/backend (y worker si aplica) unidos a `shared-network` (external: true).
- [x] Variables `.env.example` y `.env` (DB host `shared-postgres-db`, redis `shared-redis`, puertos internos).
- [x] Scripts `make`/`npm`/`composer` para levantar, build y test.

## Backend Symfony (P1)
- [x] Scaffold Symfony 8 + estructura básica.
- [x] Configurar conexión Postgres (prod/stage) y Redis (cache/rate limit) en entorno real.
- [x] Entidades/migraciones: `Service`, `ContactLead`, `SiteSetting`, `Faq` (falta `Project` inactivo).
- [x] Módulos: `Contact` (validación + persistencia + notificación email), `Service`, `SiteContent`, `Faq`, `AdminAuth` (sesiones expirables 2h).
- [x] Arquitectura Hexagonal (Clean Architecture): Domain, Application, Infrastructure, Presentation layers.
- [x] CQRS implementado: Command Bus + Query Bus con Symfony Messenger.
- [x] Value Objects: Uuid, Email, Phone, DateTime en Domain.
- [x] Repository Pattern: Interfaces en Domain, implementaciones Doctrine en Infrastructure.
- [x] Domain Events: ContactLeadCreatedEvent con listener para notificaciones.
- [x] Endpoints públicos: `/health`, `/api/services`, `/api/services/{slug}`, `/api/site-settings/public`, `/api/faq`, `/api/contact` (con rate limit básico).
- [x] Endpoints admin: login, CRUD servicios, FAQ, site-settings.
- [x] Middleware de seguridad: CSRF admin, rate limit login/contacto, headers básicos.
- [x] Documentación: `backend/ARCHITECTURE.md` con explicación completa de la arquitectura.

### Backend - Pendientes
- [x] Endpoints DELETE para servicios y FAQ (CRUD completo) - implementado con CQRS + Domain Events.
- [ ] Tests unitarios: capa Domain (sin dependencias externas).
- [ ] Tests integración: Application con repositories mockeados.
- [ ] Tests funcional: endpoints con cliente HTTP.
- [x] PHPStan + CS Fixer configurados:
  - PHPStan nivel 8 con extensiones Symfony y Doctrine
  - PHP CS Fixer con reglas PSR-12 + PHP 8.4 migration
  - Scripts composer: `composer lint`, `composer fix:cs`
  - Comandos make: `make lint-phpstan`, `make lint-cs-check`, `make fix-cs`
  - Ignorados archivos generados (Kernel.php, var/, vendor/)
- [x] Logs estructurados (Monolog) para auditoría:
  - Canal 'audit' con formato JSON
  - Rotación de logs (30 días)
  - Servicio AuditLogger para eventos de negocio
  - Logs en: contact, login, CRUD servicios/FAQ/settings
- [x] Health checks avanzados: HealthCheckService con checks de DB, Redis, Mailer
  - Endpoint /health devuelve estado detallado + response times
  - Código HTTP 200 (healthy) o 503 (unhealthy)
  - Formato JSON con timestamp y estado de cada servicio

## Frontend Vue (P1)
- [x] Proyecto Vue 3 + TS + Vite; rutas: home one-page, alias `/contacto`, legales.
- [x] Sistema de diseño: tokens, base/utilities, layout main.
- [~] Componentes base separados (Button, Section, ServiceCard, ProcessStep, FAQItem, CTAStickyMobile) — faltan Icon/Tag dedicados y dividir más bloques.
- [~] Integración con API: servicios/FAQ/site-settings/contacto con fallback estático (falta estado de carga/toasts y manejo de errores avanzado).
- [~] Formulario contacto: validación básica y POST al backend; falta tracking eventos/estado de envío real.
- [x] Bloques home según `MAQUETA_HTML.md`: hero, razones, servicios, proceso, seguimiento diario, métricas (placeholder), FAQ, CTA final, footer.
- [x] Panel Admin: Login con manejo de sesión/CSRF (Pinia store + API client).
- [x] Panel Admin: Dashboard con navegación lateral y logout.
- [x] Panel Admin: CRUD Servicios con validación y modal.
- [x] Panel Admin: CRUD FAQ con validación.
- [x] Panel Admin: Configuración SiteSettings con preview.

### Frontend - Pendientes
- [x] Panel Admin: endpoints DELETE para servicios y FAQ (con CQRS commands).
- [x] Panel Admin: confirmación antes de eliminar registros (modal).
- [x] Panel Admin: toasts/notificaciones de éxito/error.
- [x] Panel Admin: guardar cambios sin salir:
  - Detectar cambios (isDirty) en Services, FAQ y Settings
  - Confirmación al cerrar modal con cambios pendientes
  - Advertencia beforeunload al intentar salir de la página
- [ ] Panel Admin: rich text editor para contenido HTML.
- [x] Frontend público: manejo de estados loading/error/toasts global (store ui + componente Toast).
- [ ] Frontend público: tracking eventos contacto (GTM/dataLayer).
- [x] Frontend público: estado de envío real del formulario (loading + notificaciones toast).
- [ ] Vitest: tests unitarios componentes clave.
- [ ] Playwright: flujo E2E contacto + login admin.
- [ ] ESLint/Prettier: pipeline configurada.

## Contenidos y SEO (P1)
- [~] Redactar copys finales hero/razones/proceso/seguimiento/FAQ (copys iniciales listos, falta validar con cliente).
- [x] Title/meta inicial home; H1 único en home; schema `LocalBusiness` estático en `index.html` (actualizar dominio/datos reales).
- [x] Sitemap y robots generados en build (static en `frontend/public`).
- [ ] Enlace a Google Reviews (placeholder hasta que exista).

### Contenidos - Pendientes
- [ ] Ajustar CSP cuando definamos dominio final del frontend.
- [ ] Analytics: configurar GA4/GTM con dominio real.
- [ ] Meta descripciones optimizadas por página.
- [ ] Open Graph tags para compartir en redes sociales.
- [ ] Schema.org ampliado: Service, FAQPage, BreadcrumbList.

## Testing y calidad (P2)
- [ ] Backend: tests unit/integration para `Contact` y `Service`.
- [ ] Frontend: Vitest componentes clave; Playwright/Cypress flujo contacto; axe smoke en home/contacto.
- [ ] Lint/format: PHPStan/CS Fixer, ESLint/Prettier, Stylelint si aplica.

### Testing - Pendientes específicos
- [ ] Cobertura tests backend > 70% (Domain + Application).
- [ ] Contract tests: API spec OpenAPI/Swagger.
- [ ] Mutación testing (Infection PHP).
- [ ] Lighthouse CI: presupuestos de performance.
- [ ] Axe accessibility: automatizado en pipeline.

## Seguridad y fiabilidad (P2)
- [x] Hash de contraseñas nativo (bcrypt en ADMIN_PASSWORD_HASH).
- [x] Sesiones expirables, invalidación de sesión.
- [x] Rate limit en login y `/api/contact`.
- [x] CSP, X-Frame-Options, Referrer-Policy, Permissions-Policy (en SecurityHeadersSubscriber).
- [ ] Logs básicos y health checks; backups DB (si gestionados externamente, documentar).

### Seguridad - Pendientes
- [ ] Auditoría OWASP ZAP o similar (automática en pipeline).
- [ ] Secrets management: mover contraseñas a Docker secrets o vault.
- [ ] WAF básico: rate limiting por IP en Nginx.
- [ ] Certificado SSL/TLS configurado.
- [ ] Backup automatizado de DB: estrategia y documentación.
- [ ] Disaster recovery: runbook básico.

## Despliegue y operación (P2)
- [ ] Pipeline CI/CD: lint + tests + build + imágenes Docker.
- [ ] Publicar imágenes y desplegar en entorno (staging/prod) usando `shared-network`.
- [ ] Verificación post-deploy: health, contacto, métricas básicas.

### CI/CD - Pendientes específicos
- [ ] GitHub Actions: workflow PR (lint + tests).
- [ ] GitHub Actions: workflow merge (build + push Docker Hub).
- [ ] Semantic versioning: tags automáticos.
- [ ] Changelog automático (conventional commits).
- [ ] Deploy blue/green o rolling update.
- [ ] Monitoreo: Uptime Robot o similar.
- [ ] Alertas: Slack/email en errores críticos.

## Roadmap posterior (P3)
- [ ] Landings por servicio/localidad y blog/guías.
- [ ] Activar módulo `Project` con portfolio real.
- [ ] Métricas reales y testimonios verificados.
- [ ] Optimización CWV y accesibilidad extendida.

## Ideas futuras (Backlog)
- [ ] Multi-idioma (i18n): español/inglés.
- [ ] Cache HTTP: ETags, Cache-Control en API.
- [ ] CDN para assets estáticos.
- [ ] Progressive Web App (PWA).
- [ ] Notificaciones push (nuevos contactos).
- [ ] Dashboard analítico: leads por día, origen, etc.
- [ ] Integración CRM: exportar leads a HubSpot/Salesforce.
- [ ] Chat en vivo: Tawk.to o similar.
