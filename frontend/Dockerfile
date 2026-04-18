FROM node:20-alpine
WORKDIR /app

# Instalamos deps (no hay lock, usamos npm install)
COPY package.json ./
RUN npm install

COPY . /app
EXPOSE 4173
CMD ["npm","run","dev","--","--host","0.0.0.0","--port","4173"]
