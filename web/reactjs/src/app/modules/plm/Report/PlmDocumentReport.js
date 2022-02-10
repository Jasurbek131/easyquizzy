import React, {Component} from 'react';
import {render} from "react-dom";
import axios from "axios";
import {tr} from "react-date-range/dist/locale";
const API_URL = window.location.protocol + "//" + window.location.host + "/api/v1/plm-document-reports/";

class PlmDocumentReport extends Component {
    constructor(props) {
        super(props);
        this.state = {
            items: []
        }
    }

    componentDidMount() {
        this.getReportData().then(r => {
            console.log("ok");
        });
    };

    async getReportData()
    {
        let response = await axios.get(API_URL + 'index?type=PLM_DOCUMENT_DATA');
        if (response.data.status) {
            this.setState({items: response.data.items})
        }
    };

    render() {
        let { items } = this.state;

        let dataBody = "";
        let iterator = 0;
        if (items.length > 0){
            dataBody =  items.map(function (item, index) {
                return (
                    <tr key={index}>
                        <td>{++iterator}</td>
                        <td>{item.department_name}</td>
                        <td>{item.shift_name}</td>
                    </tr>
                );
            });
        }

        return (<div>
            <div className={"card"}>
                <div className={"card-body"}>
                    <table className={"table table-stripped table-condensed table-bordered"}>
                        <thead>
                            <tr>
                                <th>№</th>
                                <th>Bo'lim</th>
                                <th>Smena</th>
                            </tr>
                        </thead>
                        <tbody>
                            {dataBody}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>);
    }
}
render((<PlmDocumentReport/>), window.document.getElementById('root'));
