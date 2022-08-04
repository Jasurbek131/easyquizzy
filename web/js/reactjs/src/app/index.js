import React from "react";
import {render} from "react-dom";
import {Flip, ToastContainer} from 'react-toastify';


class Root extends React.Component {
    constructor(props, context) {
        super(props, context);
        this.state = {
            isLoading: true
        };
    }

    render() {
        const {
            isLoading
        } = this.state;

        return (
            <div>
                <div className="no-print">
                    <ToastContainer autoClose={3000} position={'top-right'} transition={Flip} draggablePercent={60} closeOnClick={true} pauseOnHover closeButton={true}/>
                </div>
                <div className={'row'}>
                    <div className={'col-sm-12'}>
                        REACT JS
                    </div>
                </div>
            </div>
        );
    }
}

render((<Root/>), window.document.getElementById('root'));
