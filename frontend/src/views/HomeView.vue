<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { RouterLink } from 'vue-router'
import Header from '../components/Header.vue'
import Footer from '../components/Footer.vue'
import {
  BRAND_NAME,
  PHONE_DISPLAY,
  PHONE_LINK,
  WHATSAPP_LINK,
  EMAIL,
  ADDRESS,
} from '../data/contact'

// Services data with free images
const services = [
  {
    id: 1,
    name: 'Instalaciones Eléctricas',
    description: 'Instalaciones eléctricas completas para viviendas, locales y oficinas con normativa vigente.',
    image: 'https://images.unsplash.com/photo-1621905251189-08b45d6a269e?w=800&h=600&fit=crop',
    link: '#servicios',
  },
  {
    id: 2,
    name: 'Cuadros Eléctricos',
    description: 'Montaje, mantenimiento y actualización de cuadros eléctricos y protecciones.',
    image: 'https://images.unsplash.com/photo-1558389400-99777677b12d?w=800&h=600&fit=crop',
    link: '#servicios',
  },
  {
    id: 3,
    name: 'Iluminación LED',
    description: 'Proyectos de iluminación eficiente, ahorro energético y domótica básica.',
    image: 'https://images.unsplash.com/photo-1565008447742-97f6f38c985c?w=800&h=600&fit=crop',
    link: '#servicios',
  },
  {
    id: 4,
    name: 'Mantenimiento Industrial',
    description: 'Servicio técnico especializado para naves industriales y maquinaria eléctrica.',
    image: 'https://images.unsplash.com/photo-1581091229493-c07766f9759b?w=800&h=600&fit=crop',
    link: '#servicios',
  },
  {
    id: 5,
    name: 'Sistemas de Seguridad',
    description: 'Instalación de alarmas, videovigilancia y control de accesos eléctricos.',
    image: 'https://images.unsplash.com/photo-1557597774-9d557677f17b?w=800&h=600&fit=crop',
    link: '#servicios',
  },
  {
    id: 6,
    name: 'Boletines Eléctricos',
    description: 'Gestión de boletines, certificados de instalación y legalizaciones.',
    image: 'https://images.unsplash.com/photo-1454165833767-027ff9597e9d?w=800&h=600&fit=crop',
    link: '#servicios',
  },
  {
    id: 7,
    name: 'Energía Solar',
    description: 'Asesoramiento e instalación de paneles fotovoltaicos para autoconsumo.',
    image: 'https://images.unsplash.com/photo-1508514177221-7576a4739736?w=800&h=600&fit=crop',
    link: '#servicios',
  },
  {
    id: 8,
    name: 'Urgencias 24h',
    description: 'Servicio rápido de reparación de averías eléctricas en toda Salamanca.',
    image: 'https://images.unsplash.com/photo-1621905251189-08b45d6a269e?w=800&h=600&fit=crop',
    link: '#servicios',
  },
]

// Stats data
const stats = [
  { value: '20+', label: 'Años de Experiencia' },
  { value: '500+', label: 'Proyectos Completados' },
  { value: '98%', label: 'Clientes Satisfechos' },
  { value: '15', label: 'Profesionales en Plantilla' },
]

// Projects data
const projects = [
  {
    id: 1,
    title: 'Cuadro eléctrico industrial',
    category: 'Cuadros Eléctricos',
    image: 'https://images.unsplash.com/photo-1558389400-99777677b12d?w=600&h=400&fit=crop',
  },
  {
    id: 2,
    title: 'Iluminación LED en oficina',
    category: 'Iluminación LED',
    image: 'https://images.unsplash.com/photo-1565008447742-97f6f38c985c?w=600&h=400&fit=crop',
  },
  {
    id: 3,
    title: 'Instalación solar fotovoltaica',
    category: 'Energía Solar',
    image: 'https://images.unsplash.com/photo-1508514177221-7576a4739736?w=600&h=400&fit=crop',
  },
  {
    id: 4,
    title: 'Mantenimiento preventivo nave',
    category: 'Mantenimiento Industrial',
    image: 'https://images.unsplash.com/photo-1581091229493-c07766f9759b?w=600&h=400&fit=crop',
  },
]

