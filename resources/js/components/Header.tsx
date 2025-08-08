import React from 'react';
import { Navbar, Container, Form, FormControl, Button } from 'react-bootstrap';
import Notifications from './Notifications';

const Header: React.FC = () => {
    return (
        <header>
            <Container>
                <div className="row">
                    <div className="col-12">
                        <Navbar bg="body-tertiary" className="p-3">
                            <Container>
                                <Navbar.Brand href="#home">🚀 MyInbox</Navbar.Brand>
                                <Form className="d-flex">
                                    <FormControl
                                        type="search"
                                        placeholder="Buscar"
                                        className="me-2"
                                        aria-label="Search"
                                    />
                                    <Button variant="outline-info"><i className="ri-search-line"></i></Button>
                                </Form>
                                <Notifications />
                            </Container>
                        </Navbar>
                    </div>
                </div>
            </Container>
        </header>
    );
};

export default Header;
