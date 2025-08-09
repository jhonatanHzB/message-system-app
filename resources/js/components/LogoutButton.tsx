import React, { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import { http } from '../src/http';

type Props = {
    className?: string;
    children?: React.ReactNode;
    label?: string;
};

const LogoutButton: React.FC<Props> = ({ className, children, label = 'Cerrar sesión' }) => {
    const navigate = useNavigate();
    const [loading, setLoading] = useState(false);
    const [error, setError] = useState<string | null>(null);

    const handleLogout = async () => {
        if (loading) return;
        setError(null);
        setLoading(true);
        try {
            await http.post('./api/logout');
        } catch (e: any) {
            setError(e?.response?.data?.message || 'No se pudo cerrar sesión. Se limpiará la sesión local.');
        } finally {
            localStorage.removeItem('auth_token');
            if (http.defaults.headers?.common?.Authorization) {
                delete http.defaults.headers.common.Authorization;
            }
            setLoading(false);
            navigate('/login', { replace: true });
        }
    };

    return (
        <div className={className}>
            <button
                type="button"
                onClick={handleLogout}
                disabled={loading}
                className="btn btn-outline-secondary"
                title="Cerrar sesión"
            >
                {loading ? 'Cerrando…' : (children ?? label)}
            </button>
            {error && (
                <div className="mt-2 small text-danger">{error}</div>
            )}
        </div>
    );
};

export default LogoutButton;