// FAQ data
const faqs = [
  {
    question: '¿Cuál es el tiempo estimado para una instalación eléctrica?',
    answer: 'Depende de la magnitud del proyecto. Una instalación básica de vivienda puede llevar unos pocos días, mientras que proyectos industriales requieren más tiempo. Te proporcionamos un presupuesto y calendario detallado.',
  },
  {
    question: '¿Gestionáis los boletines y legalizaciones?',
    answer: 'Sí, nos encargamos de toda la gestión técnica, firma de boletines y trámites con la distribuidora eléctrica para asegurar que tu instalación sea legal y segura.',
  },
  {
    question: '¿En qué zonas trabajáis?',
    answer: 'Trabajamos principalmente en Salamanca capital y alfoz (Santa Marta, Carbajosa, Villamayor, Cabrerizos, etc.). Para otras zonas, consúltanos sin compromiso.',
  },
  {
    question: '¿Cómo se realiza el pago de la instalación?',
    answer: 'Se pide una señal al aceptar el presupuesto para la compra de materiales, y el resto se abona según el avance de la obra y tras la entrega final con la certificación.',
  },
  {
    question: '¿Atendéis urgencias eléctricas?',
    answer: 'Sí, contamos con un servicio de urgencias para averías eléctricas graves que requieren intervención inmediata en toda la zona de Salamanca.',
  },
]

// Form state
const form = ref({
  name: '',
  phone: '',
  email: '',
  service: '',
  message: '',
  privacy: false,
})

const isSubmitting = ref(false)
const submitSuccess = ref(false)
const submitError = ref('')

const handleSubmit = async () => {
  isSubmitting.value = true
  submitError.value = ''
  submitSuccess.value = false

  try {
    // Simulate API call
    await new Promise((resolve) => setTimeout(resolve, 1500))
    submitSuccess.value = true
    form.value = {
      name: '',
      phone: '',
      email: '',
      service: '',
      message: '',
      privacy: false,
    }
  } catch (error) {
    submitError.value = 'Ha ocurrido un error. Por favor, inténtalo de nuevo.'
  } finally {
    isSubmitting.value = false
  }
}

// Scroll animations
onMounted(() => {
  const observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          entry.target.classList.add('animate-visible')
        }
      })
    },
    { threshold: 0.1 }
  )

  document.querySelectorAll('.animate-on-scroll').forEach((el) => {
    observer.observe(el)
  })
})
</script>

