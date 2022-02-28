import React, {Component} from 'react';
import {render} from "react-dom";
import  style from "../../../style/style";
import Select from "react-select";
import axios from "axios";
import {toast, Flip, ToastContainer} from "react-toastify";

const API_EQUIPMENT_GROUP_URL = window.location.protocol + "//" + window.location.host + "/api/v1/api-equipment-groups/";

const initialItem = {
    lifecycle_id : '',
    bypass : '',
    lifecycle : '',
    products: [],
};

const  initialLifeCycle = {
    name : '',
    equipments : '',
    equipments_group_type_id : 1,
    cycles: [JSON.parse(JSON.stringify(initialItem))]
};

class EquipmentGroup extends Component {

    constructor(props) {
        super(props);
        this.state = {
            messages: {},
            equipments_list: [],
            status_list: [],
            products_list: [],
            equipments_group_type_list: [],
            forms: {
                modal: {
                    id: null,
                    name: '',
                    part_number: '',
                    status_id: 1,
                },
                groups: [JSON.parse(JSON.stringify(initialLifeCycle))],
            },
            display: false,
        }
    }

    async componentDidMount() {

        let params = new URLSearchParams(window.location.search);
        let id = params.get('id') ?? '';

        let response = await axios.get(API_EQUIPMENT_GROUP_URL + 'index?type=EQUIPMENT_GROUP_DATA&id=' + id);
        if (response.data.status){
            if (id){
                let forms = response.data.forms;
                if (response.data.forms.groups.length <= 0)
                    forms['groups'] = [JSON.parse(JSON.stringify(initialLifeCycle))];

                forms["modal"] = {
                    id: null,
                    name: '',
                    part_number: '',
                    status_id: '',
                };
                this.setState({ forms });
            }
            this.setState({
                equipments_list: response.data.equipments_list,
                status_list: response.data.status_list,
                products_list: response.data.products_list,
                equipments_group_type_list: response.data.equipments_group_type_list,
                messages: response.data.messages,
            });
        }
    }

    onHandleChange = (type, name, model, index, subIndex,  e) => {
        let { forms } = this.state;
        let value = "";
        switch (type) {
            case "select":
                value = e?.value ?? "";
                break;
            case "multi-select":
                value = e;
                break;
            case "date":
                value = e;
                break;
            case "input":
                value = e?.target?.value ?? "";
                break;
        }

        switch (model) {
            case "cycles":
                forms["groups"][index][model][subIndex][name] = value;
                break;
            case "groups":
                if (name == "equipments_group_type_id") {
                    forms[model][index]["cycles"] = [JSON.parse(JSON.stringify(initialItem))];
                }
                forms[model][index][name] = value;
                break;
            case "modal":
                forms[model][name] = value;
                break;
            default:
                forms[name] = value;
                break
        }
        this.setState({forms});
    };

    async removeData(model, index, subIndex, e){
        let { forms } = this.state;
        switch (model) {
            case "groups":
                if (forms[model][index]['equipment_group_id']){
                    if (confirm("Rostdan ham o'chirmoqchimisiz?")){
                        let { forms } = this.state;
                        let response = await axios.post(API_EQUIPMENT_GROUP_URL + 'index?type=EQUIPMENT_GROUP_DELETE', forms["groups"][index]);
                        if(response.data.status)
                            this.remove(index, '', model);
                        else
                            toast.error(response.data.message);
                    }
                }else{
                    this.remove(index, '',  model);
                }
                break;
            case "cycles":
                if (forms["groups"][index][model][subIndex]["lifecycle_id"]){
                    if (confirm("Rostdan ham o'chirmoqchimisiz?")){
                        let { forms } = this.state;
                        let response = await axios.post(API_EQUIPMENT_GROUP_URL + 'index?type=PRODUCT_DELETE', forms["groups"][index][model][subIndex]);
                        if(response.data.status)
                            this.remove(index, '', model);
                        else
                            toast.error(response.data.message);
                    }
                }else{
                    this.remove(index, subIndex, model);
                }
                break;
        }
    };

