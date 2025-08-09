import { GetUserResponse, LoginRequest, LoginResponse, User } from '../types/api';
import { http } from '../http';

export const AuthService = {
    async login(payload: LoginRequest): Promise<LoginResponse> {
        const { data } = await http.post<LoginResponse>('/api/login', payload);
        return data;
    },

    async getUser(): Promise<User> {
        const { data } = await http.get<GetUserResponse>('/api/user');
        return data;
    },
};
