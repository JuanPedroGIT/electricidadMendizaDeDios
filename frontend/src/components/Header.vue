<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue'
import { RouterLink } from 'vue-router'
import { PHONE_LINK, PHONE_DISPLAY, WHATSAPP_LINK, BRAND_NAME } from '../data/contact'

const isScrolled = ref(false)
const isMobileMenuOpen = ref(false)

const handleScroll = () => {
  isScrolled.value = window.scrollY > 50
}

onMounted(() => {
  window.addEventListener('scroll', handleScroll)
})

onUnmounted(() => {
  window.removeEventListener('scroll', handleScroll)
})

const navLinks = [
  { name: 'Inicio', href: '#inicio' },
  { name: 'Servicios', href: '#servicios' },
  { name: 'Nosotros', href: '#nosotros' },
  { name: 'Proyectos', href: '#proyectos' },
  { name: 'Blog', href: '#blog' },
  { name: 'Contacto', href: '#contacto' },
]
</script>

<template>
  <header class="header" :class="{ scrolled: isScrolled }">
    <div class="container">
      <div class="top-bar">
        <RouterLink to="/" class="brand">
          <div class="brand-mark">EM</div>
          <div class="brand-text">
            <span class="brand-name">{{ BRAND_NAME }}</span>
            <span class="brand-tagline">Instalaciones Eléctricas</span>
          </div>
        </RouterLink>

        <nav class="nav-links">
          <a v-for="link in navLinks" :key="link.name" :href="link.href">{{ link.name }}</a>
        </nav>

        <div class="nav-actions">
          <a :href="PHONE_LINK" class="phone-link">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
            </svg>
            <span>{{ PHONE_DISPLAY }}</span>
          </a>
          <a :href="WHATSAPP_LINK" target="_blank" rel="noreferrer" class="btn btn-accent">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
              <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.008-.57-.008-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
            </svg>
            WhatsApp
          </a>

          <button class="mobile-menu-btn" @click="isMobileMenuOpen = !isMobileMenuOpen">
            <svg v-if="!isMobileMenuOpen" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <line x1="3" y1="12" x2="21" y2="12"></line>
              <line x1="3" y1="6" x2="21" y2="6"></line>
              <line x1="3" y1="18" x2="21" y2="18"></line>
            </svg>
            <svg v-else xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <line x1="18" y1="6" x2="6" y2="18"></line>
              <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
          </button>
        </div>
      </div>
    </div>

    <!-- Mobile Menu -->
    <div v-if="isMobileMenuOpen" class="mobile-menu">
      <div class="container">
        <nav class="mobile-nav">
          <a v-for="link in navLinks" :key="link.name" :href="link.href" @click="isMobileMenuOpen = false">
            {{ link.name }}
          </a>
          <a :href="PHONE_LINK" class="mobile-phone">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
            </svg>
            {{ PHONE_DISPLAY }}
          </a>
        </nav>
      </div>
    </div>
  </header>
</template>

<style scoped>
.header {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  z-index: 100;
  background: rgba(255, 255, 255, 0.95);
  backdrop-filter: blur(10px);
  border-bottom: 1px solid rgba(0, 0, 0, 0.05);
  transition: all 0.3s ease;
}

.header.scrolled {
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
}

.top-bar {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: var(--space-4) 0;
  gap: var(--space-4);
}

.brand {
  display: flex;
  align-items: center;
  gap: var(--space-3);
  text-decoration: none;
}

.brand-mark {
  width: 44px;
  height: 44px;
  border-radius: var(--radius-md);
  background: linear-gradient(135deg, var(--primary-700), var(--primary-500));
  display: grid;
  place-items: center;
  color: var(--white);
  font-weight: 800;
  font-size: 1.1rem;
  box-shadow: 0 8px 20px rgba(45, 106, 79, 0.3);
}

.brand-text {
  display: flex;
  flex-direction: column;
  gap: 2px;
}

.brand-name {
  font-size: 1.125rem;
  font-weight: 700;
  line-height: 1.2;
  color: var(--graphite-900);
}

.brand-tagline {
  font-size: 0.75rem;
  color: var(--text-muted);
  font-weight: 500;
}

.nav-links {
  display: flex;
  gap: var(--space-8);
  align-items: center;
}

.nav-links a {
  font-weight: 500;
  color: var(--text-secondary);
  padding: var(--space-2) 0;
  position: relative;
  transition: color 0.2s ease;
  text-decoration: none;
}

.nav-links a::after {
  content: '';
  position: absolute;
  bottom: 0;
  left: 0;
  width: 0;
  height: 2px;
  background: var(--primary-500);
  transition: width 0.3s ease;
}

.nav-links a:hover {
  color: var(--primary-700);
}

.nav-links a:hover::after {
  width: 100%;
}

.nav-actions {
  display: flex;
  align-items: center;
  gap: var(--space-3);
}

.phone-link {
  display: flex;
  align-items: center;
  gap: var(--space-2);
  color: var(--graphite-700);
  font-weight: 600;
  padding: var(--space-2) var(--space-4);
  border-radius: var(--radius-md);
  transition: background 0.2s ease;
}

.phone-link:hover {
  background: var(--graphite-50);
}

.mobile-menu-btn {
  display: none;
  background: none;
  border: none;
  cursor: pointer;
  color: var(--graphite-900);
  padding: var(--space-2);
}

.mobile-menu {
  display: none;
  border-top: 1px solid rgba(0, 0, 0, 0.05);
  background: var(--white);
}

.mobile-nav {
  display: flex;
  flex-direction: column;
  padding: var(--space-4) 0;
}

.mobile-nav a {
  padding: var(--space-4) 0;
  color: var(--graphite-900);
  font-weight: 500;
  border-bottom: 1px solid var(--graphite-100);
}

.mobile-phone {
  display: flex !important;
  align-items: center;
  gap: var(--space-2);
  color: var(--primary-700) !important;
  font-weight: 600 !important;
}

@media (max-width: 1024px) {
  .nav-links {
    gap: var(--space-5);
  }

  .phone-link span {
    display: none;
  }
}

@media (max-width: 768px) {
  .nav-links {
    display: none;
  }

  .mobile-menu-btn {
    display: block;
  }

  .mobile-menu {
    display: block;
  }

  .phone-link {
    display: none;
  }
}
</style>
