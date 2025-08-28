// frontend/src/services/api.js

const API_BASE_URL = import.meta.env.VITE_API_BASE_URL || 'http://localhost:8000';

async function request(endpoint, options = {}) {
    const url = `${API_BASE_URL}${endpoint}`;
    const defaultOptions = {
        headers: {
            'Content-Type': 'application/json',
        },
    };

    const config = { ...defaultOptions, ...options };

    try {
        const response = await fetch(url, config);
        const data = await response.json();

        if (!response.ok) {
            throw new Error(data.error || 'حدث خطأ في الاتصال');
        }

        return data;
    } catch (error) {
        console.error('API request failed:', error);
        throw error;
    }
}

export const volunteerService = {
    getAll: () => request('/api/volunteers'),
    getById: (id) => request(`/api/volunteers/${id}`),
    create: (data) => request('/api/volunteers', {
        method: 'POST',
        body: JSON.stringify(data)
    }),
    update: (id, data) => request(`/api/volunteers/${id}`, {
        method: 'PUT',
        body: JSON.stringify(data)
    }),
    delete: (id) => request(`/api/volunteers/${id}`, {
        method: 'DELETE'
    }),
    findBySkills: (skills) => request(`/api/volunteers/skills/${skills}`),
    getStats: () => request('/api/volunteers/stats')
};

export const eventService = {
    getAll: () => request('/api/events'),
    getById: (id) => request(`/api/events/${id}`),
    create: (data) => request('/api/events', {
        method: 'POST',
        body: JSON.stringify(data)
    }),
    update: (id, data) => request(`/api/events/${id}`, {
        method: 'PUT',
        body: JSON.stringify(data)
    }),
    delete: (id) => request(`/api/events/${id}`, {
        method: 'DELETE'
    }),
    getUpcoming: () => request('/api/events/upcoming'),
    getNeedVolunteers: () => request('/api/events/need-volunteers'),
    getStats: () => request('/api/events/stats')
};

export const matchService = {
    getAll: () => request('/api/matches'),
    generate: () => request('/api/matches/generate', {
        method: 'POST'
    }),
    updateStatus: (id, status) => request(`/api/matches/${id}`, {
        method: 'PUT',
        body: JSON.stringify({ status })
    }),
    delete: (id) => request(`/api/matches/${id}`, {
        method: 'DELETE'
    })
};