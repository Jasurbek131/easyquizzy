import React, {Component} from 'react';
import {render} from "react-dom";
import axios from "axios";
import {tr} from "react-date-range/dist/locale";
import { Search } from "./Search";
const API_URL = window.location.protocol + "//" + window.location.host + "/api/v1/plm-document-reports/";

class PlmDocumentReport extends Component {
    constructor(props) {
        super(props);
        this.state = {
            items: [],
            searchParams: {
                start_date: null,
                end_date: null,
                language: 'uz'
            }
        }
    }

    onHandleChange = (name, type, e) => {
        let v;
        switch (type) {
            case "select":
                v = e?.value ?? "";
                break;
            case "multi-select":
                v = e;
                break;
            case "date":
                v = e;
                break;
            case "input":
            case "textarea":
                v = e?.target?.value ?? "";
                break;
        }
        let { searchParams } = this.state;
        searchParams[name] = v;
        this.setState({ searchParams });
    };

    onHandleSearch = () => {

    };

    componentDidMount() {
        this.getReportData().then(r => {
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

        let { items, searchParams, language } = this.state;
        const search = <Search
            searchParams={searchParams}
            onHandleChange={this.onHandleChange}
            onHandleSearch={this.onHandleSearch}
            language={language}
        />;
        let dataBody = "";
        let iterator = 0;

        if (items.length > 0){
            dataBody =  items.map(function (item, index) {
                return (
                    <tr key={index}>
                        <td>{++iterator}</td>
                        <td>{item.department_name}</td>
                        <td>{item.shift_name}</td>
                        <td>{item.shift_name}</td>
                    </tr>
                );
            });
        }

        return (<div>
            {search}
            <div className={"card"}>
                <div className={"card-body"}>
                    <table className={"table table-stripped table-condensed table-bordered"}>
                        <thead>
                            <tr>
                                <th>№</th>
                                <th>Bo'lim</th>
                                <th>Smena</th>
                                <th>Sana</th>
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
