const API_BASE = import.meta.env.VITE_API_BASE ?? '/api';

async function get<T>(path: string): Promise<T> {
  const res = await fetch(`${API_BASE}${path}`, {
    headers: {
      Accept: 'application/json',
    },
  });
  if (!res.ok) {
    throw new Error(`Error ${res.status} al llamar a ${path}`);
  }
  return (await res.json()) as T;
}

async function post<T>(path: string, body: unknown): Promise<T> {
  const res = await fetch(`${API_BASE}${path}`, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      Accept: 'application/json',
    },
    body: JSON.stringify(body),
  });
  if (!res.ok) {
    const payload = await res.json().catch(() => ({}));
    const message = payload?.errors ? JSON.stringify(payload.errors) : `Error ${res.status}`;
    throw new Error(message);
  }
  return (await res.json()) as T;
}

export const publicApi = {
  fetchServices: () => get<{ data: ServiceDto[] }>('/services'),
  fetchService: (slug: string) => get<{ data: ServiceDto }>('/services/' + slug),
  fetchFaq: () => get<{ data: FaqDto[] }>('/faq'),
  fetchSiteSettings: () => get<{ data: SiteSettingsDto }>('/site-settings/public'),
  sendContact: (payload: ContactPayload) => post<{ status: string }>('/contact', payload),
};

export type ServiceDto = {
  id?: string;
  slug: string;
  name: string;
  summary: string;
  benefits: string[];
};

export type FaqDto = {
  id?: string;
  question: string;
  answer: string;
};

export type SiteSettingsDto = {
  brand: string;
  tagline: string;
  phone: string;
  phone_display: string;
  whatsapp: string;
  email: string;
  address: string;
  cta: string;
};

export type ContactPayload = {
  name: string;
  phone: string;
  email?: string;
  type?: string;
  area?: string;
  message?: string;
};