<template>
  <div class="page">
    <Header />

    <main>
      <!-- Hero Section -->
      <section id="inicio" class="hero">
        <div class="container">
          <div class="hero-content">
            <div class="hero-badge">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
              </svg>
              Más de 20 años de experiencia
            </div>
            <h1 class="hero-title">
              Electricidad en <span>Salamanca</span><br />con calidad contrastada
            </h1>
            <p class="hero-subtitle">
              Instalaciones eléctricas para hogares, comunidades e industrias. Presupuesto sin compromiso,
              visita técnica gratuita y garantía de satisfacción.
            </p>
            <div class="hero-stats">
              <div class="hero-stat">
                <span class="hero-stat-number">20+</span>
                <span class="hero-stat-label">Años de experiencia</span>
              </div>
              <div class="hero-stat">
                <span class="hero-stat-number">500+</span>
                <span class="hero-stat-label">Proyectos realizados</span>
              </div>
              <div class="hero-stat">
                <span class="hero-stat-number">98%</span>
                <span class="hero-stat-label">Clientes satisfechos</span>
              </div>
            </div>
            <div class="hero-cta">
              <a href="#contacto" class="btn btn-accent">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                  <line x1="16" y1="2" x2="16" y2="6"/>
                  <line x1="8" y1="2" x2="8" y2="6"/>
                  <line x1="3" y1="10" x2="21" y2="10"/>
                </svg>
                Pide tu presupuesto
              </a>
              <a :href="WHATSAPP_LINK" target="_blank" rel="noreferrer" class="btn btn-ghost">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.008-.57-.008-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                </svg>
                WhatsApp directo
              </a>
            </div>
          </div>
        </div>
      </section>

      <!-- About Section -->
      <section id="nosotros" class="section about-section">
        <div class="container">
          <div class="about-grid">
            <div class="about-image animate-on-scroll">
              <img
                src="https://images.unsplash.com/photo-1504307651254-35680f356dfd?w=800&h=600&fit=crop"
                alt="Equipo de Electricidad Mendoza de Dios trabajando"
              />
            </div>
            <div class="about-content animate-on-scroll">
              <div class="section-kicker">Sobre Nosotros</div>
              <h2>Diseñamos para mejorar hogares, comunidades e industrias</h2>
              <p>
                En {{ BRAND_NAME }} llevamos más de dos décadas ofreciendo soluciones eléctricas
                e instalaciones en Salamanca. Nuestro objetivo es dar satisfacción al cliente con un trabajo
                de calidad a un precio razonable.
              </p>
              <p>
                Cualquier instalación eléctrica, nosotros la realizamos. Nos diferenciamos por
                nuestra atención al detalle, el cumplimiento de plazos y la transparencia en cada
                etapa del proyecto.
              </p>
              <div class="about-features">
                <div class="about-feature">
                  <div class="about-feature-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <path d="M12 2L2 7l10 5 10-5-10-5z"/>
                      <path d="M2 17l10 5 10-5"/>
                      <path d="M2 12l10 5 10-5"/>
                    </svg>
                  </div>
                  <div>
                    <h4>Proyectos Profesionales</h4>
                    <p>Desarrollamos cada proyecto con planificación detallada y ejecución impecable.</p>
                  </div>
                </div>
                <div class="about-feature">
                  <div class="about-feature-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                    </svg>
                  </div>
                  <div>
                    <h4>Garantía de Calidad</h4>
                    <p>Todos nuestros trabajos cuentan con garantía y seguro de responsabilidad civil.</p>
                  </div>
                </div>
                <div class="about-feature">
                  <div class="about-feature-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <circle cx="12" cy="12" r="10"/>
                      <polyline points="12 6 12 12 16 14"/>
                    </svg>
                  </div>
                  <div>
                    <h4>Cumplimiento de Plazos</h4>
                    <p>Respetamos los tiempos acordados con control semanal del avance de obra.</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- Services Section -->
      <section id="servicios" class="section services-section">
        <div class="container">
          <div class="section-header animate-on-scroll">
            <div class="section-kicker">Nuestros Servicios</div>
            <h2>¿Qué hacemos?</h2>
            <p>Ofrecemos soluciones integrales para todo tipo de proyectos eléctricos e instalaciones.</p>
          </div>
          <div class="services-grid">
            <article v-for="service in services" :key="service.id" class="service-card animate-on-scroll">
              <div class="service-card-image">
                <img :src="service.image" :alt="service.name" loading="lazy" />
              </div>
              <div class="service-card-content">
                <h3>{{ service.name }}</h3>
                <p>{{ service.description }}</p>
                <a :href="service.link" class="service-card-link">
                  Ver más
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="5" y1="12" x2="19" y2="12"/>
                    <polyline points="12 5 19 12 12 19"/>
                  </svg>
                </a>
              </div>
            </article>
          </div>
        </div>
      </section>

      <!-- Stats Section -->
      <section class="stats-section">
        <div class="container">
          <div class="stats-grid">
            <div v-for="stat in stats" :key="stat.label" class="stat-item animate-on-scroll">
              <div class="stat-number">{{ stat.value }}</div>
              <div class="stat-label">{{ stat.label }}</div>
            </div>
          </div>
        </div>
      </section>

      <!-- Projects Section -->
      <section id="proyectos" class="section projects-section">
        <div class="container">
          <div class="section-header animate-on-scroll">
            <div class="section-kicker">Últimos Proyectos</div>
            <h2>Nuestros trabajos recientes</h2>
            <p>Algunos ejemplos de proyectos que hemos completado con éxito.</p>
          </div>
          <div class="projects-grid">
            <article v-for="project in projects" :key="project.id" class="project-card animate-on-scroll">
              <div class="project-card-image">
                <img :src="project.image" :alt="project.title" loading="lazy" />
                <div class="project-card-overlay">
                  <span class="project-card-category">{{ project.category }}</span>
                  <h3>{{ project.title }}</h3>
                  <a href="#contacto" class="btn btn-primary btn-sm">Ver proyecto</a>
                </div>
              </div>
            </article>
          </div>
        </div>
      </section>

      <!-- Why Choose Us Section -->
      <section class="section why-section">
        <div class="container">
          <div class="section-header animate-on-scroll">
            <div class="section-kicker">¿Por qué elegirnos?</div>
            <h2>Comprometidos con la excelencia</h2>
          </div>
          <div class="why-grid">
            <div class="why-card animate-on-scroll">
              <div class="why-card-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <circle cx="12" cy="8" r="7"/>
                  <polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"/>
                </svg>
              </div>
              <h3>Calidad Garantizada</h3>
              <p>Trabajamos con los mejores materiales y profesionales del sector.</p>
            </div>
            <div class="why-card animate-on-scroll">
              <div class="why-card-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <circle cx="12" cy="12" r="10"/>
                  <polyline points="12 6 12 12 16 14"/>
                </svg>
              </div>
              <h3>Puntualidad</h3>
              <p>Cumplimos los plazos acordados con seguimiento constante.</p>
            </div>
            <div class="why-card animate-on-scroll">
              <div class="why-card-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                </svg>
              </div>
              <h3>Seguridad</h3>
              <p>Seguro de responsabilidad civil y cumplimiento de normativas.</p>
            </div>
            <div class="why-card animate-on-scroll">
              <div class="why-card-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                </svg>
              </div>
              <h3>Comunicación</h3>
              <p>Información constante del avance de obra por WhatsApp o email.</p>
            </div>
          </div>
        </div>
      </section>

      <!-- FAQ Section -->
      <section id="faq" class="section faq-section">
        <div class="container">
          <div class="section-header animate-on-scroll">
            <div class="section-kicker">Preguntas Frecuentes</div>
            <h2>Resolvemos tus dudas</h2>
          </div>
          <div class="faq-list">
            <details v-for="(faq, index) in faqs" :key="index" class="faq-item animate-on-scroll">
              <summary>{{ faq.question }}</summary>
              <div class="faq-item-content">
                <p>{{ faq.answer }}</p>
              </div>
            </details>
          </div>
        </div>
      </section>

      <!-- CTA Section -->
      <section class="cta-section">
        <div class="container">
          <div class="cta-content">
            <h2>¿Necesitas un presupuesto?</h2>
            <p>Ponte en contacto con nosotros o ven a hacernos una visita. Sin compromiso, te entregamos el mejor presupuesto adaptado a tus necesidades.</p>
            <div class="cta-buttons">
              <a href="#contacto" class="btn btn-accent btn-lg">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                  <line x1="16" y1="2" x2="16" y2="6"/>
                  <line x1="8" y1="2" x2="8" y2="6"/>
                  <line x1="3" y1="10" x2="21" y2="10"/>
                </svg>
                Solicitar presupuesto
              </a>
              <a :href="`tel:${PHONE_LINK.replace('tel:', '')}`" class="btn btn-ghost btn-lg">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                </svg>
                {{ PHONE_DISPLAY }}
              </a>
            </div>
          </div>
        </div>
      </section>

      <!-- Contact Section -->
      <section id="contacto" class="section contact-section">
        <div class="container">
          <div class="contact-grid">
            <div class="contact-info animate-on-scroll">
              <div class="section-kicker">Contacto</div>
              <h2>Hablemos de tu proyecto</h2>
              <p>Rellena el formulario y nos pondremos en contacto contigo en menos de 24 horas laborables.</p>
              <div class="contact-details">
                <div class="contact-item">
                  <div class="contact-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                    </svg>
                  </div>
                  <div>
                    <h4>Teléfono</h4>
                    <p>{{ PHONE_DISPLAY }}</p>
                  </div>
                </div>
                <div class="contact-item">
                  <div class="contact-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                      <polyline points="22,6 12,13 2,6"/>
                    </svg>
                  </div>
                  <div>
                    <h4>Email</h4>
                    <p>{{ EMAIL }}</p>
                  </div>
                </div>
                <div class="contact-item">
                  <div class="contact-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                      <circle cx="12" cy="10" r="3"/>
                    </svg>
                  </div>
                  <div>
                    <h4>Dirección</h4>
                    <p>{{ ADDRESS }}</p>
                  </div>
                </div>
              </div>
            </div>
            <div class="contact-form-wrapper animate-on-scroll">
              <form class="contact-form" @submit.prevent="handleSubmit">
                <div v-if="submitSuccess" class="form-success">
                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                    <polyline points="22 4 12 14.01 9 11.01"/>
                  </svg>
                  <h3>¡Mensaje enviado!</h3>
                  <p>Te contactaremos en menos de 24 horas.</p>
                </div>
                <template v-else>
                  <div class="form-row">
                    <div class="form-field">
                      <label for="name">Nombre *</label>
                      <input
                        id="name"
                        v-model="form.name"
                        type="text"
                        placeholder="Tu nombre"
                        required
                      />
                    </div>
                    <div class="form-field">
                      <label for="phone">Teléfono *</label>
                      <input
                        id="phone"
                        v-model="form.phone"
                        type="tel"
                        placeholder="Tu teléfono"
                        required
                      />
                    </div>
                  </div>
                  <div class="form-row">
                    <div class="form-field">
                      <label for="email">Email</label>
                      <input
                        id="email"
                        v-model="form.email"
                        type="email"
                        placeholder="Tu email"
                      />
                    </div>
                    <div class="form-field">
                      <label for="service">Tipo de servicio</label>
                      <select id="service" v-model="form.service">
                        <option value="">Selecciona...</option>
                        <option value="instalacion">Instalación eléctrica</option>
                        <option value="cuadro">Cuadro eléctrico</option>
                        <option value="iluminacion">Iluminación LED</option>
                        <option value="solar">Energía solar</option>
                        <option value="mantenimiento">Mantenimiento industrial</option>
                        <option value="boletin">Boletín eléctrico</option>
                        <option value="urgencia">Urgencia eléctrica</option>
                        <option value="otros">Otros</option>
                      </select>
                    </div>
                  </div>
                  <div class="form-field">
                    <label for="message">Mensaje</label>
                    <textarea
                      id="message"
                      v-model="form.message"
                      rows="4"
                      placeholder="Cuéntanos qué necesitas..."
                    />
                  </div>
                  <div class="form-field form-checkbox">
                    <label class="checkbox-label">
                      <input
                        v-model="form.privacy"
                        type="checkbox"
                        required
                      />
                      <span class="checkbox-text">
                        He leído y acepto la
                        <RouterLink to="/privacidad">política de privacidad</RouterLink>
                      </span>
                    </label>
                  </div>
                  <button
                    type="submit"
                    class="btn btn-primary"
                    :disabled="isSubmitting"
                  >
                    <span v-if="isSubmitting">Enviando...</span>
                    <span v-else>
                      <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="22" y1="2" x2="11" y2="13"/>
                        <polygon points="22 2 15 22 11 13 2 9 22 2"/>
                      </svg>
                      Enviar mensaje
                    </span>
                  </button>
                  <p v-if="submitError" class="form-error">{{ submitError }}</p>
                </template>
              </form>
            </div>
          </div>
        </div>
      </section>
    </main>

    <Footer />
  </div>
