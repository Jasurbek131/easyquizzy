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
            appearance: {
                title: "",
                display: "none",
                variables: {},
                variableItems: [{}],
            },
            temporarily: {
                title: "",
                display: "none",
                type: "",
                item: {},
                key: "",
                itemKey: "",
                store: {}
            },
            displayOperator: 'none',
            plm_document: {
                id: "",
                doc_number: "",
                reg_date: "",
                shift_id: "",
                hr_department_id: "",
                organisation_id: "",
                add_info: ""
            },
            plm_document_items: [JSON.parse(JSON.stringify(items))],
            // organisationList: [],
            departmentList: [],
            reasonList: [],
            repairedList: [],
            scrappedList: [],
            equipmentGroupList: [],
            shiftList: [],
            timeTypeList: [],
            language: 'uz'
        };
    };

    async componentDidMount() {
        this._isMounted = true;
        let id = "";
        let response;
        let {history} = this.props;
        if (this.props.match.path === "/update/:id") {
            id = this.props.match.params.id;
            response = await axios.post(API_URL + 'fetch-list?type=CREATE_DOCUMENT&id=' + id);
        } else {
            response = await axios.post(API_URL + 'fetch-list?type=CREATE_DOCUMENT');
        }
        if (response.data.status) {

            if (id) {
                let departments = response.data.departmentList.filter(({value}) => +value === +response.data?.plm_document?.hr_department_id) ?? [];
                // let departmentList = organisations ? (organisations[0]?.departments ?? []) : [];
                // let departments = departmentList.filter(({value}) => +value === +response.data?.plm_document?.hr_department_id) ?? [];
                let shiftList = departments ? (departments[0]?.shifts ?? []) : [];

                this.setState({
                    plm_document: response.data?.plm_document,
                    plm_document_items: response.data?.plm_document?.plm_document_items,
                    shiftList: shiftList,
                    // departmentList: departmentList,
                    // organisationList: response.data.organisationList,
                });
            }

            let {plm_document, shiftList} = this.state;
            if (!plm_document.hr_department_id) { // organisation id tanlanmagan bo'lsa default chiqarib qoyish
                plm_document.hr_department_id = response?.data?.departmentList[0] ? response?.data?.departmentList[0]['value'] : "";
                shiftList = response?.data?.departmentList[0] ? response?.data?.departmentList[0]["shifts"] : [];
            }

            if (!plm_document.reg_date) {
                plm_document.reg_date = response.data.today;
            }

            this.setState({
                // organisationList: response.data.organisationList,
                equipmentGroupList: response.data.equipmentGroupList,
                timeTypeList: response.data.timeTypeList,
                reasonList: response.data.reasonList,
                repairedList: response.data.repaired,
                scrappedList: response.data.scrapped,
                plm_document: plm_document,
                departmentList: response.data.departmentList,
                shiftList: shiftList,
                language: response.data.language,
                isLoading: false
            });
        } else {
            toast.error(response.data.message);
            setTimeout(function () {
                history.goBack()
            }, 5000);
        }
    };

    onPlanSummary = (item) => {
        let diff = this.onReturnMin(item?.end_work, item?.start_work);
        let planned = this.onReturnMin(item?.planned_stopped?.end_time, item?.planned_stopped?.begin_date);
        let unplanned = this.onReturnMin(item?.unplanned_stopped?.end_time, item?.unplanned_stopped?.begin_date);
        let lifecycle = item ? (item.lifecycle ? item.lifecycle : 1) : 1;
        item.target_qty = ((diff - planned - unplanned) * 60 / (+lifecycle)).toFixed(0);
        return item;
    };

    onHandleChange = (type, model, name, key, index, value, e) => {
        let v = value;
        let element = $('#' + name);
        let {plm_document_items, temporarily} = this.state;
        switch (type) {
            case "select":
                v = e?.value ?? "";
                element.children('div').css("border", "1px solid #ced4da");
                break;
            case "multi-select":
                v = e;
                element.children('div').css("border", "1px solid #ced4da");
                break;
            case "date":
                v = e;
                element.css("border", "1px solid #ced4da");
                break;
            case "input":
            case "textarea":
                v = e?.target?.value ?? "";
                element.css("border", "1px solid #ced4da");
                break;
        }
        switch (model) {
            case "products":
                if (name === 'product_id') {
                    $("#product_lifecycle_id").children('div').css("border", "1px solid #ced4da");
                    $("#qty").css("border", "1px solid #ced4da");
                    // e.repaired = [];
                    // e.scrapped = [];
                    plm_document_items[key]['products'][index][name] = v;
                    plm_document_items[key]['products'][index]["repaired"] = [];
                    plm_document_items[key]['products'][index]["scrapped"] = [];
                    // plm_document_items[key] = this.onPlanSummary(plm_document_items[key]);
                } else {
                    plm_document_items[key]['products'][index][name] = v;
                }
                this.setState({plm_document_items: plm_document_items});
                break;
            case "plm_document":
                let {plm_document} = this.state;
                plm_document[name] = v;
                if (name === 'organisation_id') {
                    plm_document['hr_department_id'] = "";
                    this.setState({departmentList: e?.departments ?? []});
                }
                if (name === 'hr_department_id') {
                    plm_document_items['shift_id'] = "";
                    this.setState({shiftList: e?.shifts});
                }
                this.setState({plm_document: plm_document});
                break;
            case "plm_document_items":
                plm_document_items[key][name] = v;
                if (name === 'equipment_group_id') {
                    plm_document_items[key]['equipmentGroup'] = e;
                    plm_document_items[key]['equipments'] = [];
                    plm_document_items[key]['lifecycle'] = e?.lifecycles?.lifecycle;
                    plm_document_items[key]['bypass'] = e?.lifecycles?.bypass;
                }
                if (name === 'product_id') {
                    plm_document_items[key]['products'][index] = e;
                }
                if (name === "start_work") {
                    plm_document_items[key]['end_work'] = "";
                }
                if (name === "end_work") {
                    plm_document_items[key] = this.onPlanSummary(plm_document_items[key]);
                }
                this.setState({plm_document_items: plm_document_items});
                break;
            case "temporarily":
                temporarily.store[name] = v;
                if (name === "begin_date") {
                    temporarily.store.end_time = "";
                }
                this.setState({temporarily: temporarily});
                break;
            case "repaired":
                temporarily['store'][index]['count'] = v;
                this.setState({temporarily: temporarily});
                break;
            case "scrapped":
                temporarily['store'][index]['count'] = v;
                this.setState({temporarily: temporarily});
                break;
        }
    };

    arrayUnique = (arr1, arr2) => {
        let a = JSON.parse(JSON.stringify(arr1));
        arr2 = JSON.parse(JSON.stringify(arr2));
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
    };

    onOpenModal = (type, title, key, itemKey, e) => {
        let {plm_document_items, repairedList, scrappedList} = this.state;
        let store;
        let block = "none";
        switch (type) {
            case "planned_stopped":
                store = plm_document_items[key][type];
                if (plm_document_items[key]['start_work'] && plm_document_items[key]['end_work']) {
                    block = "block";
                } else {
                    toast.error("«Boshlanish» va «Tugash» vaqtlarini kiriting!");
                }
                break;
            case "unplanned_stopped":
                store = plm_document_items[key][type];
                if (plm_document_items[key]['start_work'] && plm_document_items[key]['end_work']) {
                    block = "block";
                } else {
                    toast.error("«Boshlanish» va «Tugash» vaqtlarini kiriting!");
                }
                break;
            case "repaired":
                store = plm_document_items[key]['products'][itemKey][type];
                store = this.arrayUnique(store, repairedList);
                plm_document_items[key]['products'][itemKey][type] = store;
                this.setState({plm_document_items: plm_document_items});
                block = "block";
                break;
            case "scrapped":
                store = plm_document_items[key]['products'][itemKey][type];
                store = this.arrayUnique(store, scrappedList);
                plm_document_items[key]['products'][itemKey][type] = store;
                this.setState({plm_document_items: plm_document_items});
                block = "block";
                break;
        }
        let temporarily = {
            title: title,
            display: block,
            type: type,
            store: JSON.parse(JSON.stringify(store)),
            item: JSON.parse(JSON.stringify(plm_document_items[key])),
            key: key,
            itemKey: itemKey
        };
        this.setState({temporarily: temporarily});
    };

    onHandleSave = (e) => {
        let {temporarily, plm_document_items} = this.state;
        let stored = temporarily.store;
        if (temporarily.type === 'repaired' || temporarily.type === 'scrapped') {
            plm_document_items[temporarily.key]['products'][temporarily.itemKey][temporarily.type] = JSON.parse(JSON.stringify(temporarily.store));
            temporarily.display = "none";
            this.setState({temporarily: temporarily, plm_document_items: plm_document_items});
        }

        if (temporarily.type === 'planned_stopped') {
            let isSave = true;
            if (stored.reason_id === "") {
                isSave = false;
                $('#reason_id').children('div').css("border", "1px solid red");
            }
            if (stored.begin_date === "") {
                isSave = false;
                $('#begin_date').css("border", "1px solid red");
            }
            if (stored.end_time === "") {
                isSave = false;
                $('#end_time').css("border", "1px solid red");
            }
            if (isSave) {
                plm_document_items[temporarily.key][temporarily.type] = JSON.parse(JSON.stringify(temporarily.store));
                plm_document_items[temporarily.key] = this.onPlanSummary(plm_document_items[temporarily.key]);
                temporarily.display = "none";
                this.setState({temporarily: temporarily, plm_document_items: plm_document_items});
            }
        }

        if (temporarily.type === 'unplanned_stopped') {
            let isSave = true;
            if (stored.bypass === "") {
                isSave = false;
                $('#bypass').css("border", "1px solid red");
            }
            if (stored.reason_id === "") {
                isSave = false;
                $('#reason_id').children('div').css("border", "1px solid red");
            }
            if (stored.begin_date === "") {
                isSave = false;
                $('#begin_date').css("border", "1px solid red");
            }
            if (stored.end_time === "") {
                isSave = false;
                $('#end_time').css("border", "1px solid red");
            }
            if (isSave) {
                plm_document_items[temporarily.key][temporarily.type] = JSON.parse(JSON.stringify(temporarily.store));
                plm_document_items[temporarily.key] = this.onPlanSummary(plm_document_items[temporarily.key]);
                temporarily.display = "none";
                this.setState({temporarily: temporarily, plm_document_items: plm_document_items});
            }
        }
    };

    onHandleCancel = (e) => {
        let {appearance} = this.state;
        appearance.display = "none";
        this.setState({appearance: appearance});
    };

    onReturnMin = (end, start) => {
        let m = 0;
        if (end && start) {
            m = Math.round((new Date(end).getTime() - new Date(start).getTime()) / 60000);
        }
        return m;
    };

    onPush = async (type, model, key, index, e) => {
        let {plm_document_items} = this.state;
        switch (type) {
            // case "equipment-group-plus":
            //     appearance = {
            //         display: "block",
            //         type: "equipment-group",
            //         title: "Qurilmalar guruhi yaratish",
            //         variables: {name: "", value: ""},
            //         equipmentList: this.state.equipmentList,
            //         variableItems: [{
            //             equipment_id: ""
            //         }]
            //     };
            //     this.setState({appearance: appearance});
            //     break;
            // case "product-lifecycle-plus":
            //     appearance = {
            //         display: "block",
            //         type: "product-lifecycle",
            //         key: key,
            //         title: "Create Product Lifecycle",
            //         variables: {
            //             product_id: "",
            //             equipment_group_id: plm_document_items[key]['equipmentGroup']['id'],
            //             lifecycle: "",
            //             bypass: ""
            //         },
            //         productList: this.state.productList,
            //         equipmentGroupList: this.state.equipmentGroupList,
            //         timeTypeList: this.state.timeTypeList
            //     };
            //     this.setState({appearance: appearance});
            //     break;
            case "add":
                let newItems = items;
                newItems.repaired = this.state.repairedList;
                newItems.scrapped = this.state.scrappedList;
                plm_document_items.push(JSON.parse(JSON.stringify(newItems)));
                break;
            case "remove":
                if (plm_document_items[key]["id"]) {
                    if (confirm("Rostdan ham o'chirmoqchimisiz?")) {
                        let response = await axios.post(API_URL + 'save-properties?type=DELETE_DOCUMENT_ITEM', {
                            plm_document_items: plm_document_items[key]
                        });
                        if (response.data.status) {
                            plm_document_items = removeElement(plm_document_items, key);
                            toast.success(response.data.message);
                        } else {
                            toast.error(response.data.message);
                        }
                    }
                } else {
                    plm_document_items = removeElement(plm_document_items, key);
                }
                break;
            case "product-plus":
                let product = {
                    label: "",
                    fact_qty: "",
                    qty: "",
                    value: "",
                    repaired: [],
                    scrapped: []
                };
                plm_document_items[key]['products'].push(JSON.parse(JSON.stringify(product)));
                break;
            case "product-minus":
                if (+index > 0) {
                    let elements = plm_document_items[key]['products'];
                    plm_document_items[key]['products'] = removeElement(elements, index);
                    this.setState({plm_document_items: plm_document_items});
                }
                break;
        }
        this.setState({plm_document_items: plm_document_items});
    };

    onRequiredColumns = (document, documentItems) => {
        let isEmpty = this.onRequiredDoc(document);
        if (documentItems?.length > 0) {
            documentItems.map((item, key) => {
                isEmpty = this.onRequiredDocItem(item, key);
            })
        }
        return isEmpty;
    };

    onRequiredDocItem(item, key) {
        let isEmpty = true;
        if (item.equipment_group_id === "") {
            isEmpty = false;
            $("#equipment_group_id_" + key).children('div').css("border", "1px solid red");
        }
        if (item.equipments.length <= 0) {
            isEmpty = false;
            $("#equipments_" + key).children('div').css("border", "1px solid red");
        }
        if (item.start_work === "") {
            isEmpty = false;
            $("#start_work_" + key).css("border", "1px solid red");
        }
        if (item.end_work === "") {
            isEmpty = false;
            $("#end_work_" + key).css("border", "1px solid red");
        }
        if (item?.products?.length > 0) {
            item.products.map((product, proKey) => {
                if (product.product_id === "") {
                    isEmpty = false;
                    $("#product_id_" + key + "_" + proKey).children('div').css("border", "1px solid red");
                }
                if (product.fact_qty === "") {
                    isEmpty = false;
                    $("#fact_qty_" + key + "_" + proKey).css("border", "1px solid red");
                }
            })
        }
        return isEmpty;
    }

    onRequiredDoc(document) {
        let isEmpty = true;
        // if (document?.organisation_id === "") {
        //     isEmpty = false;
        //     $("#organisation_id").children('div').css("border", "1px solid red");
        // }
        if (document?.hr_department_id === "") {
            isEmpty = false;
            $("#hr_department_id").children('div').css("border", "1px solid red");
        }
        if (document?.shift_id === "") {
            isEmpty = false;
            $("#shift_id").children('div').css("border", "1px solid red");
        }
        if (document?.reg_date === "") {
            isEmpty = false;
            $("#reg_date").css("border", "1px solid red");
        }
        return isEmpty;
    }

    onSave = async (key, e) => {
        let {plm_document, plm_document_items} = this.state;
        let params = {
            document: plm_document,
            document_items: [plm_document_items[key]]
        };
        if (this.onRequiredDoc(plm_document) && this.onRequiredDocItem(plm_document_items[key], key)) {
            const response = await axios.post(API_URL + 'save-properties?type=SAVE_DOCUMENT', params);
            if (response.data.status) {
                toast.success(response.data.message);
                plm_document["id"] = response.data.doc_id;
                plm_document_items[key]["id"] = response.data.doc_item_id;
                this.setState({plm_document_items, plm_document});
            } else {
                toast.error(response.data.message);
            }
        }
    };

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
    };

    setUrl = (url, e) => {
        this.props.history.push(url);
    };

    onChangeProps = (type, model) => {
        let {appearance} = this.state;
        appearance[type] = model;
        this.setState({appearance: appearance});
    };

    onSaveProps = async (type, model) => {

    };

    render() {
        const {
            language,
            isLoading,
            temporarily,
            plm_document,
            plm_document_items,
            // organisationList,
            departmentList,
            equipmentGroupList,
            reasonList,
            shiftList
        } = this.state;
        if (isLoading)
            return loadingContent();
        return (
            <div>
                <div className="no-print">
                    <ToastContainer autoClose={3000} position={'top-center'} transition={Flip} draggablePercent={60}
                                    closeOnClick={true} pauseOnHover closeButton={true}/>
                </div>
                <div className={'card'}>

                    <div className={'card-header'}>
                        <div className={'row'}>
                            <div className={"col-lg-12"}>
                                <div className={'pull-right'}>
                                    <Link to={'/index'} className={"btn btn-sm btn-warning"}>Orqaga</Link>
                                </div>
                            </div>
                        </div>
                        <div className={'row'}>
                            {/*<div className={'col-lg-2'}>*/}
                            {/*    <div className={'form-group'}>*/}
                            {/*        <label className={"control-label"}>Tashkilot</label>*/}
                            {/*        <Select className={"aria-required"}*/}
                            {/*                id={"organisation_id"}*/}
                            {/*                onChange={this.onHandleChange.bind(this, 'select', 'plm_document', 'organisation_id', '', '', '')}*/}
                            {/*                placeholder={"Tanlang ..."}*/}
                            {/*                value={organisationList.filter(({value}) => +value === +plm_document?.organisation_id)}*/}
                            {/*                options={organisationList}*/}
                            {/*                styles={customStyles}*/}
                            {/*        />*/}
                            {/*    </div>*/}
                            {/*</div>*/}
                            <div className={'col-lg-3'}>
                                <div className={'form-group'}>
                                    <label className={"control-label"}>Bo'lim</label>
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
                            <div className={'col-lg-3'}>
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
                            <div className={'col-lg-3'}>
                                <div className={'form-group'}>
                                    <label className={"control-label"}>Sana</label>
                                    <DatePicker onChange={(e) => {
                                        this.onHandleChange('date', 'plm_document', 'reg_date', '', '', '', new Date(e))
                                    }}
                                                id={"reg_date"}
                                                locale={language === "uz" ? uz : ru}
                                                dateFormat="dd.MM.yyyy"
                                                className={"form-control aria-required"}
                                                selected={plm_document?.reg_date ? new Date(plm_document.reg_date) : ""}
                                                autoComplete={'off'}
                                                peekNextMonth
                                                showMonthDropdown
                                                showYearDropdown
                                    />
                                </div>
                            </div>
                            <div className={'col-lg-3'}>
                                <div className={'form-group'}>
                                    <label className={"control-label"}>Izoh</label>
                                    <textarea
                                        onChange={this.onHandleChange.bind(this, 'textarea', 'plm_document', 'add_info', '', '', '')}
                                        name={'add_info'}
                                        value={plm_document?.add_info}
                                        className={'form-control'}
                                        rows={"1"}
                                    />
                                </div>
                            </div>
                        </div>
                    </div>


                    <div className={'card-body'}>
                        {
                            plm_document_items?.length > 0 && plm_document_items.map((item, key) => {
                                return (
                                    <div className={"border-block"} key={key}>
                                        <div className={'pull-right'}>
                                            {
                                                key === 0 ?
                                                    <button
                                                        onClick={this.onPush.bind(this, 'add', 'plm_document_items', '', '')}
                                                        className={"btn btn-xs btn-primary"}>
                                                        <i className={"fa fa-plus"}/>
                                                    </button>
                                                    :
                                                    <button
                                                        onClick={this.onPush.bind(this, 'remove', 'plm_document_items', key, '')}
                                                        className={"btn btn-xs btn-danger"}>
                                                        <i className={"fa fa-times"}/>
                                                    </button>

                                            }
                                            <br/>
                                            <br/>
                                            <button onClick={this.onSave.bind(this, key)}
                                                    className={"btn btn-xs btn-success"}>
                                                <i className={"fa fa-check"}/>
                                            </button>
                                        </div>

                                        <div className={"row"}>
                                            <div className={'col-lg-2'}>
                                                <div className={"align-center"}>
                                                    <div className={'row'}>
                                                        <div className={'col-lg-12 mb-2'}>
                                                            <label htmlFor={"equipment_group_id" + key}>Uskunalar
                                                                guruhi</label>
                                                            <Select className={"aria-required"}
                                                                    id={"equipment_group_id_" + key}
                                                                    onChange={this.onHandleChange.bind(this, 'select', 'plm_document_items', 'equipment_group_id', key, '', '')}
                                                                    placeholder={"Qurilmalar guruhi"}
                                                                    value={equipmentGroupList.filter(({value}) => +value === +item?.equipment_group_id)}
                                                                    options={equipmentGroupList}
                                                                    styles={customStyles}
                                                            />
                                                        </div>
                                                        <div className="col-lg-12">
                                                            <label htmlFor={"equipments_" + key}>Uskunalar</label>
                                                            <Select
                                                                styles={customStyles}
                                                                isMulti
                                                                id={"equipments_" + key}
                                                                className={"custom-padding"}
                                                                onChange={this.onHandleChange.bind(this, 'multi-select', 'plm_document_items', 'equipments', key, '', '')}
                                                                value={item.equipments}
                                                                placeholder={"Выбрать"}
                                                                isClearable={true}
                                                                options={item?.equipmentGroup?.equipments ?? []}
                                                            />
                                                        </div>
                                                        {/*<div className={"col-lg-2 mb-1 text-right"}>*/}
                                                        {/*    <button onClick={this.onPush.bind(this, 'equipment-group-plus', 'plm_document_items', key, '')}*/}
                                                        {/*            className={"btn btn-xs wh-28 btn-primary"}>*/}
                                                        {/*        <i className={"fa fa-plus"}/>*/}
                                                        {/*    </button>*/}
                                                        {/*</div>*/}
                                                        {/*{*/}
                                                        {/*    item?.equipmentGroup?.equipments?.length > 0 && item.equipmentGroup?.equipments.map((equipment, eqKey) => {*/}
                                                        {/*        return (*/}
                                                        {/*            <div className={'col-lg-12 mb-1'} key={eqKey}>*/}
                                                        {/*                <span className={'form-control'}>{equipment?.label}</span>*/}
                                                        {/*            </div>*/}
                                                        {/*        )*/}
                                                        {/*    })*/}
                                                        {/*}*/}
                                                    </div>
                                                </div>
                                            </div>

                                            <div className={'col-lg-1'}>
                                                <div className={"align-center"}>
                                                    <div className={'row'}>
                                                        <div className={'col-lg-12 text-center'}>
                                                            <label className={"control-label"}>Boshlanishi</label>
                                                            <DatePicker locale={ru}
                                                                        dateFormat="HH:mm"
                                                                        id={"start_work_" + key}
                                                                        onChange={(e) => {
                                                                            this.onHandleChange('date', 'plm_document_items', 'start_work', key, '', '', new Date(e))
                                                                        }}
                                                                        className={"form-control aria-required"}
                                                                        selected={item?.start_work ? new Date(item.start_work) : ""}
                                                                        autoComplete={'off'}
                                                                        showTimeSelect
                                                                        showTimeSelectOnly
                                                                        timeIntervals={5}
                                                                        timeCaption="Вақт"
                                                            />
                                                        </div>
                                                        <div className={"col-lg-12 text-center mt-2 mb-1"}>
                                                            <label>{this.onReturnMin(item?.end_work, item?.start_work)}
                                                                <small>min</small></label>
                                                        </div>
                                                        <div className={'col-lg-12 text-center'}>
                                                            <label className={"control-label"}>Tugashi</label>
                                                            <DatePicker locale={ru}
                                                                        dateFormat="HH:mm"
                                                                        id={"end_work_" + key}
                                                                        className={"form-control aria-required"}
                                                                        onChange={(e) => {
                                                                            this.onHandleChange('date', 'plm_document_items', 'end_work', key, '', '', new Date(e))
                                                                        }}
                                                                        selected={item?.end_work ? new Date(item.end_work) : ""}
                                                                        filterTime={(e) => {
                                                                            return new Date(item?.start_work).getTime() < new Date(e).getTime()
                                                                        }}
                                                                        autoComplete={'off'}
                                                                        showTimeSelect
                                                                        showTimeSelectOnly
                                                                        timeIntervals={5}
                                                                        timeCaption="Вақт"
                                                            />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div className="col-lg-1">
                                                <div className="row">
                                                    <div className={'col-lg-6 pb-1 text-center'}>
                                                        <div className={"align-center"}>
                                                            <div>
                                                                <label className={'control-label'}>Cycle time
                                                                    (s)</label>
                                                                <input value={item?.lifecycle ?? ""} readOnly={true}
                                                                       className={'form-control'}/>
                                                                <br/>
                                                                <label className={'control-label'}>Bypass (s)</label>
                                                                <input value={item?.bypass ?? ""} readOnly={true}
                                                                       className={'form-control'}/>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div className={'col-lg-6 pb-1 text-center'}>
                                                        <div className={"align-center"}>
                                                            <div>
                                                                <label className={'control-label'}>Rejada</label>
                                                                <input value={item?.target_qty ?? ""} readOnly={true}
                                                                       className={'form-control'}/>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div className={'col-lg-6'}>
                                                <div className={'row'}>
                                                    <div className={'col-lg-12'}>
                                                        <div className={'row'}>
                                                            <div className={'col-lg-3 pb-1'}>
                                                                <div className={'row'}>
                                                                    <div className={"col-lg-2 mb-1"}>
                                                                        <button
                                                                            onClick={this.onPush.bind(this, 'product-plus', 'plm_document_items', key, '')}
                                                                            className={"btn btn-xs btn-primary wh-28"}>
                                                                            <i className={"fa fa-plus"}/>
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div className={'col-lg-2 text-center'}>
                                                                <label className={"control-label"}>Ish/chiq</label>
                                                            </div>
                                                            <div className={'col-lg-2 text-center'}>
                                                                <label className={"control-label"}>Ish/chiq(Bs)</label>
                                                            </div>
                                                            <div className={'col-lg-2 text-center'}>
                                                                <label className={"control-label"}>Ta'mirlangan</label>
                                                            </div>
                                                            <div className={'col-lg-2 text-center'}>
                                                                <label className={"control-label"}>Yaroqsiz</label>
                                                            </div>
                                                            <div className={"col-lg-1 text-center"}></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                {
                                                    item?.products?.length > 0 && item.products.map((product, prKey) => {
                                                        return (
                                                            <div className={'row'} key={prKey}>
                                                                <div className={'col-lg-12'}>
                                                                    <div className={'row'}>
                                                                        <div className={'col-lg-3'}>
                                                                            <Select className={"aria-required"}
                                                                                    id={"product_id_" + key + "_" + prKey}
                                                                                    onChange={this.onHandleChange.bind(this, 'select', 'products', 'product_id', key, prKey, '')}
                                                                                    placeholder={"Tanlang ..."}
                                                                                    value={item?.equipmentGroup?.lifecycles?.productGroup?.products.filter(({value}) => +value === +product?.product_id)}
                                                                                    options={item?.equipmentGroup?.lifecycles?.productGroup?.products}
                                                                                    styles={customStyles}
                                                                            />
                                                                        </div>
                                                                        {/*<div className={'col-lg-2'}>*/}
                                                                        {/*    <input type={"text"} id={"product_lifecycle"} disabled={true} className={'form-control'} value={product?.lifecycle}/>*/}
                                                                        {/*</div>*/}
                                                                        {/*<div className={'col-lg-2'}>*/}
                                                                        {/*    <input type={"text"} id={"product_bypass"} disabled={true} className={'form-control'} value={product?.bypass}/>*/}
                                                                        {/*</div>*/}
                                                                        <div className={'col-lg-2'}>
                                                                            <input
                                                                                onChange={this.onHandleChange.bind(this, 'input', 'products', 'fact_qty', key, prKey, '')}
                                                                                type={'number'}
                                                                                className={'form-control aria-required'}
                                                                                id={"fact_qty_" + key + "_" + prKey}
                                                                                min={0}
                                                                                value={product?.fact_qty ?? ""}/>
                                                                        </div>
                                                                        <div className={'col-lg-2'}>
                                                                            <input
                                                                                onChange={this.onHandleChange.bind(this, 'input', 'products', 'qty', key, prKey, '')}
                                                                                type={'number'}
                                                                                className={'form-control aria-required'}
                                                                                id={"qty_" + prKey}
                                                                                min={0}
                                                                                value={product?.qty ?? ""}/>
                                                                        </div>
                                                                        <div className={'col-lg-2 text-center'}>
                                                                            <label
                                                                                className={'mr-2'}>{this.onSumma(product?.repaired)}</label>
                                                                            <button
                                                                                onClick={this.onOpenModal.bind(this, 'repaired', "Ta'mirlangan", key, prKey)}
                                                                                className={'btn btn-primary btn-xs wh-28'}>
                                                                                <i className={'fa fa-plus'}/>
                                                                            </button>
                                                                        </div>
                                                                        <div className={'col-lg-2 text-center'}>
                                                                            <label
                                                                                className={'mr-2'}>{this.onSumma(product?.scrapped)}</label>
                                                                            <button
                                                                                onClick={this.onOpenModal.bind(this, 'scrapped', "Yaroqsiz", key, prKey)}
                                                                                className={'btn btn-primary btn-xs wh-28'}>
                                                                                <i className={'fa fa-plus'}/>
                                                                            </button>
                                                                        </div>
                                                                        <div className={'col-lg-1 mb-1'}>
                                                                            <button
                                                                                onClick={this.onPush.bind(this, 'product-minus', 'products', key, prKey)}
                                                                                className={"btn btn-xs wh-28 btn-outline-danger"}>
                                                                                <i className={"fa fa-times"}/>
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        )
                                                    })
                                                }
                                            </div>

                                            <div className={'col-lg-1'}>
                                                <div className={"align-center"}>
                                                    <div className={'row'}>
                                                        <div className={'col-lg-12 text-center'}>
                                                            <label className={"control-label"}>Rejali
                                                                to'xtalishlar</label>
                                                        </div>
                                                        <div className={'col-lg-12 text-center'}>
                                                            <label
                                                                className={'mr-2'}>{this.onReturnMin(item?.planned_stopped?.end_time, item?.planned_stopped?.begin_date)}
                                                                <small>min</small></label>
                                                            <button
                                                                onClick={this.onOpenModal.bind(this, 'planned_stopped', "Rejali to'xtalishlar", key, '')}
                                                                className={"btn btn-xs btn-primary"}>
                                                                <i className={'fa fa-plus'}/>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div className={'col-lg-1'}>
                                                <div className={"align-center"}>
                                                    <div className={'row'}>
                                                        <div className={'col-lg-12 text-center'}>
                                                            <label className={"control-label"}>Rejasiz
                                                                to'xtalishlar</label>
                                                        </div>
                                                        <div className={'col-lg-12 text-center'}>
                                                            <label
                                                                className={'mr-2'}>{this.onReturnMin(item?.unplanned_stopped?.end_time, item?.unplanned_stopped?.begin_date)}
                                                                <small>min</small></label>
                                                            <button
                                                                onClick={this.onOpenModal.bind(this, 'unplanned_stopped', "Rejasiz to'xtalishlar", key, '')}
                                                                className={'btn btn-primary btn-xs'}>
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


                <div className="fade modal show" role="dialog" tabIndex="-1" style={{display: temporarily?.display}}
                     aria-modal="true">
                    <div className="modal-dialog modal-lg" role="document">
                        <div className="modal-content">
                            <div className="modal-header">
                                <h5>{temporarily?.title}</h5>
                                <button onClick={(e) => {
                                    this.setState({temporarily: {display: "none"}});
                                }} className="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
                            </div>
                            <div className="modal-body none-scroll">
                                <div className={'card-body'}>
                                    {
                                        temporarily?.type === "planned_stopped" || temporarily?.type === "unplanned_stopped" ?
                                            <div className={'row'}>
                                                <div className={'col-lg-12'}>
                                                    <div className={"form-group"}>
                                                        <label className={"control-label"}>Sabablar</label>
                                                        <Select
                                                            onChange={this.onHandleChange.bind(this, 'select', 'temporarily', 'reason_id', '', '', '')}
                                                            placeholder={"Tanlang ..."}
                                                            id={"reason_id"}
                                                            value={reasonList.filter(({value}) => +value === +temporarily?.store?.reason_id)}
                                                            options={reasonList}
                                                            styles={customStyles}
                                                        />
                                                    </div>
                                                </div>
                                                <div className={'col-lg-6'}>
                                                    <div className={"form-group"}>
                                                        <label className={"control-label"}>Boshlandi</label>
                                                        <DatePicker onChange={(e) => {
                                                            this.onHandleChange('date', 'temporarily', 'begin_date', '', '', '', new Date(e))
                                                        }}
                                                                    locale={ru}
                                                                    id={"begin_date"}
                                                                    className={"form-control"}
                                                                    selected={temporarily?.store?.begin_date ? new Date(temporarily.store.begin_date) : ""}
                                                                    autoComplete={'off'}
                                                            // filterTime={(e) => {
                                                            //     return new Date(temporarily.item?.start_work).getTime() <= new Date(e).getTime() &&
                                                            //         new Date(e).getTime() <= new Date(temporarily.item?.end_work).getTime()
                                                            // }}
                                                                    peekNextMonth
                                                                    showMonthDropdown
                                                                    showYearDropdown
                                                                    showTimeSelect
                                                                    dateFormat="dd/MM/yyyy HH:mm"
                                                                    timeIntervals={5}
                                                                    timeCaption="Вақт"
                                                        />
                                                    </div>
                                                </div>
                                                <div className={'col-lg-6'}>
                                                    <div className={"form-group"}>
                                                        <label className={"control-label"}>Tugadi</label>
                                                        <DatePicker onChange={(e) => {
                                                            this.onHandleChange('date', 'temporarily', 'end_time', '', '', '', new Date(e))
                                                        }}
                                                                    locale={ru}
                                                                    id={"end_time"}
                                                                    className={"form-control"}
                                                                    selected={temporarily?.store?.end_time ? new Date(temporarily.store.end_time) : ""}
                                                                    autoComplete={'off'}
                                                                    minDate={temporarily?.store?.begin_date ? new Date(temporarily.store.begin_date) : ""}
                                                                    peekNextMonth
                                                                    showMonthDropdown
                                                            // filterTime={(e) => {
                                                            //     return new Date(temporarily.store?.begin_date).getTime() < new Date(e).getTime() &&
                                                            //         new Date(e).getTime() <= new Date(temporarily.item?.end_work).getTime()
                                                            // }}
                                                                    showYearDropdown
                                                                    showTimeSelect
                                                                    dateFormat="dd/MM/yyyy HH:mm"
                                                                    timeIntervals={5}
                                                                    timeCaption="Вақт"
                                                        />
                                                    </div>
                                                </div>
                                                {
                                                    temporarily?.type === 'unplanned_stopped' ?
                                                        <div className={'col-lg-12'}>
                                                            <div className={"form-group"}>
                                                                <label className={"control-label"}>Bypass</label>
                                                                <input
                                                                    onChange={this.onHandleChange.bind(this, 'input', 'temporarily', 'bypass', '', '', '')}
                                                                    className={"form-control"}
                                                                    value={temporarily?.store?.bypass} id={"bypass"}/>
                                                            </div>
                                                        </div> : ""
                                                }
                                                <div className={'col-lg-12'}>
                                                    <div className={"form-group"}>
                                                        <label className={"control-label"}>Izoh</label>
                                                        <textarea
                                                            onChange={this.onHandleChange.bind(this, 'input', 'temporarily', 'add_info', '', '', '')}
                                                            className={"form-control"} rows={8}
                                                            value={temporarily?.store?.add_info} id={"add_info"}/>
                                                    </div>
                                                </div>
                                            </div>
                                            : temporarily?.type === "repaired" || temporarily?.type === "scrapped" ?
                                            <div className={'row'}>
                                                {
                                                    temporarily?.store?.length > 0 && temporarily.store.map((item, itemKey) => {
                                                        return (
                                                            <div className={"col-lg-6"} key={itemKey}>
                                                                <div className={"form-group"}>
                                                                    <label>{item?.label}</label>
                                                                    <input
                                                                        onChange={this.onHandleChange.bind(this, 'input', temporarily?.type, 'count', temporarily?.key, itemKey, '')}
                                                                        type={"number"} className={"form-control"}
                                                                        value={item?.count ?? 0}/>
                                                                </div>
                                                            </div>
                                                        )
                                                    })
                                                }
                                            </div> : ""
                                    }
                                </div>
                                <div className={'card-footer mt-1'}>
                                    <div className={'row'}>
                                        <div className={'col-lg-12'}>
                                            <div className={'pull-left'}>
                                                <button onClick={this.onHandleSave.bind(this)}
                                                        className={"btn btn-sm btn-success mr-3"}>Saqlash
                                                </button>
                                                <button onClick={this.onHandleCancel.bind(this)}
                                                        className={"btn btn-sm btn-danger"}>Bekor qilish
                                                </button>
                                            </div>
                                            <div className={'pull-right'}>
                                                <b>{temporarily?.type === "repaired" || temporarily?.type === "scrapped" ? "Jami: " + this.onSumma(temporarily?.store) : ""}</b>
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
    };
}

export default Form;
