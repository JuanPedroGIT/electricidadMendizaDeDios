const API_BASE = import.meta.env.VITE_API_BASE ?? '/api';

export interface AdminSession {
  isAuthenticated: boolean;
  csrfToken: string | null;
}

class AdminApiClient {
  private csrfToken: string | null = null;

  setCsrfToken(token: string): void {
    this.csrfToken = token;
  }

  clearSession(): void {
    this.csrfToken = null;
  }

  private getHeaders(isMutation = false): HeadersInit {
    const headers: HeadersInit = {
      Accept: 'application/json',
      'Content-Type': 'application/json',
    };

    if (isMutation && this.csrfToken) {
      headers['X-CSRF-TOKEN'] = this.csrfToken;
    }

    return headers;
  }

  async login(password: string): Promise<{ status: string; csrf_token: string }> {
    const res = await fetch(`${API_BASE}/admin/login`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        Accept: 'application/json',
      },
      credentials: 'include',
      body: JSON.stringify({ password }),
    });

    if (!res.ok) {
      const error = await res.json().catch(() => ({ error: 'Login failed' }));
      throw new Error(error.error || 'Login failed');
    }

    const data = await res.json();
    this.setCsrfToken(data.csrf_token);
    return data;
  }

  async logout(): Promise<void> {
    await fetch(`${API_BASE}/admin/logout`, {
      method: 'POST',
      headers: this.getHeaders(true),
      credentials: 'include',
    });
    this.clearSession();
  }

  // Services CRUD
  async getServices(): Promise<{ data: AdminServiceDto[] }> {
    return this.get('/admin/services');
  }

  async createService(payload: CreateServicePayload): Promise<{ data: { id: string } }> {
    return this.post('/admin/services', payload);
  }

  async updateService(id: string, payload: CreateServicePayload): Promise<{ status: string }> {
    return this.put(`/admin/services/${id}`, payload);
  }

  async deleteService(id: string): Promise<{ status: string }> {
    return this.delete(`/admin/services/${id}`);
  }

  // FAQ CRUD
  async getFaqs(): Promise<{ data: AdminFaqDto[] }> {
    return this.get('/admin/faq');
  }

  async createFaq(payload: CreateFaqPayload): Promise<{ data: { id: string } }> {
    return this.post('/admin/faq', payload);
  }

  async updateFaq(id: string, payload: CreateFaqPayload): Promise<{ status: string }> {
    return this.put(`/admin/faq/${id}`, payload);
  }

  async deleteFaq(id: string): Promise<{ status: string }> {
    return this.delete(`/admin/faq/${id}`);
  }

  // Site Settings
  async getSiteSettings(): Promise<{ data: SiteSettingsDto }> {
    return this.get('/admin/site-settings');
  }

  async updateSiteSettings(payload: SiteSettingsDto): Promise<{ status: string }> {
    return this.put('/admin/site-settings', payload);
  }

  private async get<T>(path: string): Promise<T> {
    const res = await fetch(`${API_BASE}${path}`, {
      headers: this.getHeaders(false),
      credentials: 'include',
    });

    if (res.status === 401) {
      this.clearSession();
      throw new Error('Session expired');
    }

    if (!res.ok) {
      const error = await res.json().catch(() => ({ error: 'Request failed' }));
      throw new Error(error.error || `Error ${res.status}`);
    }

    return res.json();
  }

  private async post<T>(path: string, body: unknown): Promise<T> {
    const res = await fetch(`${API_BASE}${path}`, {
      method: 'POST',
      headers: this.getHeaders(true),
      credentials: 'include',
      body: JSON.stringify(body),
    });

    if (res.status === 401) {
      this.clearSession();
      throw new Error('Session expired');
    }

    if (!res.ok) {
      const error = await res.json().catch(() => ({ error: 'Request failed' }));
      throw new Error(error.error || `Error ${res.status}`);
    }

    return res.json();
  }

  private async put<T>(path: string, body: unknown): Promise<T> {
    const res = await fetch(`${API_BASE}${path}`, {
      method: 'PUT',
      headers: this.getHeaders(true),
      credentials: 'include',
      body: JSON.stringify(body),
    });

    if (res.status === 401) {
      this.clearSession();
      throw new Error('Session expired');
    }

    if (!res.ok) {
      const error = await res.json().catch(() => ({ error: 'Request failed' }));
      throw new Error(error.error || `Error ${res.status}`);
    }

    return res.json();
  }

  private async delete<T>(path: string): Promise<T> {
    const res = await fetch(`${API_BASE}${path}`, {
      method: 'DELETE',
      headers: this.getHeaders(true),
      credentials: 'include',
    });

    if (res.status === 401) {
      this.clearSession();
      throw new Error('Session expired');
    }

    if (!res.ok) {
      const error = await res.json().catch(() => ({ error: 'Request failed' }));
      throw new Error(error.error || `Error ${res.status}`);
    }

    return res.json();
  }
}

export const adminApi = new AdminApiClient();

export interface AdminServiceDto {
  id: string;
  slug: string;
  name: string;
  summary: string;
  benefits: string[];
}

export interface CreateServicePayload {
  slug: string;
  name: string;
  summary: string;
  benefits: string[];
}

export interface AdminFaqDto {
  id: string;
  question: string;
  answer: string;
}

export interface CreateFaqPayload {
  question: string;
  answer: string;
}

export interface SiteSettingsDto {
  brand: string;
  tagline: string;
  phone: string;
  phone_display: string;
  whatsapp: string;
  email: string;
  address: string;
  cta: string;
}