</template>

<style scoped>
.page {
  min-height: 100vh;
  display: flex;
  flex-direction: column;
}

/* Animation classes */
.animate-on-scroll {
  opacity: 0;
  transform: translateY(30px);
  transition: opacity 0.6s ease, transform 0.6s ease;
}

.animate-visible {
  opacity: 1;
  transform: translateY(0);
}

/* Hero Section */
.hero {
  padding-top: calc(80px + var(--space-16));
  padding-bottom: var(--space-20);
  background: linear-gradient(135deg, var(--primary-900) 0%, var(--primary-700) 50%, var(--primary-500) 100%);
  position: relative;
  overflow: hidden;
}

.hero::before {
  content: '';
  position: absolute;
  inset: 0;
  background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
  opacity: 0.5;
}

.hero .container {
  position: relative;
  z-index: 1;
}

.hero-content {
  max-width: 800px;
}

.hero-badge {
  display: inline-flex;
  align-items: center;
  gap: var(--space-2);
  padding: var(--space-2) var(--space-4);
  background: rgba(255, 255, 255, 0.15);
  border: 1px solid rgba(255, 255, 255, 0.2);
  border-radius: 9999px;
  font-weight: 500;
  font-size: 0.875rem;
  color: var(--white);
  margin-bottom: var(--space-6);
  backdrop-filter: blur(4px);
}

