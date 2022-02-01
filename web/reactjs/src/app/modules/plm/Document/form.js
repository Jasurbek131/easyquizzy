import React from "react";
import {Flip, ToastContainer} from 'react-toastify';


class Form extends React.Component {
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
                <div className={'card'}>
                    <div className={'card-body'}>
                        Create Update
                    </div>
                </div>
            </div>
        );
    }
}

export default Form;
