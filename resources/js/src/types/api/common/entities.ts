export type ID = number | string;

export type Timestamps = {
    created_at: Date;
    updated_at: Date;
};

export type User = Timestamps & {
    id: ID;
    name: string;
    email: string;
    email_verified_at: string;
    pivot?: Pivot
};

export type Thread = Timestamps & {
    id: ID;
    subject: string;
    participants?: Array<User>;
    latest_message?: Message;
    thread_id?: number;
    unread_count?: number;
};

export type Message = Timestamps & {
    id: ID;
    thread_id: ID;
    user_id: ID;
    body: string;
    user: User;
};

export interface Pivot {
    thread_id: number;
    user_id:   number;
}

export interface Notification {
    unread_messages_count: number;
    threads: Array<Required<Pick<Thread, "thread_id" | "subject" | "unread_count">>> | [];
}
