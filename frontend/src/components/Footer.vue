<script setup lang="ts">
import { RouterLink } from 'vue-router'
import { PHONE_LINK, PHONE_DISPLAY, WHATSAPP_LINK, EMAIL, ADDRESS, LEGAL_NAME } from '../data/contact'
</script>

<template>
  <footer class="footer">
    <div class="container">
      <div class="footer-grid">
        <div class="footer-brand">
          <div class="brand" style="color: #fff;">
            <div class="brand-mark">EM</div>
            <div class="brand-text">
              <span class="brand-name">Electricidad Mendoza de Dios</span>
              <span class="brand-tagline">Instalaciones Eléctricas</span>
            </div>
          </div>
          <p>Más de 20 años de experiencia en instalaciones eléctricas en Salamanca. Calidad, servicio y precio razonable.</p>
          <div class="footer-social">
            <a href="#" aria-label="Facebook">
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
              </svg>
            </a>
            <a href="#" aria-label="Instagram">
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
              </svg>
            </a>
            <a :href="WHATSAPP_LINK" target="_blank" rel="noreferrer" aria-label="WhatsApp">
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.008-.57-.008-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
              </svg>
            </a>
          </div>
        </div>

        <div class="footer-column">
          <h4>Servicios</h4>
          <nav class="footer-links">
            <a href="#servicios">Reformas integrales</a>
            <a href="#servicios">Cocinas</a>
            <a href="#servicios">Baños</a>
            <a href="#servicios">Obra nueva</a>
            <a href="#servicios">Fachadas</a>
            <a href="#servicios">Cubiertas</a>
          </nav>
        </div>

        <div class="footer-column">
          <h4>Empresa</h4>
          <nav class="footer-links">
            <a href="#nosotros">Sobre nosotros</a>
            <a href="#proyectos">Proyectos</a>
            <a href="#blog">Blog</a>
            <a href="#contacto">Contacto</a>
          </nav>
        </div>

        <div class="footer-column footer-contact">
          <h4>Contacto</h4>
          <p>
            <strong>Teléfono</strong>
            <a :href="PHONE_LINK">{{ PHONE_DISPLAY }}</a>
          </p>
          <p>
            <strong>Email</strong>
            <a :href="`mailto:${EMAIL}`">{{ EMAIL }}</a>
          </p>
          <p>
            <strong>Dirección</strong>
            {{ ADDRESS }}
          </p>
        </div>
      </div>

      <div class="footer-bottom">
        <p>&copy; {{ new Date().getFullYear() }} {{ LEGAL_NAME }}. Todos los derechos reservados.</p>
        <nav class="footer-legal">
          <RouterLink to="/aviso-legal">Aviso Legal</RouterLink>
          <RouterLink to="/privacidad">Privacidad</RouterLink>
          <RouterLink to="/politica-cookies">Cookies</RouterLink>
        </nav>
      </div>
    </div>
  </footer>
</template>

<style scoped>
.footer {
  background: var(--graphite-900);
  color: var(--white);
  padding: var(--space-16) 0 var(--space-8);
  margin-top: auto;
}

.footer-grid {
  display: grid;
  grid-template-columns: 2fr 1fr 1fr 1.5fr;
  gap: var(--space-12);
  margin-bottom: var(--space-12);
}

.footer-brand {
  display: flex;
  flex-direction: column;
  gap: var(--space-4);
}

.footer-brand .brand {
  display: flex;
  align-items: center;
  gap: var(--space-3);
}

.footer-brand .brand-mark {
  width: 44px;
  height: 44px;
  border-radius: var(--radius-md);
  background: linear-gradient(135deg, var(--primary-500), var(--primary-300));
  display: grid;
  place-items: center;
  color: var(--white);
  font-weight: 800;
  font-size: 1.1rem;
}

.footer-brand .brand-text {
  display: flex;
  flex-direction: column;
  gap: 2px;
}

.footer-brand .brand-name {
  font-size: 1.125rem;
  font-weight: 700;
  line-height: 1.2;
  color: var(--white);
}

.footer-brand .brand-tagline {
  font-size: 0.75rem;
  color: rgba(255, 255, 255, 0.6);
  font-weight: 500;
}

.footer-brand p {
  color: rgba(255, 255, 255, 0.7);
  margin: 0;
  max-width: 300px;
}

.footer-social {
  display: flex;
  gap: var(--space-3);
  margin-top: var(--space-2);
}

.footer-social a {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  background: rgba(255, 255, 255, 0.1);
  display: grid;
  place-items: center;
  color: var(--white);
  transition: all 0.2s ease;
}

.footer-social a:hover {
  background: var(--primary-500);
  transform: translateY(-2px);
}

.footer-column h4 {
  color: var(--white);
  margin-bottom: var(--space-5);
  font-size: 1rem;
  font-weight: 600;
}

.footer-links {
  display: flex;
  flex-direction: column;
  gap: var(--space-3);
}

.footer-links a {
  color: rgba(255, 255, 255, 0.7);
  transition: color 0.2s ease;
  font-size: 0.9375rem;
}

.footer-links a:hover {
  color: var(--accent-400);
}

.footer-contact p {
  color: rgba(255, 255, 255, 0.7);
  margin-bottom: var(--space-4);
  font-size: 0.9375rem;
}

.footer-contact strong {
  color: var(--white);
  display: block;
  margin-bottom: var(--space-1);
  font-weight: 600;
}

.footer-contact a {
  color: rgba(255, 255, 255, 0.7);
  transition: color 0.2s ease;
}

.footer-contact a:hover {
  color: var(--accent-400);
}

.footer-bottom {
  border-top: 1px solid rgba(255, 255, 255, 0.1);
  padding-top: var(--space-6);
  display: flex;
  justify-content: space-between;
  align-items: center;
  flex-wrap: wrap;
  gap: var(--space-4);
}

.footer-bottom p {
  color: rgba(255, 255, 255, 0.5);
  margin: 0;
  font-size: 0.875rem;
}

.footer-legal {
  display: flex;
  gap: var(--space-6);
}

.footer-legal a {
  color: rgba(255, 255, 255, 0.5);
  font-size: 0.875rem;
  transition: color 0.2s ease;
}

.footer-legal a:hover {
  color: var(--white);
}

@media (max-width: 1024px) {
  .footer-grid {
    grid-template-columns: repeat(2, 1fr);
    gap: var(--space-8);
  }
}

@media (max-width: 640px) {
  .footer-grid {
    grid-template-columns: 1fr;
  }

  .footer-bottom {
    flex-direction: column;
    text-align: center;
  }

  .footer-legal {
    gap: var(--space-4);
  }
}
</style>
