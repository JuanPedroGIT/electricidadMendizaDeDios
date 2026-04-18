# Construcciones Salamanca

Estructura inicial del proyecto. Usa infraestructura compartida (`apps/infra`) con PostgreSQL, Redis y Nginx reverse proxy.

## Servicios
- Backend PHP-FPM (`constru-backend-php`) + Nginx (`constru-backend-nginx`), sin puertos publicados, en red `shared-network`.
- Frontend Vite (`constru-frontend`) sirviendo en 4173 solo para red interna.

## Pasos rápidos
1. Copia `.env.example` a `.env` y ajusta secretos.
2. En `apps/infra/.env` añade `CONSTRUCCIONES_DB_PASS` y levanta infra: `cd ../infra && docker compose up -d`.
3. Dentro del proyecto: `docker compose up -d --build` (tras instalar deps backend/frontend).
4. Añade server block en `apps/infra/nginx/conf.d` apuntando a `constru-frontend:4173` y `constru-backend-nginx:80`.

## Próximos
- Backend Symfony 8 con endpoints públicos `/health`, `/api/services`, `/api/site-settings/public`, `/api/faq`, `/api/contact` (persistencia en Postgres).
- Frontend Vue 3 + TS + Vite con landing one-page y rutas legales.
- Completar bloques y API según `PROJECT_SPEC.md` y `MAQUETA_HTML.md`.

## Backend rápido
1. Instalar dependencias (usa Docker si no tienes PHP 8.4):\
   `cd backend && docker run --rm -v ${PWD}:/app -w /app php:8.4-cli sh -lc "apt-get update >/dev/null && apt-get install -y git unzip libzip-dev >/dev/null && docker-php-ext-install zip >/dev/null && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer >/dev/null && composer install"`
2. Ejecutar migraciones: `docker compose exec constru-backend-php php bin/console doctrine:migrations:migrate -n`
3. Levantar proyecto: `docker compose up -d --build`

Rate limit contacto: 5 req / 10 min por IP (config en `config/packages/rate_limiter.yaml`; en prod usar storage Redis).

Makefile: usa `make backend-install`, `make frontend-install`, `make up`, `make migrate`.
