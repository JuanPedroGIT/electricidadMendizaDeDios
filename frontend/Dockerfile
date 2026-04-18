# ETAPA 1: Construcción (Build)
FROM node:20-alpine as build-stage
WORKDIR /app
COPY package*.json ./
RUN npm install
COPY . .
RUN npm run build

# ETAPA 2: Producción (Nginx)
FROM nginx:stable-alpine as prod-stage
# Copiamos el build de la etapa anterior
COPY --from=build-stage /app/dist /usr/share/nginx/html
# Copiamos una configuración mínima de nginx para SPA (opcional pero recomendado)
EXPOSE 80
CMD ["nginx", "-g", "daemon off;"]