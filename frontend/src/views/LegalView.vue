<script setup lang="ts">
import { computed } from 'vue'
import Header from '../components/Header.vue'
import Footer from '../components/Footer.vue'
import { BRAND_NAME, EMAIL, LEGAL_NAME, PHONE_DISPLAY, ADDRESS } from '../data/contact'

const props = defineProps<{
  title: string
}>()

const content = computed(() => {
  switch (props.title) {
    case 'Aviso legal':
      return {
        description: 'Información legal sobre el responsable del sitio web y condiciones de uso.',
        sections: [
          {
            title: 'Información general',
            content: [
              `En cumplimiento de la Ley 34/2002, de 11 de julio, de servicios de la sociedad de la información y de comercio electrónico, ${BRAND_NAME} le informa que es titular del sitio web que estás visitando.`,
              'Este Aviso Legal regula el acceso y uso del sitio web, incluyendo los contenidos y servicios puestos a disposición de los usuarios.',
            ],
          },
          {
            title: 'Datos identificativos',
            content: [
              `<strong>Razón social:</strong> ${LEGAL_NAME}`,
              `<strong>Dirección:</strong> ${ADDRESS}`,
              `<strong>Teléfono:</strong> ${PHONE_DISPLAY}`,
              `<strong>Email:</strong> ${EMAIL}`,
            ],
          },
          {
            title: 'Condiciones de uso',
            content: [
              'El acceso a este sitio web implica la aceptación de las presentes condiciones de uso. El usuario se compromete a utilizar el sitio web y los servicios que se ofrecen de conformidad con la ley, la buena fe y el orden público.',
              'Queda prohibido el uso del sitio web con fines ilícitos, lesivos de los derechos e intereses de terceros, o que puedan dañar, inutilizar, sobrecargar o deteriorar el sitio web o impedir su normal utilización.',
            ],
          },
          {
            title: 'Propiedad intelectual',
            content: [
              'Los contenidos de este sitio web, incluyendo textos, imágenes, gráficos y código fuente, están protegidos por derechos de propiedad intelectual. Queda prohibida su reproducción, distribución o comunicación pública sin la autorización previa del titular.',
            ],
          },
          {
            title: 'Responsabilidad',
            content: [
              `${BRAND_NAME} no se responsabiliza de los daños que pudieran derivarse de interferencias, omisiones, interrupciones, virus informáticos, averías telefónicas o desconexiones en el funcionamiento operativo de este sistema electrónico.`,
              'Tampoco se responsabiliza de los retrasos o bloqueos en el funcionamiento operativo de este sistema electrónico causados por deficiencias o sobrecargas en las líneas telefónicas o en internet.',
            ],
          },
        ],
      }
    case 'Política de privacidad':
      return {
        description: 'Información sobre cómo tratamos tus datos personales y tus derechos.',
        sections: [
          {
            title: 'Responsable del tratamiento',
            content: [
              `<strong>Responsable:</strong> ${LEGAL_NAME}`,
              `<strong>Dirección:</strong> ${ADDRESS}`,
              `<strong>Email:</strong> ${EMAIL}`,
              `<strong>Teléfono:</strong> ${PHONE_DISPLAY}`,
            ],
          },
          {
            title: 'Finalidad del tratamiento',
            content: [
              'En cumplimiento del Reglamento (UE) 2016/679 del Parlamento Europeo y del Consejo, le informamos que los datos personales proporcionados serán tratados para:',
              '• Gestionar y responder a sus consultas y solicitudes de presupuesto.',
              '• Mantener una relación comercial con el interesado.',
              '• Enviar comunicaciones comerciales sobre nuestros servicios.',
              'La base legal para el tratamiento de sus datos es el consentimiento expreso del interesado.',
            ],
          },
          {
            title: 'Conservación de datos',
            content: [
              'Los datos personales proporcionados se conservarán mientras se mantenga la relación comercial o durante el tiempo necesario para cumplir con las obligaciones legales. Una vez finalizada esta relación, los datos serán bloqueados durante el plazo legalmente establecido.',
            ],
          },
          {
            title: 'Derechos del interesado',
            content: [
              'Tiene derecho a acceder a sus datos personales, así como a solicitar la rectificación de los datos inexactos o, en su caso, solicitar su supresión cuando, entre otros motivos, los datos ya no sean necesarios para los fines que fueron recogidos.',
              'También tiene derecho a oponerse al tratamiento de sus datos, así como a la portabilidad de los mismos. Para ejercer estos derechos, puede contactar con nosotros en:',
              `<strong>Email:</strong> ${EMAIL}`,
            ],
          },
          {
            title: 'Seguridad de la información',
            content: [
              'Implementamos medidas de seguridad técnicas y organizativas para garantizar la protección de sus datos personales frente a accesos no autorizados, pérdida o alteración.',
            ],
          },
        ],
      }
    case 'Política de cookies':
      return {
        description: 'Información sobre el uso de cookies en nuestro sitio web.',
        sections: [
          {
            title: '¿Qué son las cookies?',
            content: [
              'Las cookies son pequeños archivos de texto que se almacenan en su dispositivo cuando visita un sitio web. Son ampliamente utilizadas para hacer que los sitios web funcionen de manera más eficiente, así como para proporcionar información a los propietarios del sitio.',
            ],
          },
          {
            title: 'Tipos de cookies que utilizamos',
            content: [
              '<strong>Cookies técnicas necesarias:</strong> Son aquellas que permiten al usuario la navegación a través de la página web y la utilización de las diferentes opciones o servicios que en ella existen.',
              '<strong>Cookies de análisis:</strong> Son aquellas que permiten el seguimiento y análisis del comportamiento de los usuarios de los sitios web a los que están vinculadas.',
            ],
          },
          {
            title: 'Cookies utilizadas',
            content: [
              '<strong>Propias:</strong>',
              '• session_cookie: Cookie técnica para mantener la sesión del usuario.',
              '',
              '<strong>De terceros:</strong>',
              '• Google Analytics: Para el análisis del tráfico web (si se activa).',
            ],
          },
          {
            title: 'Gestión de cookies',
            content: [
              'Puede permitir, bloquear o eliminar las cookies instaladas en su equipo mediante la configuración de las opciones del navegador instalado en su ordenador.',
              'Le informamos que, en caso de bloquear las cookies técnicas necesarias, es posible que algunas funciones del sitio web no estén disponibles.',
            ],
          },
          {
            title: 'Más información',
            content: [
              `Si tiene alguna pregunta sobre nuestra política de cookies, puede contactarnos en: ${EMAIL}`,
            ],
          },
        ],
      }
    default:
      return {
        description: '',
        sections: [],
      }
  }
})
</script>

