import React from 'react'
import {render} from "react-dom";
import Index from 'index';
import Form from 'form';
import {BrowserRouter as Router, Route, Switch} from 'react-router-dom';
const PATH_NAME = '/uz/plm/models-list/model-list';
const ModelList = (
    <Router>
        <Switch>
            <Route path={PATH_NAME+"/index"} component={Index}/>
            <Route path={PATH_NAME+"/create/"} component={Form}/>
            <Route path={PATH_NAME+"/update/:id"} component={Form}/>
            <Route path="/" component={Index}/>
        </Switch>
    </Router>);

render(ModelList, window.document.getElementById('root'));