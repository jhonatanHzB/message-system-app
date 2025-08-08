import React from 'react';
import { Row, Col } from 'react-bootstrap';
import Header from './Header';
import ConversationList from './ConversationList';
import MessageView from './MessageView';

const AppLayout: React.FC = () => {
    return (
        <>
            <Header />
            <main className="container mt-3 flex-grow-1">
                <Row>
                    <Col sm={12} lg={5} xl={4}>
                        <ConversationList />
                    </Col>
                    <Col sm={12} lg={7} xl={8}>
                        <MessageView />
                    </Col>
                </Row>
            </main>
            <footer className="container footer mt-auto py-3 bg-body-tertiary">
                <p className="text-body-secondary text-center">Licencia MIT para MyInbox App</p>
            </footer>
        </>
    );
};

export default AppLayout;
