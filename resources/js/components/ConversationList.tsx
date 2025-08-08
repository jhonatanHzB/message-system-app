import React from 'react';
import { ListGroup } from 'react-bootstrap';

const ConversationList: React.FC = () => {
    return (
        <>
            <div className="d-flex flex-column align-items-stretch flex-shrink-0 bg-body-tertiary shadow-sm">
                <div className="p-3">
                    <h5 className="mb-0">Lista de conversaciones</h5>
                </div>
            </div>

            <ListGroup className="list-group-flush border-bottom scrollarea shadow-sm">
                <ListGroup.Item action active href="#" className="py-3 lh-sm">
                    <div className="d-flex w-100 align-items-center justify-content-between">
                        <span className="fw-bold mb-1">Jane Doe</span>
                        <small>Viernes</small>
                    </div>
                    <div className="col-10 mb-1 small">
                        Extracto del mensaje
                    </div>
                </ListGroup.Item>
                <ListGroup.Item action href="#" className="py-3 lh-sm">
                    <div className="d-flex w-100 align-items-center justify-content-between">
                        <span className="fw-bold mb-1">Jane Doe</span>
                        <small>Viernes</small>
                    </div>
                    <div className="col-10 mb-1 small">
                        Extracto del mensaje
                    </div>
                </ListGroup.Item>
                <ListGroup.Item action href="#" className="py-3 lh-sm">
                    <div className="d-flex w-100 align-items-center justify-content-between">
                        <span className="fw-bold mb-1">Jane Doe</span>
                        <small>Viernes</small>
                    </div>
                    <div className="col-10 mb-1 small">
                        Extracto del mensaje
                    </div>
                </ListGroup.Item>
            </ListGroup>
        </>
    );
};

export default ConversationList;
