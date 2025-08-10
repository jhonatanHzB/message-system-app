import React, { useEffect, useMemo, useState } from 'react';
import { Dropdown, Form, Badge, Button } from 'react-bootstrap';
import { NotificationsService } from '../src/services';
import { GetNotificationsResponse, Notification } from '../src/types/api';

const Notifications: React.FC = () => {
    const [unreadMessageCount, setUnreadMessageCount] = useState<number>(0);
    const [threads, setThreads] = useState<Notification["threads"]>([]);
    const [query, setQuery] = useState<string>("");

    useEffect(() => {
        let mounted = true;

        // Request para obtener notificaciones
        const load = async () => {
            const res: GetNotificationsResponse = await NotificationsService.list();
            if (mounted) {
                setUnreadMessageCount(res.unread_messages_count ?? 0);
                setThreads(res.threads ?? []);
            }
        };

        // Carga inicial
        load();

        // Temp: intervalo cada 30s antes de implementar socket.io
        const id = setInterval(load, 30000);

        return () => {
            mounted = false;
            clearInterval(id);
        };
    }, []);

    const filteredThreads = useMemo(() => {
        const q = query.trim().toLowerCase();
        if (!q) return threads;
        return threads.filter(t => (t.subject ?? "").toLowerCase().includes(q));
    }, [threads, query]);

    return (
        <Dropdown>
            <Dropdown.Toggle as={Button} variant="primary" id="dropdown-notifications">
                {
                    unreadMessageCount === 0
                        ? 'Notificaciones'
                        : 'Nuevos mensajes'
                }
                {
                    unreadMessageCount > 0 && (
                        <Badge
                            bg="danger"
                            className="position-absolute top-0 start-100 translate-middle rounded-pill"
                        >
                            {unreadMessageCount}
                            <span className="visually-hidden">unread messages</span>
                        </Badge>
                    )
                }
            </Dropdown.Toggle>

            <Dropdown.Menu className="pt-0 mx-0 rounded-3 shadow overflow-hidden w-280px" data-bs-theme="light">
                {
                    threads.length > 0 && (
                        <Form className="p-2 mb-2 bg-body-tertiary border-bottom">
                            <Form.Control
                                type="search"
                                autoComplete="off"
                                placeholder="Buscar notificación..."
                                value={query}
                                onChange={(e) => setQuery(e.target.value)}
                            />
                        </Form>
                    )
                }
                {
                    threads.length === 0 ? (
                        <Dropdown.Item className="d-flex align-items-center gap-2 py-2" href="#">
                            <span className="item-text">Nada nuevo</span>
                        </Dropdown.Item>
                    ) : filteredThreads.length === 0 ? (
                        <Dropdown.Item className="d-flex align-items-center gap-2 py-2" href="#" disabled>
                            <span className="item-text">Sin resultados</span>
                        </Dropdown.Item>
                    ) : (
                        filteredThreads.map((thread) => (
                            <Dropdown.Item
                                className="d-flex align-items-center gap-2 py-2 fw-bold"
                                key={`${thread.thread_id ?? thread.subject}`}
                                href="#"
                            >
                                <span className="d-inline-block bg-info rounded-circle p-1"></span>
                                <span className="item-text">{thread.subject}</span>
                                {thread.unread_count > 0 && (
                                    <Badge bg="secondary" className="ms-auto">{thread.unread_count}</Badge>
                                )}
                            </Dropdown.Item>
                        ))
                    )
                }
            </Dropdown.Menu>
        </Dropdown>
    );
};

export default Notifications;