.hero-title {
  font-size: clamp(2.5rem, 5vw, 4rem);
  line-height: 1.1;
  margin-bottom: var(--space-6);
  color: var(--white);
}

.hero-title span {
  color: var(--accent-400);
}

.hero-subtitle {
  font-size: 1.125rem;
  color: rgba(255, 255, 255, 0.9);
  margin-bottom: var(--space-8);
  max-width: 600px;
}

.hero-stats {
  display: flex;
  gap: var(--space-8);
  margin-bottom: var(--space-8);
  flex-wrap: wrap;
}

.hero-stat {
  display: flex;
  flex-direction: column;
  gap: var(--space-1);
}

.hero-stat-number {
  font-size: 2.5rem;
  font-weight: 800;
  color: var(--accent-400);
  line-height: 1;
}

.hero-stat-label {
  font-size: 0.875rem;
  color: rgba(255, 255, 255, 0.8);
}

.hero-cta {
  display: flex;
  gap: var(--space-4);
  flex-wrap: wrap;
}

.hero-cta .btn-accent {
  font-size: 1rem;
  padding: var(--space-4) var(--space-8);
}

.hero-cta .btn-ghost {
  color: var(--white);
  border-color: rgba(255, 255, 255, 0.3);
}

.hero-cta .btn-ghost:hover {
  background: rgba(255, 255, 255, 0.1);
}

