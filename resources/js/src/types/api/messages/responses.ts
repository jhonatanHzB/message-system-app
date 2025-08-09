import { Thread, User } from '../common';
import { Message } from '../common';

export type PostMessageResponse = Message & {
    thread: Thread,
    user: User
};
