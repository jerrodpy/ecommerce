const API_URL = import.meta.env.VITE_API_URL;
function buildQueryString(params) {
  if (!params || Object.keys(params).length === 0) {
    return '';
  }

  const flattenParams = (obj, prefix = '') => {
    const result = [];

    for (const key in obj) {
      if (!Object.hasOwn(obj, key)) continue;

      const value = obj[key];
      const fullKey = prefix ? `${prefix}[${key}]` : key;

      if (value === null || value === undefined || value === '') {
        continue;
      }

      if (typeof value === 'object' && !Array.isArray(value)) {
        result.push(...flattenParams(value, fullKey));
      } else {
        result.push([fullKey, value]);
      }
    }

    return result;
  };

  const flattened = flattenParams(params);
  const searchParams = new URLSearchParams(flattened);

  return `?${searchParams.toString()}`;
}

async function request(endpoint, options = {}) {
  const queryString = options.params ? buildQueryString(options.params) : '';
  const url = `${API_URL}${endpoint}${queryString}`;
  const token = localStorage.getItem('token');
  const isFormData = options.body instanceof FormData;
  const headers = {
    ...(isFormData ? {} : { 'Content-Type': 'application/json' }),
    ...options.headers,
  };

  if (token) {
    headers['Authorization'] = `Bearer ${token}`;
  }
  let response, data;
  try {
    response = await fetch(url, {
      method: options.method || 'GET',
      headers,
      ...(options.body && { body: options.body }),
    });
    data = await response.json();
  } catch {
    throw new Error("Помилка з'єднання з сервером");
  }

  if (!response.ok) {
    handleError(response.status, data);
  }
  return data;
}

function handleError(status, data) {
  const message = data.message || data.error || 'Сталася помилка';

  switch (status) {
    case 400:
      throw new Error(`Невірний запит: ${message}`);
    case 401:
      localStorage.removeItem('token');
      localStorage.removeItem('user');
      window.location.href = '/auth';
      throw new Error('Потрібна авторизація');
    case 403:
      throw new Error('Доступ заборонено');
    case 404:
      throw new Error('Ресурс не знайдено');
    case 422:
      throw new Error(`Помилка валідації: ${message}`);
    case 500:
      throw new Error('Помилка сервера');
    default:
      throw new Error(message);
  }
}

export const api = {
  get: (endpoint, params) => request(endpoint, { method: 'GET', params }),

  post: (endpoint, data) =>
    request(endpoint, {
      method: 'POST',
      body: JSON.stringify(data),
    }),

  postForm: (endpoint, data) =>
    request(endpoint, {
      method: 'POST',
      body: data,
    }),

  put: (endpoint, data) =>
    request(endpoint, {
      method: 'PUT',
      body: JSON.stringify(data),
    }),

  patch: (endpoint, data) =>
    request(endpoint, {
      method: 'PATCH',
      body: JSON.stringify(data),
    }),

  delete: (endpoint) => request(endpoint, { method: 'DELETE' }),
};