<template>
  <div class="page">
    <Header />

    <main class="legal-page">
      <div class="container">
        <div class="legal-header">
          <div class="section-kicker">Legal</div>
          <h1>{{ title }}</h1>
          <p v-if="content.description" class="legal-description">{{ content.description }}</p>
        </div>

        <div class="legal-content">
          <article
            v-for="(section, index) in content.sections"
            :key="index"
            class="legal-section"
          >
            <h2>{{ section.title }}</h2>
            <div class="legal-section-content">
              <p
                v-for="(paragraph, pIndex) in section.content"
                :key="pIndex"
                v-html="paragraph"
              />
            </div>
          </article>
        </div>
      </div>
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

.legal-page {
  padding-top: calc(80px + var(--space-16));
  padding-bottom: var(--space-20);
  background: var(--bg-cream);
  flex: 1;
}

.legal-header {
  text-align: center;
  margin-bottom: var(--space-12);
  max-width: 700px;
  margin-left: auto;
  margin-right: auto;
}

.legal-header h1 {
  margin: var(--space-4) 0 var(--space-3);
}

.legal-description {
  color: var(--text-muted);
  font-size: 1.125rem;
}

.legal-content {
  max-width: 800px;
  margin: 0 auto;
}

.legal-section {
  background: var(--white);
  border-radius: var(--radius-lg);
  padding: var(--space-8);
  margin-bottom: var(--space-6);
  box-shadow: var(--shadow-sm);
}

.legal-section:last-child {
  margin-bottom: 0;
}

.legal-section h2 {
  font-size: 1.25rem;
  margin-bottom: var(--space-4);
  color: var(--primary-700);
}

.legal-section-content {
  display: grid;
  gap: var(--space-4);
}

.legal-section-content p {
  margin: 0;
  line-height: 1.7;
}

@media (max-width: 768px) {
  .legal-page {
    padding-top: calc(80px + var(--space-12));
    padding-bottom: var(--space-12);
  }

  .legal-section {
    padding: var(--space-6);
  }
}
</style>
