import React, { useEffect, useState } from 'react';
import { ListGroup } from 'react-bootstrap';
import { GetThreadsResponse, Thread } from '../src/types/api';
import { ThreadsService } from "../src/services/threads.service";
import { formatMessageDate } from "../lib/utils/dateFormat";

const ConversationList: React.FC = () => {
    const [threads, setThreads] = useState<Thread[]>([]);

    useEffect(() => {
        let mounted = true;

        const load = async () => {
            try {
                const res: GetThreadsResponse = await ThreadsService.list();
                if (mounted) {
                    setThreads(res.data ?? []);
                }
            } catch (err) {
                if (mounted) setThreads([])
            }
        }

        load();

        return () => {
            mounted = false;
        }
    }, []);

    return (
        <>
            <div className="d-flex flex-column align-items-stretch flex-shrink-0 bg-body-tertiary shadow-sm">
                <div className="p-3">
                    <h5 className="mb-0">Lista de conversaciones</h5>
                </div>
            </div>

            <ListGroup className="list-group-flush border-bottom scrollarea shadow-sm">
                {
                    threads.length === 0
                        ? (
                            <ListGroup.Item action key={0} href="#" className="py-3 lh-sm">
                                <div className="col-10 mb-1 small">
                                    No hay mensajes aún
                                </div>
                            </ListGroup.Item>
                        )
                        : (
                            threads.map((thread: Thread) => {
                                const lm = thread.latest_message;
                                const createdAtLabel = lm?.created_at
                                    ? formatMessageDate(lm.created_at as any)
                                    : '-';

                                return (
                                    <ListGroup.Item
                                        action
                                        href="#"
                                        onClick={(e) => e.preventDefault()}
                                        className="py-3 lh-sm"
                                        key={`${thread.id}`}
                                    >
                                        <div className="d-flex w-100 align-items-center justify-content-between">
                                            <span className="fw-bold mb-1">{
                                                thread.participants?.filter(p => p.id !== thread.current_user_id)
                                                    .map(p => p.name).join(', ') || 'Sin participantes'
                                            }</span>
                                            <small>{createdAtLabel}</small>
                                        </div>
                                    </ListGroup.Item>
                                );
                            })
                        )
                }
            </ListGroup>
        </>
    );
};

export default ConversationList;
