import React from 'react'
import {render} from "react-dom";
import Index from './index';
import Form from './form';
import {BrowserRouter as Router, Route, Switch} from 'react-router-dom';
const PATHNAME = window.location.pathname.substring(0,3);
const app = (
    <Router basename={PATHNAME+'/plm/plm-documents/document'}>
        <Switch>
            <Route path="/index" exact compact component={Index}/>
            <Route path="/create" exact compact component={Form}/>
            <Route path="/update/:id" exact compact component={Form}/>
        </Switch>
    </Router>
);

render(app, window.document.getElementById('root'));