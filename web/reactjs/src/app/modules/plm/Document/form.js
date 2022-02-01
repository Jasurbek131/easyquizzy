import React from "react";
import {Flip, ToastContainer} from 'react-toastify';
import axios from "axios";
import Select from "react-select";
import DatePicker from "react-datepicker";
import customStyles from "../../../actions/style/customStyle.js";
import ru from "date-fns/locale/ru/index.js";

const API_URL = window.location.protocol + "//" + window.location.host + "/api/v1/documents/";


class Form extends React.Component {
    constructor(props, context) {
        super(props, context);
        this.state = {
            isLoading: true,
            plm_document: {
                id: "",
                doc_number: "",
                reg_date: "",
                hr_department_id: "",
                hr_organisation_id: "",
                add_info: ""
            },
            organisationList: [],
            departmentList: []
        };
    }

    async componentDidMount() {
        this._isMounted = true;
        const response = await axios.post(API_URL + 'fetch-list?type=CREATE_DOCUMENT');
        if (response.data.status) {
            this.setState({
              //  plm_document: response.data.plm_document,
                organisationList: response.data.organisationList,
                departmentList: response.data.departmentList,
                isLoading: false
            });
        }
    }

    onHandleChange = (type, model, name, index, key, value, e) => {
        let state = this.state;
        let v = value;
        switch (type) {
            case "select":
                v = e?.value ?? "";
                break;
            case "date":
                v = e;
                break;
            case "input":
                v = e?.target?.value ?? "";
                break;
        }
        state[model][name] = v;
        this.setState({[model]: state[model]});
    }

    render() {
        const {
            isLoading,
            organisationList,
            plm_document,
            departmentList
        } = this.state;
        if (isLoading) {
            document.getElementById("loading").style.display = "block";
        } else {
            document.getElementById("loading").style.display = "none";
        }
        return (
            <div>
                <div className="no-print">
                    <ToastContainer autoClose={3000} position={'top-right'} transition={Flip} draggablePercent={60} closeOnClick={true} pauseOnHover closeButton={true}/>
                </div>
                <div className={'card'}>
                    <div className={'card-header'}>
                        <div className={'row'}>
                            <div className={'col-sm-3'}>
                                <div className={'form-group'}>
                                    <label>Organization</label>
                                    <Select name={'hr_organisation_id'}
                                            onChange={this.onHandleChange.bind(this, 'select', 'plm_document', 'hr_organisation_id', '', '', '')}
                                            placeholder={"Tanlang ..."}
                                            value={organisationList.filter(({value}) => +value === +plm_document?.hr_organisation_id)}
                                            options={organisationList}
                                            isClearable={true}
                                            styles={customStyles}
                                    />
                                </div>
                            </div>
                            <div className={'col-sm-3'}>
                                <div className={'form-group'}>
                                    <label>Department</label>
                                    <Select name={'hr_department_id'}
                                            onChange={this.onHandleChange.bind(this, 'select', 'plm_document', 'hr_department_id', '', '', '')}
                                            placeholder={"Tanlang ..."}
                                            value={departmentList.filter(({value}) => +value === +plm_document?.hr_department_id)}
                                            options={departmentList}
                                            isClearable={true}
                                            styles={customStyles}
                                    />
                                </div>
                            </div>
                            <div className={'col-sm-3'}>
                                <div className={'form-group'}>
                                    <label>Reg Date</label>
                                    <DatePicker
                                        name={"reg_date"}
                                        onChange={(e)=>{
                                            this.onHandleChange('date', 'plm_document', 'reg_date', '', '', '', new Date(e))
                                        }}
                                        locale={ru}
                                        id={'reg_date'}
                                        dateFormat="dd.MM.yyyy"
                                        className={"form-control"}
                                        selected={plm_document.reg_date ? new Date(plm_document.reg_date) : ""}
                                        autoComplete={'off'}
                                        peekNextMonth
                                        showMonthDropdown
                                        showYearDropdown
                                    />
                                </div>
                            </div>
                            <div className={'col-sm-3'}>
                                <div className={'form-group'}>
                                    <label>Info</label>
                                    <input onChange={this.onHandleChange.bind(this, 'input', 'plm_document', 'add_info', '', '', '')}
                                           name={'add_info'} value={plm_document?.add_info} className={'form-control'}/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div className={'card-body'}>

                    </div>
                </div>
            </div>
        );
    }
}

export default Form;
