import { GetNotificationsResponse, MarkThreadReadResponse } from '../types/api';
import { http } from '../http';

export const NotificationsService = {
    async list(): Promise<GetNotificationsResponse> {
        const { data } = await http.get<GetNotificationsResponse>('/api/notifications');
        return data;
    },

    async markThreadRead(threadId: number | string): Promise<MarkThreadReadResponse> {
        const { data } = await http.post<MarkThreadReadResponse>(`/api/threads/${threadId}/read`, {});
        return data;
    },
};
