import { Paginated } from '../common';
import { Message, Thread } from '../common';

export type GetThreadsResponse = Paginated<Thread>;

export type CreateThreadResponse = Thread;

export type GetThreadResponse = Thread & {
    messages?: Message[];
};
