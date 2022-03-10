import React, {Component} from 'react';
import {render} from "react-dom";

class PlmDefectReport extends Component {
    render() {
        return (
            <div>
                Defect report
            </div>
        );
    }
}

render((<PlmDefectReport/>), window.document.getElementById('root'));
