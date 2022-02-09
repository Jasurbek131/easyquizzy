import React from "react";
import {Flip, toast, ToastContainer} from 'react-toastify';
import axios from "axios";
import Select from "react-select";
import DatePicker from "react-datepicker";
import customStyles from "../../../actions/style/customStyle.js";
import ru from "date-fns/locale/ru/index.js";
import uz from "date-fns/locale/uz/index.js";
import {items} from "../../../actions/elements";
import {loadingContent, removeElement} from "../../../actions/functions";
import {Link} from "react-router-dom";

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
            displayOperator: 'none',
            plm_document: {
                id: "",
                doc_number: "",
                reg_date: "",
                shift_id: "",
                hr_department_id: "",
                hr_organisation_id: "",
                add_info: ""
            },
            plm_document_items: [JSON.parse(JSON.stringify(items))],
            organisationList: [],
            departmentList: [],
            reasonList: [],
            repairedList: [],
            scrappedList: [],
            operatorList: [],
            shiftList: [],
            productList: [{
                equipmentGroup: {equipmentGroupRelationEquipments: []}
            }],
            equipmentList: [],
            language: 'uz'
        };
    }

    async componentDidMount() {
        this._isMounted = true;
        let id = "";
        let response;
        let {history} = this.props;
        if (this.props.match.path === "/update/:id") {
            id = this.props.match.params.id;
            response = await axios.post(API_URL + 'fetch-list?type=CREATE_DOCUMENT&id='+id);
        } else {
            response = await axios.post(API_URL + 'fetch-list?type=CREATE_DOCUMENT');
        }
        if (response.data.status) {
            if (id) {
                this.setState({
                    plm_document: response.data?.plm_document,
                    plm_document_items: response.data?.plm_document?.plm_document_items,
                });
            }
            this.setState({
                organisationList: response.data.organisationList,
                departmentList: response.data.departmentList,
                productList: response.data.productList,
                equipmentList: response.data.equipmentList,
                reasonList: response.data.reasonList,
                repairedList: response.data.repaired,
                scrappedList: response.data.scrapped,
                operatorList: response.data.operatorList,
                shiftList: response.data.shiftList,
                language: response.data.language,
                isLoading: false
            });
        } else {
            toast.error(response.data.message);
            setTimeout(function () {
                history.goBack()
            }, 5000);
        }
    }

    onHandleChange = (type, model, name, key, index, value, e) => {
        let v = value;
        let element = $('#'+name);
        switch (type) {
            case "select":
                v = e?.value ?? "";
                element.children('div').css("border", "1px solid #ced4da");
                break;
            case "date":
                v = e;
                element.css("border", "1px solid #ced4da");
                break;
            case "input":
                v = e?.target?.value ?? "";
                element.css("border", "1px solid #ced4da");
                break;
        }
        let {plm_document_items, modal} = this.state;
        switch (model) {
            case "products":
                plm_document_items[key]['products'][index][name] = v;
                this.setState({plm_document_items: plm_document_items});
                break;
            case "plm_document":
                let {plm_document} = this.state;
                plm_document[name] = v;
                this.setState({plm_document: plm_document});
                break;
            case "equipment":
                plm_document_items[key]['equipmentGroup']['equipmentGroupRelationEquipments'][index][name] = v;
                this.setState({plm_document_items: plm_document_items});
                break;
            case "plm_document_items":
                if (name === 'product_id') {
                    plm_document_items[key]['products'][index] = e;
                } else {
                    plm_document_items[key][name] = v;
                }
                if (name === "start_work") {
                    plm_document_items[key]['end_work'] = "";
                }
                this.setState({plm_document_items: plm_document_items});
                break;
            case "modal":
                modal.model[name] = v;
                if (name === "begin_date") {
                    modal.model.end_time = "";
                }
                this.setState({modal: modal});
                break;
            case "repaired":
                modal['model'][index]['count'] = v;
                this.setState({modal: modal});
                break;
            case "scrapped":
                modal['model'][index]['count'] = v;
                this.setState({modal: modal});
                break;
        }
    }

    arrayUnique = (arr1, arr2) => {
        let a = arr1;
        if (arr1?.length === 0) {
            return arr2;
        }
        let isYes = true;
        if (arr2?.length > 0) {
            arr2.map((item2, key2) => {
                isYes = true;
                arr1.map((item1, key1) => {
                    if (+item2.value === +item1.value) {
                        isYes = false;
                    }
                });
                if (isYes) {
                    a.push(item2);
                }
            });
        }
        return a;
    }

    onOpenModal = (type, title, key, itemKey, e) => {
        let {plm_document_items, repairedList, scrappedList} = this.state;
        let model;
        switch (type) {
            case "planned_stopped":
                model = JSON.parse(JSON.stringify(plm_document_items[key][type]));
                break;
            case "unplanned_stopped":
                model = JSON.parse(JSON.stringify(plm_document_items[key][type]));
                break;
            case "repaired":
                model = JSON.parse(JSON.stringify(plm_document_items[key]['products'][itemKey][type]));
                model = this.arrayUnique(model, JSON.parse(JSON.stringify(repairedList)));
                plm_document_items[key]['products'][itemKey][type] = JSON.parse(JSON.stringify(model));
                this.setState({plm_document_items: plm_document_items});
                break;
            case "scrapped":
                model = JSON.parse(JSON.stringify(plm_document_items[key]['products'][itemKey][type]));
                model = this.arrayUnique(model, JSON.parse(JSON.stringify(scrappedList)));
                plm_document_items[key]['products'][itemKey][type] = JSON.parse(JSON.stringify(model));
                this.setState({plm_document_items: plm_document_items});
                break;
        }
        let modal = {
            display: "block",
            title: title,
            type: type,
            model: model,
            key: key,
            itemKey: itemKey
        }
        this.setState({modal: modal});
    }

    onHandleSave = (e) => {
        let {modal, plm_document_items} = this.state;
        if (modal.type === 'repaired' || modal.type === 'scrapped') {
            plm_document_items[modal.key]['products'][modal.itemKey][modal.type] = JSON.parse(JSON.stringify(modal.model));
        } else if (modal.type === 'planned_stopped' || modal.type === 'unplanned_stopped') {
            plm_document_items[modal.key][modal.type] = JSON.parse(JSON.stringify(modal.model));
        }
        modal.display = "none";
        this.setState({modal: modal, plm_document_items: plm_document_items});
    }

    onHandleCancel = (e) => {
        let {modal} = this.state;
        modal.display = "none";
        this.setState({modal: modal});
    }

    onReturnMin = (end, start) => {
        let m = 0;
        if (end && start) {
            m = Math.round((new Date(end).getTime() - new Date(start).getTime())/60000);
        }
        return m;
    }

    onPush = (type, model, key, index, e) => {
        let {plm_document_items} = this.state;
        switch (type) {
            case "add":
                let newItems = items;
                newItems.repaired = this.state.repairedList;
                newItems.scrapped = this.state.scrappedList;
                plm_document_items.push(JSON.parse(JSON.stringify(newItems)));
                break;
            case "remove":
                plm_document_items = removeElement(plm_document_items, key);
                break;
            case "equipment-plus":
                let equipment = {
                    label: "",
                    value: ""
                };
                plm_document_items[key]['equipmentGroup']['equipmentGroupRelationEquipments'].push(equipment);
                break;
            case "product-plus":
                let product = {
                    label: "",
                    value: "",
                    repaired: [],
                    scrapped: []
                };
                plm_document_items[key]['products'].push(JSON.parse(JSON.stringify(product)));
                break;
            case "equipment-minus":
                if (+index > 0) {
                    let elements = plm_document_items[key]['equipmentGroup']['equipmentGroupRelationEquipments'];
                    plm_document_items[key]['equipmentGroup']['equipmentGroupRelationEquipments'] = removeElement(elements, index);
                    this.setState({plm_document_items: plm_document_items});
                }
                break;
            case "product-minus":
                if (+index > 0) {
                    let elements = plm_document_items[key]['products'];
                    plm_document_items[key]['products'] = removeElement(elements, index);
                    this.setState({plm_document_items: plm_document_items});
                }
                break;
            case "operator-plus":
                let document_item_key = key;
                let equipment_key = index;
               // plm_document_items[key]['equipmentGroup']['equipmentGroupRelationEquipments'];
                this.setState({displayOperator: 'block'});
                break;
        }
        this.setState({plm_document_items: plm_document_items});
    }

    onRequiredColumns = (document, documentItems) => {
        let isEmpty = true;
        if (document?.hr_organisation_id === "") {
            isEmpty = false;
            $("#hr_organisation_id").children('div').css("border", "1px solid red");
        } else {
            $("#hr_organisation_id").children('div').css("border", "1px solid #ced4da");
        }
        if (document?.hr_department_id === "") {
            isEmpty = false;
            $("#hr_department_id").children('div').css("border", "1px solid red");
        } else {
            $("#hr_department_id").children('div').css("border", "1px solid #ced4da");
        }
        if (document?.reg_date === "") {
            isEmpty = false;
            $("#reg_date").css("border", "1px solid red");
        } else {
            $("#reg_date").css("border", "1px solid #ced4da");
        }
        return isEmpty;
    }

    onSave = async (e) => {
        let {plm_document, plm_document_items} = this.state;
        let params = {
            document: plm_document,
            document_items: plm_document_items
        };
        if (this.onRequiredColumns(plm_document, plm_document_items)) {
            const response = await axios.post(API_URL + 'save-properties?type=SAVE_DOCUMENT', params);
            if (response.data.status) {
                this.setUrl( '/index');
            } else {
                toast.error(response.data.message);
            }
        }
    }

    onSumma = (items) => {
        let summa = 0;
        if (items?.length > 0) {
            items.map((item, key) => {
                if (item?.count) {
                    summa += +item.count;
                }
            })
        }
        return summa;
    }

    setUrl = (url, e) => {
        this.props.history.push(url);
    };

    render() {
        const {
            language,
            isLoading,
            modal,
            plm_document,
            plm_document_items,

            organisationList,
            departmentList,
            productList,
            equipmentList,
            reasonList,
            operatorList,
            shiftList,
            displayOperator
        } = this.state;
        let {history} = this.props;
        if (isLoading)
            return loadingContent()
        return (
            <div>
                <div className="no-print">
                    <ToastContainer autoClose={3000} position={'top-right'} transition={Flip} draggablePercent={60} closeOnClick={true} pauseOnHover closeButton={true}/>
                </div>
                <div className={'card'}>
                    <div className={'card-header'}>
                        <div className={'row'}>
                            <div className={"col-sm-12"}>
                                <div className={'pull-right'}>
                                    <Link to={'/index'} className={"btn btn-sm btn-warning"}>Orqaga</Link>
                                </div>
                            </div>
                        </div>
                        <div className={'row'}>
                            <div className={'col-sm-2'}>
                                <div className={'form-group'}>
                                    <label className={"control-label"}>Organization</label>
                                    <Select className={"aria-required"}
                                            id={"hr_organisation_id"}
                                            onChange={this.onHandleChange.bind(this, 'select', 'plm_document', 'hr_organisation_id', '', '', '')}
                                            placeholder={"Tanlang ..."}
                                            value={organisationList.filter(({value}) => +value === +plm_document?.hr_organisation_id)}
                                            options={organisationList}
                                            styles={customStyles}
                                    />
                                </div>
                            </div>
                            <div className={'col-sm-2'}>
                                <div className={'form-group'}>
                                    <label className={"control-label"}>Department</label>
                                    <Select className={"aria-required"}
                                            id={"hr_department_id"}
                                            onChange={this.onHandleChange.bind(this, 'select', 'plm_document', 'hr_department_id', '', '', '')}
                                            placeholder={"Tanlang ..."}
                                            value={departmentList.filter(({value}) => +value === +plm_document?.hr_department_id)}
                                            options={departmentList}
                                            styles={customStyles}
                                    />
                                </div>
                            </div>
                            <div className={'col-sm-2'}>
                                <div className={'form-group'}>
                                    <label className={"control-label"}>Sana</label>
                                    <DatePicker onChange={(e)=>{
                                                    this.onHandleChange('date', 'plm_document', 'reg_date', '', '', '', new Date(e))
                                                }}
                                                id={"reg_date"}
                                                locale={language === "uz" ? uz : ru}
                                                dateFormat="dd.MM.yyyy"
                                                className={"form-control aria-required"}
                                                selected={plm_document.reg_date ? new Date(plm_document.reg_date) : ""}
                                                autoComplete={'off'}
                                                peekNextMonth
                                                showMonthDropdown
                                                showYearDropdown
                                    />
                                </div>
                            </div>
                            <div className={'col-sm-2'}>
                                <div className={'form-group'}>
                                    <label className={"control-label"}>Smena</label>
                                    <Select className={"aria-required"}
                                            id={"shift_id"}
                                            onChange={this.onHandleChange.bind(this, 'select', 'plm_document', 'shift_id', '', '', '')}
                                            placeholder={"Tanlang ..."}
                                            value={shiftList.filter(({value}) => +value === +plm_document?.shift_id)}
                                            options={shiftList}
                                            styles={customStyles}
                                    />
                                </div>
                            </div>
                            <div className={'col-sm-4'}>
                                <div className={'form-group'}>
                                    <label className={"control-label"}>Izoh</label>
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
                                        {
                                            key === 0 ?
                                                <div className={'pull-right'}>
                                                    <button onClick={this.onPush.bind(this, 'add', 'plm_document_items', '', '')}
                                                            className={"btn btn-xs btn-primary"}>
                                                        <i className={"fa fa-plus"}/>
                                                    </button>
                                                </div>
                                                :
                                                <div className={"pull-right"}>
                                                    <button onClick={this.onPush.bind(this, 'remove', 'plm_document_items', key, '')}
                                                            className={"btn btn-xs btn-danger"}>
                                                        <i className={"fa fa-times"}/>
                                                    </button>
                                                </div>
                                        }
                                        <div className={"row"}>
                                            <div className={'col-sm-3'}>
                                                <div className={'row'}>
                                                    <div className={'col-sm-11 mb-1'}>
                                                        <label className={"control-label"}>Qurilmalar</label>
                                                    </div>
                                                    <div className={"col-sm-1 pl-0 mb-1 text-left"}>
                                                        <button onClick={this.onPush.bind(this, 'equipment-plus', 'plm_document_items', key, '')}
                                                                className={"btn btn-xs wh-28 btn-info"}>
                                                            <i className={"fa fa-plus"}/>
                                                        </button>
                                                    </div>
                                                    {
                                                        item?.equipmentGroup?.equipmentGroupRelationEquipments?.length > 0 &&
                                                        item.equipmentGroup.equipmentGroupRelationEquipments.map((equipment, eqKey) => {
                                                            return (
                                                                <React.Fragment key={eqKey}>
                                                                    <div className={'col-sm-9 pr-0 mb-2'}>
                                                                        <Select className={"aria-required"}
                                                                                onChange={this.onHandleChange.bind(this, 'select', 'equipment', 'value', key, eqKey, '')}
                                                                                placeholder={"Tanlang ..."}
                                                                                value={equipmentList.filter(({value}) => +value === +equipment?.value)}
                                                                                options={equipmentList}
                                                                                styles={customStyles}
                                                                        />
                                                                    </div>
                                                                    <div className={'col-sm-2 pl-0 mb-2'}>
                                                                        <button onClick={this.onPush.bind(this, 'operator-plus', 'plm_document_items', key, eqKey)}
                                                                                className={"btn btn-xs btn-default w-100 h-100"}>
                                                                            <i className={"fa fa-user-plus"}/>
                                                                        </button>
                                                                    </div>
                                                                    <div className={'col-sm-1 pl-0 text-left mb-2'}>
                                                                        <button onClick={this.onPush.bind(this, 'equipment-minus', 'plm_document_items', key, eqKey)}
                                                                                className={"btn btn-xs wh-28 btn-outline-danger"}>
                                                                            <i className={"fa fa-times"}/>
                                                                        </button>
                                                                    </div>
                                                                </React.Fragment>
                                                            )
                                                        })
                                                    }
                                                </div>
                                            </div>
                                            <div className={'col-sm-6'}>
                                                <div className={'row'}>
                                                    <div className={'col-sm-4'}>
                                                        <div className={'row'}>
                                                            <div className={'col-sm-10 mb-1'}>
                                                                <label className={"control-label"}>Maxsulotlar</label>
                                                            </div>
                                                            <div className={"col-sm-2 mb-1"}>
                                                                <button onClick={this.onPush.bind(this, 'product-plus', 'plm_document_items', key, '')}
                                                                        className={"btn btn-xs btn-info wh-28"}>
                                                                    <i className={"fa fa-plus"}/>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div className={'col-sm-2 text-center'}>
                                                        <label className={"control-label"}>Rejada</label>
                                                    </div>
                                                    <div className={'col-sm-2 text-center'}>
                                                        <label className={"control-label"}>Ish/chiq</label>
                                                    </div>
                                                    <div className={'col-sm-2 text-center'}>
                                                        <label className={"control-label"}>Ta'mirlangan</label>
                                                    </div>
                                                    <div className={'col-sm-2 text-center'}>
                                                        <label className={"control-label"}>Yaroqsiz</label>
                                                    </div>
                                                </div>

                                                {
                                                    item?.products?.length > 0 &&
                                                    item.products.map((product, prKey) => {
                                                        return (
                                                            <div className={'row'} key={prKey}>
                                                                <div className={'col-sm-4'}>
                                                                    <div className={'row'}>
                                                                        <div className={'col-sm-10 pr-0 mb-2'}>
                                                                            <Select className={"aria-required"}
                                                                                    onChange={this.onHandleChange.bind(this, 'select', 'products', 'product_id', key, prKey, '')}
                                                                                    placeholder={"Tanlang ..."}
                                                                                    value={productList.filter(({value}) => +value === +product?.product_id)}
                                                                                    options={productList}
                                                                                    styles={customStyles}
                                                                            />
                                                                        </div>
                                                                        <div className={'col-sm-2 mb-2'}>
                                                                            <button onClick={this.onPush.bind(this, 'product-minus', 'products', key, prKey)}
                                                                                    className={"btn btn-xs wh-28 btn-outline-danger"}>
                                                                                <i className={"fa fa-times"}/>
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div className={'col-sm-2'}>
                                                                    <input onChange={this.onHandleChange.bind(this, 'input', 'products', 'qty', key, prKey, '')}
                                                                           type={'number'} className={'form-control aria-required'} value={product?.qty}/>
                                                                </div>
                                                                <div className={'col-sm-2'}>
                                                                    <input onChange={this.onHandleChange.bind(this, 'input', 'products', 'fact_qty', key, prKey, '')}
                                                                           type={'number'} className={'form-control aria-required'} value={product?.fact_qty}/>
                                                                </div>
                                                                <div className={'col-sm-2 text-center'}>
                                                                    <label className={'mr-2'}>{this.onSumma(product.repaired)}</label>
                                                                    <button onClick={this.onOpenModal.bind(this, 'repaired', "Ta'mirlangan", key, prKey)}
                                                                            className={'btn btn-success btn-xs wh-28'}>
                                                                        <i className={'fa fa-plus'}/>
                                                                    </button>
                                                                </div>

                                                                <div className={'col-sm-2 text-center'}>
                                                                    <label className={'mr-2'}>{this.onSumma(product.scrapped)}</label>
                                                                    <button  onClick={this.onOpenModal.bind(this, 'scrapped', "Yaroqsiz", key, prKey)}
                                                                             className={'btn btn-success btn-xs wh-28'}>
                                                                        <i className={'fa fa-plus'}/>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        )
                                                    })
                                                }
                                            </div>

                                            <div className={'col-sm-1'}>
                                                <div className={"align-center"}>
                                                    <div className={'row'}>
                                                        <div className={'col-sm-12 text-center'}>
                                                            <label className={"control-label"}>Boshlanishi</label>
                                                            <DatePicker locale={ru}
                                                                        dateFormat="HH:mm"
                                                                        onChange={(e)=>{
                                                                            this.onHandleChange('date', 'plm_document_items', 'start_work', key, '', '', new Date(e))
                                                                        }}
                                                                        className={"form-control aria-required"}
                                                                        selected={item.start_work ? new Date(item.start_work) : ""}
                                                                        autoComplete={'off'}
                                                                        showTimeSelect
                                                                        showTimeSelectOnly
                                                                        timeIntervals={10}
                                                                        timeCaption="Вақт"
                                                            />
                                                        </div>
                                                        <div className={"col-sm-12 text-center mt-2 mb-1"}>
                                                            <label>{this.onReturnMin(item.end_work, item.start_work)} <small>min</small></label>
                                                        </div>
                                                        <div className={'col-sm-12 text-center'}>
                                                            <label className={"control-label"}>Tugashi</label>
                                                            <DatePicker locale={ru}
                                                                        dateFormat="HH:mm"
                                                                        className={"form-control aria-required"}
                                                                        onChange={(e)=>{
                                                                            this.onHandleChange('date', 'plm_document_items', 'end_work', key, '', '', new Date(e))
                                                                        }}
                                                                        selected={item.end_work ? new Date(item.end_work) : ""}
                                                                        filterTime={(e) => {return new Date(item.start_work).getTime() < new Date(e).getTime()}}
                                                                        autoComplete={'off'}
                                                                        showTimeSelect
                                                                        showTimeSelectOnly
                                                                        timeIntervals={10}
                                                                        timeCaption="Вақт"
                                                            />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div className={'col-sm-1'}>
                                                <div className={"align-center"}>
                                                    <div className={'row'}>
                                                         <div className={'col-sm-12 text-center'}>
                                                            <label className={"control-label"}>Rejali to'xtalishlar</label>
                                                         </div>
                                                        <div className={'col-sm-12 text-center'}>
                                                            <label className={'mr-2'}>{this.onReturnMin(item?.planned_stopped?.end_time, item?.planned_stopped?.begin_date)} <small>min</small></label>
                                                            <button onClick={this.onOpenModal.bind(this, 'planned_stopped', "Rejali to'xtalishlar", key, '')}
                                                                    className={"btn btn-xs btn-success"}>
                                                                <i className={'fa fa-plus'}/>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div className={'col-sm-1'}>
                                                <div className={"align-center"}>
                                                    <div className={'row'}>
                                                        <div className={'col-sm-12 text-center'}>
                                                            <label className={"control-label"}>Rejasiz to'xtalishlar</label>
                                                        </div>
                                                        <div className={'col-sm-12 text-center'}>
                                                            <label className={'mr-2'}>{this.onReturnMin(item?.unplanned_stopped?.end_time, item?.unplanned_stopped?.begin_date)} <small>min</small></label>
                                                            <button onClick={this.onOpenModal.bind(this, 'unplanned_stopped', "Rejasiz to'xtalishlar", key, '')}
                                                                    className={'btn btn-success btn-xs'}>
                                                                <i className={'fa fa-plus'}/>
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
                </div>

                <div className="fade modal show" role="dialog" tabIndex="-1" style={{display: displayOperator}} aria-modal="true">
                    <div className="modal-dialog modal-lg" role="document">
                        <div className="modal-content">
                            <div className="modal-header">
                                <h5>{modal.title}</h5>
                                <button onClick={(e) => {
                                    this.setState({displayOperator: 'none'});
                                }} className="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
                            </div>
                            <div className="modal-body none-scroll">
                                <div className={'card-body'}>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div className="fade modal show" role="dialog" tabIndex="-1" style={{display: modal?.display}} aria-modal="true">
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
                                    {
                                        modal.type === "planned_stopped" || modal.type === "unplanned_stopped" ?
                                            <div className={'row mb-5'}>
                                                <div className={'col-sm-12'}>
                                                    <div className={"form-group"}>
                                                        <label className={"control-label"}>Sabablar</label>
                                                        <Select onChange={this.onHandleChange.bind(this, 'select', 'modal', 'reason_id', '', '', '')}
                                                                placeholder={"Tanlang ..."}
                                                                value={reasonList.filter(({value}) => +value === +modal?.model?.reason_id)}
                                                                options={reasonList}
                                                                styles={customStyles}
                                                        />
                                                    </div>
                                                </div>
                                                <div className={'col-sm-6'}>
                                                    <div className={"form-group"}>
                                                        <label className={"control-label"}>Boshlandi</label>
                                                        <DatePicker onChange={(e) => {
                                                                        this.onHandleChange('date', 'modal', 'begin_date', '', '', '', new Date(e))
                                                                    }}
                                                                    locale={ru}
                                                                    className={"form-control"}
                                                                    selected={modal.model?.begin_date ? new Date(modal.model?.begin_date) : ""}
                                                                    autoComplete={'off'}
                                                                    peekNextMonth
                                                                    showMonthDropdown
                                                                    showYearDropdown
                                                                    showTimeSelect
                                                                    dateFormat="dd/MM/yyyy HH:mm"
                                                                    timeCaption="Вақт"
                                                        />
                                                    </div>
                                                </div>
                                                <div className={'col-sm-6'}>
                                                    <div className={"form-group"}>
                                                        <label className={"control-label"}>Tugadi</label>
                                                        <DatePicker onChange={(e) => {
                                                                        this.onHandleChange('date', 'modal', 'end_time', '', '', '', new Date(e))
                                                                    }}
                                                                    locale={ru}
                                                                    className={"form-control"}
                                                                    selected={modal.model?.end_time ? new Date(modal.model?.end_time) : ""}
                                                                    autoComplete={'off'}
                                                                    minDate={modal.model?.begin_date ? new Date(modal.model?.begin_date) : ""}
                                                                    filterTime={(e) => {return new Date(modal.model?.begin_date).getTime() < new Date(e).getTime()}}
                                                                    peekNextMonth
                                                                    showMonthDropdown
                                                                    showYearDropdown
                                                                    showTimeSelect
                                                                    dateFormat="dd/MM/yyyy HH:mm"
                                                                    timeCaption="Вақт"
                                                        />
                                                    </div>
                                                </div>
                                                {
                                                    modal.type === 'unplanned_stopped' ?
                                                        <div className={'col-sm-12'}>
                                                            <div className={"form-group"}>
                                                                <label className={"control-label"}>Bypass</label>
                                                                <input onChange={this.onHandleChange.bind(this, 'input', 'modal', 'bypass', '', '', '')}
                                                                       className={"form-control"} value={modal?.model?.bypass}/>
                                                            </div>
                                                        </div> : ""
                                                }
                                                <div className={'col-sm-12'}>
                                                    <div className={"form-group"}>
                                                        <label className={"control-label"}>Izoh</label>
                                                        <textarea onChange={this.onHandleChange.bind(this, 'input', 'modal', 'add_info', '', '', '')}
                                                                  className={"form-control"} rows={4} value={modal?.model?.add_info}/>
                                                    </div>
                                                </div>
                                            </div>
                                            :
                                            <div className={'row'}>
                                                {
                                                    (modal.type === "repaired" || modal.type === "scrapped") && modal?.model?.length > 0 && modal.model.map((item, itemKey) => {
                                                        return (
                                                            <div className={"col-sm-6"} key={itemKey}>
                                                                <div className={"form-group"}>
                                                                    <label>{item.label}</label>
                                                                    <input onChange={this.onHandleChange.bind(this, 'input', modal.type, 'count', modal.key, itemKey, '')}
                                                                           type={"number"} className={"form-control"} value={item?.count ?? 0}/>
                                                                </div>
                                                            </div>
                                                        )
                                                    })
                                                }
                                            </div>
                                    }
                                </div>
                                <div className={'card-footer mt-5'}>
                                    <div className={'row'}>
                                        <div className={'col-sm-12'}>
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
        );
    }
}

export default Form;