/* About Section */
.about-section {
  background: var(--bg-cream);
}

.about-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: var(--space-16);
  align-items: center;
}

.about-image {
  position: relative;
}

.about-image img {
  border-radius: var(--radius-xl);
  box-shadow: var(--shadow-xl);
  width: 100%;
  height: 500px;
  object-fit: cover;
}

.about-image::before {
  content: '';
  position: absolute;
  top: -20px;
  left: -20px;
  right: 20px;
  bottom: 20px;
  border: 2px solid var(--primary-300);
  border-radius: var(--radius-xl);
  z-index: -1;
}

.about-content h2 {
  margin-bottom: var(--space-6);
}

.about-content > p {
  margin-bottom: var(--space-6);
  font-size: 1.0625rem;
}

.about-features {
  display: grid;
  gap: var(--space-4);
  margin-top: var(--space-8);
}

.about-feature {
  display: flex;
  gap: var(--space-4);
  align-items: flex-start;
}

.about-feature-icon {
  width: 48px;
  height: 48px;
  border-radius: var(--radius-md);
  background: var(--primary-100);
  display: grid;
  place-items: center;
  color: var(--primary-700);
  flex-shrink: 0;
}

.about-feature h4 {
  margin-bottom: var(--space-1);
}

.about-feature p {
  margin: 0;
  font-size: 0.9375rem;
}

/* Services Section */
.services-section {
  background: var(--white);
}

.services-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
  gap: var(--space-6);
}

.service-card {
  background: var(--white);
  border-radius: var(--radius-lg);
  overflow: hidden;
  box-shadow: var(--shadow-sm);
  transition: box-shadow 0.3s ease, transform 0.3s ease;
}

.service-card:hover {
  box-shadow: var(--shadow-lg);
  transform: translateY(-4px);
}

.service-card-image {
  width: 100%;
  height: 200px;
  overflow: hidden;
}

.service-card-image img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.5s ease;
}

.service-card:hover .service-card-image img {
  transform: scale(1.05);
}

.service-card-content {
  padding: var(--space-6);
}

.service-card h3 {
  margin-bottom: var(--space-3);
}

.service-card p {
  margin-bottom: var(--space-4);
  color: var(--text-muted);
}

.service-card-link {
  display: inline-flex;
  align-items: center;
  gap: var(--space-2);
  color: var(--primary-700);
  font-weight: 600;
  font-size: 0.9375rem;
}

.service-card-link:hover {
  gap: var(--space-3);
}

/* Stats Section */
.stats-section {
  background: var(--primary-900);
  color: var(--white);
  padding: var(--space-16) 0;
}

.stats-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: var(--space-8);
  text-align: center;
}

.stat-item {
  display: flex;
  flex-direction: column;
  gap: var(--space-2);
  padding: var(--space-6);
}

.stat-number {
  font-size: 3rem;
  font-weight: 800;
  color: var(--accent-400);
  line-height: 1;
}

.stat-label {
  font-size: 1rem;
  color: rgba(255, 255, 255, 0.8);
}

/* Projects Section */
.projects-section {
  background: var(--bg-cream);
}

.projects-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
  gap: var(--space-6);
}

