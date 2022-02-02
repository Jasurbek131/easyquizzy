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
            modal: {
               display: "none",
               title: "",
            },
            plm_document: {
                id: "",
                doc_number: "",
                reg_date: "",
                hr_department_id: "",
                hr_organisation_id: "",
                add_info: ""
            },
            plm_document_items: [{
                product_id: "",
                planned_stop_id: "",
                unplanned_stop_id: "",
                repaired_id: "",
                scrapped_id: "",
                processing_time_id: "",
                start_work: 0,
                end_work: 0,
                qty: "",
                fact_qty: "",
                repaired: {
                    id: "",
                    name_uz: "",
                    category_id: "",
                },
                scrapped: {
                    name_uz: "",
                    type: "",
                },
                planned_stopped: {
                    begin_date: "",
                    end_time: "",
                    add_info: "",
                    reason_id: ""
                },
                unplanned_stopped: {
                    begin_date: "",
                    end_time: "",
                    add_info: "",
                    reason_id: "",
                    bypass: ""
                }
            }],
            organisationList: [],
            departmentList: [],
            reasonList: [],
            productList: [{
                equipmentGroup: {equipmentGroupRelationEquipments: []}
            }]
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
                productList: response.data.productList,
                reasonList: response.data.reasonList,
                isLoading: false
            });
        }
    }

    onHandleChange = (type, model, name, key, index, value, e) => {
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

        let {plm_document_items} = this.state;
        switch (model) {
            case "plm_document":
                let {plm_document} = this.state;
                plm_document[name] = v;
                this.setState({plm_document: plm_document});
                break;
            case "plm_document_items":
                if (name === 'product_id') {
                    plm_document_items[key]['equipmentList'] = e?.equipmentGroup?.equipmentGroupRelationEquipments ?? [];
                }
                plm_document_items[key][name] = v;
                this.setState({plm_document_items: plm_document_items});
                break;
            case "modal":
                let {modal} = this.state;
                plm_document_items[modal.key][modal.type][name] = v;
                this.setState({plm_document_items: plm_document_items});
                break;
        }
    }

    onOpenModal = (type, title, key) => {
        let {plm_document_items} = this.state;
        let model = plm_document_items[key][type];
        let modal = {
            display: "block",
            title: title,
            type: type,
            model: model,
            key: key
        }
        this.setState({modal: modal});
    }

    onHandleSave = (e) => {
        let {modal} = this.state;
        let {plm_document_items} = this.state;
        switch (modal?.type) {
            case "repaired":
                plm_document_items[modal.key]['repaired_id'] = true;
                break;
            case "scrapped":
                plm_document_items[modal.key]['scrapped_id'] = true;
                break;
            case "planned_stopped":
                plm_document_items[modal.key]['planned_stop_id'] = true;
                break;
            case "unplanned_stopped":
                plm_document_items[modal.key]['unplanned_stop_id'] = true;
                break;
        }
        modal.display = "none";
        this.setState({plm_document_items: plm_document_items, modal: modal});
    }

    onHandleCancel = (e) => {
        let {modal} = this.state;
        modal.display = "none";
        this.setState({modal: modal});
    }

    render() {
        const {
            isLoading,
            modal,
            plm_document,
            plm_document_items,

            organisationList,
            departmentList,
            productList,
            reasonList
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
                                    <label className={"control-label"}>Organization</label>
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
                                    <label className={"control-label"}>Department</label>
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
                                    <label className={"control-label"}>Reg Date</label>
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
                                    <label className={"control-label"}>Info</label>
                                    <input onChange={this.onHandleChange.bind(this, 'input', 'plm_document', 'add_info', '', '', '')}
                                           name={'add_info'} value={plm_document?.add_info} className={'form-control'}/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div className={'card-body'}>
                        {
                            plm_document_items?.length > 0 && plm_document_items.map((item, key) => {
                                return (
                                    <div className={"border-block"} key={key}>
                                        <div className={"row"}>
                                            <div className={'col-sm-2'}>
                                                <div className={'form-group'}>
                                                    <label className={"control-label"}>Product</label>
                                                    <Select name={'product_id'}
                                                            onChange={this.onHandleChange.bind(this, 'select', 'plm_document_items', 'product_id', key, '', '')}
                                                            placeholder={"Tanlang ..."}
                                                            value={productList.filter(({value}) => +value === +item?.product_id)}
                                                            options={productList}
                                                            isClearable={true}
                                                            styles={customStyles}
                                                    />
                                                </div>
                                            </div>
                                            <div className={'col-sm-2'}>
                                                <label className={"control-label"}>Equipments</label>
                                                <div className={'row'}>
                                                    {
                                                        item?.equipmentList?.length > 0 && item.equipmentList.map((equipment, eqKey) => {
                                                            return (
                                                                <div className={'col-sm-12'} key={eqKey}>
                                                                    <div className={'form-group'}>
                                                                        <input disabled={true} type={"text"} value={equipment.name} className={'form-control background-white'}/>
                                                                    </div>
                                                                </div>
                                                            )
                                                        })
                                                    }
                                                </div>
                                            </div>
                                            <div className={'col-sm-1'}>
                                                <div className={'row'}>
                                                    <div className={'col-sm-12 text-center'}>
                                                        <div className={'form-group'}>
                                                            <label className={"control-label"}>Start work</label>
                                                            <DatePicker
                                                                name={"start_work"}
                                                                onChange={(e)=>{
                                                                    this.onHandleChange('date', 'plm_document_items', 'start_work', key, '', '', new Date(e))
                                                                }}
                                                                locale={ru}
                                                                id={'start_work'}
                                                                dateFormat="HH:mm"
                                                                className={"form-control"}
                                                                selected={item.start_work ? new Date(item.start_work) : ""}
                                                                autoComplete={'off'}
                                                                showTimeSelect
                                                                showTimeSelectOnly
                                                                timeIntervals={10}
                                                                timeCaption="Time"
                                                            />
                                                        </div>
                                                    </div>
                                                    <div className={'col-sm-12 text-center'}>
                                                        <label className={"control-label"}>End work</label>
                                                        <DatePicker
                                                            name={"end_work"}
                                                            onChange={(e)=>{
                                                                this.onHandleChange('date', 'plm_document_items', 'end_work', key, '', '', new Date(e))
                                                            }}
                                                            locale={ru}
                                                            id={'end_work'}
                                                            dateFormat="HH:mm"
                                                            className={"form-control"}
                                                            selected={item.end_work ? new Date(item.end_work) : ""}
                                                            autoComplete={'off'}
                                                            showTimeSelect
                                                            showTimeSelectOnly
                                                            timeIntervals={10}
                                                            timeCaption="Time"
                                                        />
                                                    </div>
                                                </div>
                                            </div>
                                            <div className={'col-sm-2'}>
                                                <div className={'row mb-2'}>
                                                     <div className={'col-sm-12 text-center'}>
                                                        <label className={"control-label"}>Rejali to'xtalishlar <small>(min)</small></label>
                                                     </div>
                                                    <div className={'col-sm-12 text-center'}>
                                                        <div className={'form-group'}>
                                                            <label className={'mr-4'}>100</label>
                                                            <button onClick={this.onOpenModal.bind(this, 'planned_stopped', "Rejali to'xtalishlar", key)}
                                                                    className={item.planned_stop_id ? "btn btn-xs btn-primary" : "btn btn-xs btn-success"}>
                                                                {item.planned_stop_id ? <i className={'fas fa-edit'}/> : <i className={'fa fa-plus'}/>}
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div className={'row'}>
                                                    <div className={'col-sm-12 text-center'}>
                                                        <label className={"control-label"}>Rejasiz to'xtalishlar <small>(min)</small></label>
                                                    </div>
                                                    <div className={'col-sm-12 text-center'}>
                                                        <div className={'form-group'}>
                                                            <label className={'mr-4'}>100</label>
                                                            <button onClick={this.onOpenModal.bind(this, 'unplanned_stopped', "Rejasiz to'xtalishlar", key)}
                                                                    className={item.unplanned_stop_id ? 'btn btn-primary btn-xs' : 'btn btn-success btn-xs'}>
                                                                {item.unplanned_stop_id ? <i className={'fas fa-edit'}/> : <i className={'fa fa-plus'}/>}
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div className={'col-sm-1'}>
                                                <div className={'row'}>
                                                    <div className={'col-sm-12 text-center'}>
                                                        <div className={'form-group'}>
                                                            <label className={"control-label"}>Rejada</label>
                                                            <input onChange={this.onHandleChange.bind(this, 'input', 'plm_document_items', 'qty', key, '', '')}
                                                                   type={'number'} className={'form-control'} value={item.qty}/>
                                                        </div>
                                                    </div>
                                                    <div className={'col-sm-12 text-center'}>
                                                        <label className={"control-label"}>Ish/chiq</label>
                                                        <input onChange={this.onHandleChange.bind(this, 'input', 'plm_document_items', 'fact_qty', key, '', '')}
                                                               type={'number'} className={'form-control'} value={item.fact_qty}/>
                                                    </div>
                                                </div>
                                            </div>

                                            <div className={'col-sm-2'}>
                                                <div className={'row mb-2'}>
                                                    <div className={'col-sm-12 text-center'}>
                                                        <label className={"control-label"}>Ta'mirlangan</label>
                                                    </div>
                                                    <div className={'col-sm-12 text-center'}>
                                                        <div className={'form-group'}>
                                                            <button onClick={this.onOpenModal.bind(this, 'repaired', "Ta'mirlangan", key)}
                                                                    className={item.repaired_id ? 'btn btn-primary btn-xs' : 'btn btn-success btn-xs'}>
                                                                {item.repaired_id ? <i className={'fas fa-edit'}/> : <i className={'fa fa-plus'}/>}
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div className={'row'}>
                                                    <div className={'col-sm-12 text-center'}>
                                                        <label className={"control-label"}>Yaroqsiz</label>
                                                    </div>
                                                    <div className={'col-sm-12 text-center'}>
                                                        <div className={'form-group'}>
                                                            <button  onClick={this.onOpenModal.bind(this, 'scrapped', "Yaroqsiz", key)}
                                                                     className={item.scrapped_id ? 'btn btn-primary btn-xs' : 'btn btn-success btn-xs'}>
                                                                {item.scrapped_id ? <i className={'fas fa-edit'}/> : <i className={'fa fa-plus'}/>}
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                )
                            })
                        }
                    </div>
                    <div className={'card-footer'}>
                        <button className={'btn btn-sm btn-success'}>Saqlash</button>
                    </div>
                </div>

                <div className="fade modal show" role="dialog" tabIndex="-1" style={{display: modal.display}} aria-modal="true">
                    <div className="modal-dialog modal-lg" role="document">
                        <div className="modal-content">
                            <div className="modal-header">
                                <h5>{modal.title}</h5>
                                <button onClick={(e) => {
                                    let {modal} = this.state;
                                    modal.display = "none";
                                    this.setState({modal: modal});
                                }} className="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
                            </div>
                            <div className="modal-body none-scroll">
                                <div className={'card-body'}>
                                    <div className={'row'}>
                                        <div className={'col-sm-12'}>
                                            <div className={"form-group"}>
                                                <label className={"control-label"}>Sabablar</label>
                                                <Select name={'reason_id'}
                                                        onChange={this.onHandleChange.bind(this, 'select', 'modal', 'reason_id', '', '', '')}
                                                        placeholder={"Tanlang ..."}
                                                        value={reasonList.filter(({value}) => +value === +modal.model?.reason_id)}
                                                        options={reasonList}
                                                        isClearable={true}
                                                        styles={customStyles}
                                                />
                                            </div>
                                        </div>
                                        <div className={'col-sm-6'}>
                                            <div className={"form-group"}>
                                                <label className={"control-label"}>Boshlandi</label>
                                                <DatePicker
                                                    name={"begin_date"}
                                                    onChange={(e)=>{
                                                        this.onHandleChange('date', 'modal', 'begin_date', '', '', '', new Date(e))
                                                    }}
                                                    locale={ru}
                                                    id={'begin_date'}
                                                    className={"form-control"}
                                                    selected={modal.model?.begin_date ? new Date(modal.model?.begin_date) : ""}
                                                    autoComplete={'off'}
                                                    peekNextMonth
                                                    showMonthDropdown
                                                    showYearDropdown
                                                    showTimeSelect
                                                    dateFormat="dd/MM/yyyy HH:mm"
                                                />
                                            </div>
                                        </div>
                                        <div className={'col-sm-6'}>
                                            <div className={"form-group"}>
                                                <label className={"control-label"}>Tugadi</label>
                                                <DatePicker
                                                    name={"reg_date"}
                                                    onChange={(e)=>{
                                                        this.onHandleChange('date', 'modal', 'end_time', '', '', '', new Date(e))
                                                    }}
                                                    locale={ru}
                                                    id={'reg_date'}
                                                    className={"form-control"}
                                                    selected={modal.model?.end_time ? new Date(modal.model?.end_time) : ""}
                                                    autoComplete={'off'}
                                                    peekNextMonth
                                                    showMonthDropdown
                                                    showYearDropdown
                                                    showTimeSelect
                                                    dateFormat="dd/MM/yyyy HH:mm"
                                                />
                                            </div>
                                        </div>
                                        {
                                            modal.type === 'unplanned_stopped' ?
                                                <div className={'col-sm-12'}>
                                                    <div className={"form-group"}>
                                                        <label className={"control-label"}>Bypass</label>
                                                        <input onChange={this.onHandleChange.bind(this, 'input', 'modal', 'bypass', '', '', '')}
                                                               className={"form-control"} value={modal.model?.bypass}/>
                                                    </div>
                                                </div> : ""
                                        }
                                        <div className={'col-sm-12'}>
                                            <div className={"form-group"}>
                                                <label className={"control-label"}>Izoh</label>
                                                <textarea onChange={this.onHandleChange.bind(this, 'input', 'modal', 'add_info', '', '', '')}
                                                          className={"form-control"} rows={2} value={modal.model?.add_info}/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div className={'card-footer'}>
                                    <div className={'row'}>
                                        <div className={'col-sm-12'}>
                                            <div className={"form-group"}>
                                                <button onClick={this.onHandleSave.bind(this)} className={"btn btn-sm btn-success mr-3"}>Saqlash</button>
                                                <button onClick={this.onHandleCancel.bind(this)} className={"btn btn-sm btn-danger"}>Bekor qilish</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        );
    }
}

export default Form;
