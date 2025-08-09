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