.project-card {
  border-radius: var(--radius-lg);
  overflow: hidden;
  position: relative;
}

.project-card-image {
  position: relative;
  height: 280px;
}

.project-card-image img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.5s ease;
}

.project-card:hover .project-card-image img {
  transform: scale(1.05);
}

.project-card-overlay {
  position: absolute;
  inset: 0;
  background: linear-gradient(to top, rgba(0, 0, 0, 0.8), transparent);
  display: flex;
  flex-direction: column;
  justify-content: flex-end;
  padding: var(--space-6);
  color: var(--white);
  opacity: 0;
  transition: opacity 0.3s ease;
}

.project-card:hover .project-card-overlay {
  opacity: 1;
}

.project-card-category {
  display: inline-block;
  padding: var(--space-1) var(--space-3);
  background: var(--accent-500);
  color: var(--graphite-900);
  font-size: 0.75rem;
  font-weight: 600;
  border-radius: var(--radius-sm);
  margin-bottom: var(--space-3);
  width: fit-content;
}

.project-card-overlay h3 {
  color: var(--white);
  margin-bottom: var(--space-3);
  font-size: 1.125rem;
}

.btn-sm {
  padding: var(--space-2) var(--space-4);
  font-size: 0.875rem;
  width: fit-content;
}

/* Why Section */
.why-section {
  background: var(--white);
}

.why-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: var(--space-6);
}

.why-card {
  padding: var(--space-8);
  text-align: center;
  border: 1px solid var(--graphite-100);
  border-radius: var(--radius-lg);
  transition: box-shadow 0.3s ease, border-color 0.3s ease;
}

.why-card:hover {
  box-shadow: var(--shadow-lg);
  border-color: var(--primary-300);
}

.why-card-icon {
  width: 64px;
  height: 64px;
  border-radius: 50%;
  background: linear-gradient(135deg, var(--primary-100), var(--primary-50));
  display: grid;
  place-items: center;
  margin: 0 auto var(--space-6);
  color: var(--primary-700);
}

.why-card h3 {
  margin-bottom: var(--space-3);
}

.why-card p {
  margin: 0;
  color: var(--text-muted);
}

/* FAQ Section */
.faq-section {
  background: var(--bg-cream);
}

.faq-list {
  max-width: 800px;
  margin: 0 auto;
  display: grid;
  gap: var(--space-4);
}

.faq-item {
  border: 1px solid var(--graphite-100);
  border-radius: var(--radius-lg);
  overflow: hidden;
  background: var(--white);
  transition: box-shadow 0.3s ease;
}

.faq-item:hover {
  box-shadow: var(--shadow-md);
}

.faq-item summary {
  padding: var(--space-5) var(--space-6);
  cursor: pointer;
  font-weight: 600;
  color: var(--graphite-900);
  display: flex;
  justify-content: space-between;
  align-items: center;
  list-style: none;
}

.faq-item summary::-webkit-details-marker {
  display: none;
}

.faq-item summary::after {
  content: '+';
  font-size: 1.5rem;
  color: var(--primary-700);
  font-weight: 300;
}

.faq-item[open] summary::after {
  content: '−';
}

.faq-item-content {
  padding: 0 var(--space-6) var(--space-6);
}

.faq-item-content p {
  color: var(--text-muted);
  margin: 0;
}

/* CTA Section */
.cta-section {
  background: linear-gradient(135deg, var(--primary-700), var(--primary-900));
  padding: var(--space-20) 0;
  position: relative;
  overflow: hidden;
}

.cta-section::before {
  content: '';
  position: absolute;
  inset: 0;
  background: url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM34 90c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm56-76c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM12 86c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm28-65c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm23-11c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-6 60c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm29 22c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zM32 63c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm57-13c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-9-21c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM60 91c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM35 41c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM12 60c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2z' fill='%23ffffff' fill-opacity='0.05' fill-rule='evenodd'/%3E%3C/svg%3E");
}

.cta-content {
  position: relative;
  z-index: 1;
  text-align: center;
  color: var(--white);
}

.cta-content h2 {
  color: var(--white);
  margin-bottom: var(--space-4);
}

.cta-content p {
  color: rgba(255, 255, 255, 0.9);
  font-size: 1.125rem;
  margin-bottom: var(--space-8);
  max-width: 600px;
  margin-left: auto;
  margin-right: auto;
}

