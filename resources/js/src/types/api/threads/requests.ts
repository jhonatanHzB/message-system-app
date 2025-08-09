export type CreateThreadRequest = {
    subject: string;
    body: string;
    participants: Array<number>;
};