    remove(remove_index,subIndex, model) {
        let { forms } = this.state;
        switch (model) {
            case "groups":
                let productLifeCycle;
                if (forms.groups.length > 0){
                    productLifeCycle =  forms.groups.filter((item, index) => index != remove_index);
                    forms[model] = productLifeCycle;
                }
                break;
            case "cycles":
                let cycles;
                if (forms["groups"][remove_index][model].length > 0){
                    cycles =  forms["groups"][remove_index][model].filter((item, index) => index != subIndex);
                    forms['groups'][remove_index][model] = cycles;
                    this.setState({ forms });
                }
                break;
        }

        this.setState({ forms });
    };

    add = (model, index) => {
        let { forms } = this.state;
        switch (model) {
            case "groups":
                forms[model].push(JSON.parse(JSON.stringify(initialLifeCycle)));
                break;
            case "cycles":
                forms['groups'][index][model].push(JSON.parse(JSON.stringify(initialItem)));
                break;
        }
        this.setState({forms});
    };

    async onSave(type, index, e){
        let { forms, products_list } = this.state;
        let data = {};
        let validate = true;
        switch (type) {
            case "PRODUCT_SAVE":
                data = forms.modal;
                if (!forms.modal.name || !forms.modal.part_number || !forms.modal.status_id)
                    validate = false;
                break;
            case "EQUIPMENT_GROUP_SAVE":
                data = [forms["groups"][index]];
                break;
        }

        if (validate){
            let response = await axios.post(API_EQUIPMENT_GROUP_URL + 'index?type=' + type, data);

            if (response.data.status) {
                switch (type) {
                    case "PRODUCT_SAVE":
                        forms['modal'] =  {
                            id: null,
                            name: '',
                            part_number: '',
                            status_id: 1,
                        };
                        products_list.push(response.data.item);
                        this.display();
                        break;
                    case "EQUIPMENT_GROUP_SAVE":
                        if (forms["groups"][index]["cycles"].length > 0){
                            forms["groups"][index]["cycles"].forEach(function (item, cycleIndex) {
                                forms["groups"][index]["cycles"][cycleIndex]["lifecycle_id"] = response.data.lifecycle_ids[cycleIndex];
                            });
                        }
                        forms["groups"][index]["equipment_group_id"] = response.data.equipment_group_id;
                        this.setState({forms});
                        break;
                }
                toast.success(response.data.message);
            } else {
                toast.error(response.data.message);
            }
        }else{
            toast.error('Kerakli ma\'lumotlarni to\'ldiring');
        }
        this.setState({forms, products_list});
    };

    display = () => {
        let { display } = this.state;
        this.setState({display: !display});
    };

