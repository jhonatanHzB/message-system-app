import React from 'react';
import { Dropdown, Form, Badge, Button } from 'react-bootstrap';

const Notifications: React.FC = () => {
    return (
        <Dropdown>
            <Dropdown.Toggle as={Button} variant="primary" id="dropdown-notifications">
                Notificaciones
                <Badge bg="danger" className="position-absolute top-0 start-100 translate-middle rounded-pill">
                    2
                    <span className="visually-hidden">unread messages</span>
                </Badge>
            </Dropdown.Toggle>

            <Dropdown.Menu className="pt-0 mx-0 rounded-3 shadow overflow-hidden w-280px" data-bs-theme="light">
                <Form className="p-2 mb-2 bg-body-tertiary border-bottom">
                    <Form.Control
                        type="search"
                        autoComplete="off"
                        placeholder="Buscar notificación..."
                    />
                </Form>
                <Dropdown.Item className="d-flex align-items-center gap-2 py-2 fw-bold" href="#">
                    <span className="d-inline-block bg-info rounded-circle p-1"></span>
                    <span className="item-text">Primer mensaje</span>
                </Dropdown.Item>
                <Dropdown.Item className="d-flex align-items-center gap-2 py-2 fw-bold" href="#">
                    <span className="d-inline-block bg-info rounded-circle p-1"></span>
                    <span className="item-text">Segundo mensaje</span>
                </Dropdown.Item>
                <Dropdown.Item className="d-flex align-items-center gap-2 py-2" href="#">
                    <span className="d-inline-block bg-success rounded-circle p-1"></span>
                    <span className="item-text">Tercer mensaje</span>
                </Dropdown.Item>
                <Dropdown.Item className="d-flex align-items-center gap-2 py-2" href="#">
                    <span className="d-inline-block bg-success rounded-circle p-1"></span>
                    <span className="item-text">Separated link</span>
                </Dropdown.Item>
            </Dropdown.Menu>
        </Dropdown>
    );
};

export default Notifications;
