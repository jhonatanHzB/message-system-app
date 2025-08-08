import React, { useState } from 'react';
import { Link, useNavigate } from 'react-router-dom';
import axios from 'axios';

const Login: React.FC = () => {
    const navigate = useNavigate();
    const [email, setEmail] = useState<string>('');
    const [password, setPassword] = useState<string>('');
    const [loading, setLoading] = useState(false);
    const [error, setError] = useState<string | null>(null);

    const handleSubmit = async (e: React.FormEvent) => {
        e.preventDefault();
        setError(null);

        if (!email || !password) {
            setError('Por favor, completa ambos campos.');
            return;
        }

        try {
            setLoading(true);

            const response = await axios.post(
                './api/login',
                { email, password },
                {
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                    },
                }
            );

            if (response.status === 200) {
                const token = response.data?.access_token;

                if (!token) {
                    setError('No se recibió el token de autenticación.');
                    return;
                }

                localStorage.setItem('auth_token', token);
                axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;

                navigate('/app', { replace: true });
            } else {
                setError('No se pudo iniciar sesión. Intenta de nuevo.');
            }
        } catch (err: any) {
            const msg =
                err?.response?.data?.message ||
                'Credenciales inválidas. Inténtalo de nuevo.';
            setError(msg);
        } finally {
            setLoading(false);
        }
    };

    return (
        <main className="form-signin w-100 m-auto">
            {error && (
                <div className="mb-4 rounded bg-red-100 text-red-700 px-3 py-2 text-sm">
                    {error}
                </div>
            )}

            <form onSubmit={handleSubmit} className="space-y-4">
                <h1 className="h3 mb-3 fw-normal">Iniciar sesión</h1>

                <div className="form-floating">
                    <input
                        id="email"
                        type="email"
                        className="form-control"
                        placeholder="name@example.com"
                        value={email}
                        onChange={(e) => setEmail(e.target.value)}
                        autoComplete="email"
                        required
                    />
                    <label htmlFor="email">Correo electrónico</label>
                </div>

                <div className="form-floating">
                    <input
                        id="password"
                        type="password"
                        className="form-control"
                        placeholder="Password"
                        value={password}
                        onChange={(e) => setPassword(e.target.value)}
                        autoComplete="current-password"
                        required
                    />
                    <label htmlFor="password">Contraseña</label></div>

                    <button
                        type="submit"
                        className="btn btn-primary w-100 py-2 my-3"
                        disabled={loading}
                    >
                        {loading ? 'Entrando…' : 'Entrar'}
                    </button>
            </form>

            <p className="mt-4 text-sm text-center text-gray-600">
                <span className="mx-2">¿No tienes cuenta?</span>
                <Link to="/register" className="text-blue-600 hover:underline">
                    Ingresar
                </Link>
            </p>
        </main>
    );
};

export default Login;
