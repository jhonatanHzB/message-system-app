import React from 'react';
import { Link } from 'react-router-dom';

const Register: React.FC = () => {
    return (
        <main className="form-signin w-100 m-auto">

            <form className="space-y-4">
                <h1 className="h3 mb-3 fw-normal">Registro</h1>

                <div className="form-floating">
                    <input
                        id="email"
                        type="email"
                        className="form-control"
                        placeholder="name@example.com"
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
                        autoComplete="current-password"
                        required
                    />
                    <label htmlFor="password">Contraseña</label></div>

                <button
                    type="submit"
                    className="btn btn-primary w-100 py-2 my-3"
                >
                    Registrar
                </button>
            </form>

            <p className="mt-4 text-sm text-center text-gray-600">
                <span className="mx-2">¿Ya tienes cuenta?</span>
                <Link to="/login" className="text-blue-600 hover:underline">
                    Volver a Login
                </Link>
            </p>
        </main>
    );
};

export default Register;
