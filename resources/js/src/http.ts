import axios, { AxiosResponse, InternalAxiosRequestConfig } from 'axios';

export const http = axios.create({
    baseURL: 'http://127.0.0.1:8000',
    headers: {
        Accept: 'application/json',
        'Content-Type': 'application/json',
    },
});

http.interceptors.request.use((config: InternalAxiosRequestConfig) => {
    const token = localStorage.getItem('auth_token');
    if (token) {
        config.headers = config.headers ?? {};
        config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
});

http.interceptors.response.use(
    (response: AxiosResponse) => response,
    (error) => {
        const status = error?.response?.status;
        if (status === 401) {
            try {
                localStorage.removeItem('auth_token');
            } catch {}
            if (typeof window !== 'undefined' && window.location.pathname !== '/login') {
                window.location.replace('/login');
            }
        }
        return Promise.reject(error);
    }
);

