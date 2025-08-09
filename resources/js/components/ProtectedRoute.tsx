import React from 'react';
import { Navigate, Outlet } from 'react-router-dom';

type ProtectedRouteProps = {
    children?: React.ReactNode;
};

const ProtectedRoute: React.FC<ProtectedRouteProps> = ({ children }) => {
    const token = typeof window !== 'undefined' ? localStorage.getItem('auth_token') : null;

    if (!token) {
        return <Navigate to="/login" replace />;
    }

    return children ? <>{children}</> : <Outlet />;
};

export default ProtectedRoute;