.cta-buttons {
  display: flex;
  gap: var(--space-4);
  justify-content: center;
  flex-wrap: wrap;
}

.btn-lg {
  padding: var(--space-4) var(--space-8);
  font-size: 1rem;
}

/* Contact Section */
.contact-section {
  background: var(--bg-cream);
}

.contact-grid {
  display: grid;
  grid-template-columns: 1fr 1.2fr;
  gap: var(--space-16);
  align-items: start;
}

.contact-info h2 {
  margin-bottom: var(--space-6);
}

.contact-info > p {
  margin-bottom: var(--space-8);
  font-size: 1.0625rem;
}

.contact-details {
  display: grid;
  gap: var(--space-6);
}

.contact-item {
  display: flex;
  gap: var(--space-4);
  align-items: flex-start;
}

.contact-icon {
  width: 48px;
  height: 48px;
  border-radius: var(--radius-md);
  background: var(--primary-100);
  display: grid;
  place-items: center;
  color: var(--primary-700);
  flex-shrink: 0;
}

.contact-item h4 {
  margin-bottom: var(--space-1);
}

.contact-item p {
  margin: 0;
  color: var(--text-muted);
}

.contact-form-wrapper {
  background: var(--white);
  border-radius: var(--radius-xl);
  padding: var(--space-8);
  box-shadow: var(--shadow-lg);
}

.contact-form {
  display: grid;
  gap: var(--space-5);
}

.form-row {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: var(--space-4);
}

.form-field {
  display: grid;
  gap: var(--space-2);
}

.form-field label {
  font-weight: 600;
  color: var(--graphite-900);
  font-size: 0.9375rem;
}

.form-field input,
.form-field select,
.form-field textarea {
  width: 100%;
  padding: var(--space-3) var(--space-4);
  border-radius: var(--radius-md);
  border: 1px solid var(--graphite-100);
  background: var(--white);
  font-family: var(--font-sans);
  font-size: 0.9375rem;
  color: var(--graphite-900);
  transition: border-color 0.2s ease, box-shadow 0.2s ease;
}

.form-field input:focus,
.form-field select:focus,
.form-field textarea:focus {
  outline: none;
  border-color: var(--primary-500);
  box-shadow: 0 0 0 3px rgba(64, 145, 108, 0.1);
}

.form-field textarea {
  min-height: 140px;
  resize: vertical;
}

.form-checkbox {
  margin-top: var(--space-2);
}

.checkbox-label {
  display: flex;
  align-items: center;
  gap: var(--space-3);
  cursor: pointer;
  font-weight: 400;
  font-size: 0.9375rem;
}

.checkbox-label input[type="checkbox"] {
  width: 20px;
  height: 20px;
  accent-color: var(--primary-500);
}

.checkbox-text a {
  color: var(--primary-700);
  text-decoration: underline;
}

.form-success {
  text-align: center;
  padding: var(--space-8);
}

.form-success svg {
  color: var(--primary-500);
  margin-bottom: var(--space-4);
}

.form-success h3 {
  margin-bottom: var(--space-2);
}

.form-error {
  color: #dc2626;
  font-size: 0.9375rem;
  text-align: center;
}

/* Responsive */
@media (max-width: 1024px) {
  .about-grid,
  .contact-grid {
    grid-template-columns: 1fr;
    gap: var(--space-12);
  }

  .about-image {
    order: -1;
  }

  .stats-grid {
    grid-template-columns: repeat(2, 1fr);
  }
}

@media (max-width: 768px) {
  .hero-stats {
    gap: var(--space-6);
  }

  .hero-stat-number {
    font-size: 2rem;
  }

  .stats-grid {
    grid-template-columns: repeat(2, 1fr);
    gap: var(--space-4);
  }

  .stat-number {
    font-size: 2rem;
  }

  .services-grid {
    grid-template-columns: 1fr;
  }

  .form-row {
    grid-template-columns: 1fr;
  }
}

@media (max-width: 480px) {
  .stats-grid {
    grid-template-columns: 1fr;
  }

  .hero-cta {
    flex-direction: column;
  }

  .hero-cta .btn {
    width: 100%;
  }

  .cta-buttons {
    flex-direction: column;
  }

  .cta-buttons .btn {
    width: 100%;
  }
}
</style>
