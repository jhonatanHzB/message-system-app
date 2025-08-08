import React from 'react';
import { Button } from 'react-bootstrap';

const MessageView: React.FC = () => {
    return (
        <div className="d-flex flex-column align-items-stretch flex-shrink-0 shadow-sm">
            <div className="p-4">
                <div className="d-flex align-items-center justify-content-between">
                    <h4>Asunto del mensaje</h4>
                    <Button variant="primary" size="sm">
                        <i className="ri-reply-fill"></i>
                    </Button>
                </div>
                <hr />
                <p className="mb-0">De: <span className="fw-bold">Jane Doe</span></p>
                <p className="mb-0">Fecha: <span className="">2015-07-05 00:22</span></p>
            </div>
            <div className="content p-4">
                <p>Agendemos una reunion para revisar el proyecto.</p>
            </div>
        </div>
    );
};

export default MessageView;