    render() {
        let {
            forms,
            equipments_list,
            equipments_group_type_list,
            status_list,
            display,
            messages,
            products_list,
        } = this.state;
        let productLifecycleBody = "";

        if(forms.groups.length > 0){
            productLifecycleBody = forms.groups.map((item, index) => {
                return (
                    <div className={"driver col-lg-12"} key={index}>
                        {index != 0 ? (
                            <div className={"remove_driver remove"}>
                                <button  className={"btn btn-xs btn-danger"}  onClick={this.removeData.bind(this, "groups", index,'')}><i className={"fa fa-times"}></i></button>
                            </div>
                        ) :
                            <div className={"add_driver"}>
                                <button className={"btn btn-xs btn-primary "} onClick={this.add.bind(this, "groups")}><i className={"fa fa-plus"}></i></button>
                            </div>}

                       <div className={"row"}>
                           <div className="col-lg-2">
                               <label htmlFor={"name_"+index}><span className={"required"}>*</span> {messages.group_name}</label>
                               <input
                                   className={"form-control"}
                                   id={"name_"+index}
                                   name="name"
                                   autoComplete={'off'}
                                   value={item.name}
                                   onChange={this.onHandleChange.bind(this, 'input', 'name', 'groups', index, '')}
                               />
                           </div>
                           <div className={"col-lg-3"}>
                               <label htmlFor={"equipments_"+index}><span className={"required"}>*</span> {messages.equipments}</label>
                               <Select
                                   styles={style}
                                   isMulti
                                   id={"equipments_"+index}
                                   className={"custom-padding"}
                                   onChange={this.onHandleChange.bind(this, 'multi-select', 'equipments','groups',index, '')}
                                   value={item.equipments}
                                   placeholder={"Выбрать"}
                                   isClearable={true}
                                   options={equipments_list}
                               />
                           </div>
                           <div className={"col-lg-2"}>
                               <label htmlFor={"equipments_group_type_id_"+index}><span className={"required"}>*</span> {messages.group_type}</label>
                               <Select
                                   styles={style}
                                   id={"equipments_group_type_id_"+index}
                                   onChange={this.onHandleChange.bind(this, 'select', 'equipments_group_type_id','groups',index, '')}
                                   value={equipments_group_type_list.filter(({value}) => +value === +item.equipments_group_type_id)}
                                   placeholder={"Выбрать"}
                                   isClearable={true}
                                   options={equipments_group_type_list}
                               />
                           </div>
                           <div className="col-lg-5">
                               <div className={"flex"}>
                                    <div className={"cycles"}>
                                        <div className={"row"}>
                                            <div className="col-lg-5">
                                                <span className={"required"}>*</span> {messages.products}
                                            </div>
                                            <div className="col-lg-3">
                                                <span className={"required"}>*</span> {messages.lifecycle}
                                            </div>
                                            <div className="col-lg-3">
                                                <span className={"required"}>*</span> {messages.bypass}
                                            </div>
                                            <div className="col-lg-1">
                                            </div>
                                        </div>
                                        {
                                            item?.cycles?.length > 0 && item.cycles.map((subItem, subIndex) => {
                                                return (
                                                    <div className="row" key={index + "_" + subIndex}>
                                                        <div className="col-lg-5">
                                                            <Select
                                                                styles={style}
                                                                isMulti
                                                                id={"products_" + index + "_" + subIndex}
                                                                onChange={this.onHandleChange.bind(this, 'multi-select', 'products', 'cycles', index, subIndex)}
                                                                value={subItem.products}
                                                                placeholder={"Выбрать"}
                                                                isClearable={true}
                                                                className={"custom-padding"}
                                                                options={products_list}
                                                            />
                                                        </div>
                                                        <div className="col-lg-3">
                                                            <input
                                                                className={"form-control"}
                                                                id={"lifecycle_"+index + "_" + subIndex}
                                                                name="lifecycle"
                                                                autoComplete={'off'}
                                                                type={"number"}
                                                                min={0}
                                                                value={subItem.lifecycle}
                                                                onChange={this.onHandleChange.bind(this, 'input', 'lifecycle', 'cycles', index, subIndex)}
                                                            />
                                                        </div>
                                                        <div className="col-lg-3">
                                                            <input
                                                                className={"form-control"}
                                                                id={"bypass" + index + "_" + subIndex}
                                                                name="bypass"
                                                                type={"number"}
                                                                autoComplete={'off'}
                                                                value={subItem.bypass}
                                                                min={0}
                                                                onChange={this.onHandleChange.bind(this, 'input', 'bypass', 'cycles', index, subIndex)}
                                                            />
                                                        </div>
                                                        <div className={"col-lg-1"}>
                                                            {
                                                                +item.equipments_group_type_id === 1 ? (<div>
                                                                    {subIndex != 0 ?
                                                                        <button  className={"btn btn-xs btn-outline-danger"}  onClick={this.removeData.bind(this, "cycles", index, subIndex)}><i className={"fa fa-times"}></i></button>
                                                                        :
                                                                        <button className={"btn btn-xs btn-outline-primary "} onClick={this.add.bind(this, "cycles", index)}><i className={"fa fa-plus"}></i></button>
                                                                    }
                                                                </div>) : (<div></div>)
                                                            }
                                                        </div>
                                                    </div>
                                                )
                                            })
                                        }
                                    </div>
                                   <button className="btn btn-success btn-xs" onClick={this.onSave.bind(this, "EQUIPMENT_GROUP_SAVE", index)}>
                                       <i className={"fa fa-check"}></i>
                                   </button>
                               </div>

                           </div>
                       </div>

                    </div>
                );
            });
        }

        return (
            <div className="card">
                <div className="card-body">
                    <div className="no-print">
                        <ToastContainer autoClose={3000} position={'top-right'} transition={Flip} draggablePercent={60} closeOnClick={true} pauseOnHover closeButton={true}/>
                    </div>
                    <div className={"row"}>
                        <div className="col-lg-12">
                            <div className="flex">
                                <button className={"btn btn-xs btn-primary"} onClick={this.display.bind(this)} ><i className={"fa fa-plus"}></i> Mahsulot qo'shish</button>
                            </div>
                        </div>
                    </div>
                    <br/>

                    <div className={"card"}>
                        <div className="card-body">
                            <div className={"add_driver_block"}>
                                <h4 style={{"paddingLeft": "5px"}}>
                                    {messages.header}
                                </h4>
                            </div>
                            <div className={"row"}>
                                {productLifecycleBody}
                            </div>
                            <div className="row">
                                <div className="col-lg-12">
                                    <a href="/references/equipment-group/index" className={"btn btn-info"}>{messages.back ?? ""}</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div className="modal" id="settingModalBox1" style={{display: display ? "block" : "none"}}>
                        <div className="modal-dialog modal-md custom-modal-xl" role="document">
                            <div className="modal-content">
                                <div className="modal-header">
                                    <span></span>
                                    <span onClick={this.display.bind(this)} className={"my_times"}>&times;</span>
                                </div>
                                <div className="modal-body">
                                   <div style={{minHeight: "50vh"}}>
                                       <div className={"row"}>
                                           <div className="col-lg-12">
                                               <label className={"control-label"}><span className={"required"}>*</span>Kodi</label>
                                               <input onChange={this.onHandleChange.bind(this, 'input', 'part_number', 'modal', '', '')}
                                                      name={'part_number'} value={forms?.modal?.part_number} className={'form-control'}/>
                                           </div>
                                           <div className="col-lg-12">
                                               <label className={"control-label"}><span className={"required"}>*</span>Nomi</label>
                                               <input onChange={this.onHandleChange.bind(this, 'input', 'name', 'modal', '', '')}
                                                      name={'name'} value={forms?.modal?.name} className={'form-control'}/>
                                           </div>
                                           <div className="col-lg-12">
                                               <label className={"control-label"}><span className={"required"}>*</span>Holati</label>
                                               <Select
                                                   styles={style}
                                                   id={"status_id"}
                                                   onChange={this.onHandleChange.bind(this, 'select', 'status_id','modal','', '')}
                                                   value={status_list.filter(({value}) => +value === +forms?.modal?.status_id)}
                                                   placeholder={"Выбрать"}
                                                   isClearable={true}
                                                   options={status_list}
                                               />
                                           </div>
                                           <div className="col-lg-12">
                                               <br/>
                                               <button className="btn btn-primary" onClick={this.onSave.bind(this, "PRODUCT_SAVE", '')}>Saqlash</button>
                                           </div>
                                       </div>
                                   </div>
                                </div>
                                <div className="modal-footer">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        );
    }
}

render((<EquipmentGroup/>), window.document.getElementById('root'));