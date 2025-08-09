import { User } from '../common';

export type LoginResponse = {
    access_token: string;
    token_type: 'Bearer' | string;
    message?: string;
};

export type GetUserResponse = User;
