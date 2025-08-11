import { GetThreadsResponse } from '../types/api';
import { http } from '../http';

export const ThreadsService = {
    async list(): Promise<GetThreadsResponse> {
        const { data } = await http.get<GetThreadsResponse>('/api/threads');
        return data;
    }
};
