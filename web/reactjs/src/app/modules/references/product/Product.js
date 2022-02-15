import React, {Component} from 'react';
import {render} from "react-dom";
import  style from "../../../style/style";
import Select from "react-select";
import axios from "axios";
import {Flip, toast, ToastContainer} from "react-toastify";

const API_URL = window.location.protocol + "//" + window.location.host + "/api/v1/api-products/";

const  initialLifeCycle = {
    equipments : '',
    lifecycle : '',
    bypass : '',
    equipment_group_id : '',
    equipments_group_type_id : '',
    product_lifecycle_id : '',
};

class Product extends Component {

    constructor(props) {
        super(props);
        this.state = {
            isSubmitted: true,
            equipments_list: [],
            status_list: [],
            products_list: [],
            equipments_group_type_list: [],
            forms: {
                modal: {
                    id: null,
                    name: '',
                    part_number: '',
                    status_id: '',
                },
                products: [],
                product_group_id: '',
                product_lifecycle: [JSON.parse(JSON.stringify(initialLifeCycle))],
            },
            display: false,
        }
    }

    async componentDidMount() {

        let params = new URLSearchParams(window.location.search);
        let id = params.get('id') ?? '';

        let response = await axios.get(API_URL + 'index?type=PRODUCT_DATA&id=' + id);
        if (response.data.status){
            if (id){
                let forms = response.data.forms;
                if (response.data.forms.product_lifecycle.length <= 0)
                    forms['product_lifecycle'] = [JSON.parse(JSON.stringify(initialLifeCycle))];

                this.setState({ forms });
            }
            this.setState({
                equipments_list: response.data.equipments_list,
                status_list: response.data.status_list,
                products_list: response.data.products_list,
                equipments_group_type_list: response.data.equipments_group_type_list,
            });
        }

    }

    onHandleChange = (type, name, model, index, e) => {
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
            case "product_lifecycle":
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

    async removeLifeCycle(remove_index){
        let { forms } = this.state;
        if (forms["product_lifecycle"][remove_index]['product_lifecycle_id']){
            if (confirm("Rostdan ham o'chirmoqchimisiz?")){
                let { forms } = this.state;
                let response = await axios.post(API_URL + 'index?type=PRODUCT_EQUIPMENT_DELETE', forms["product_lifecycle"][remove_index]);
                if(response.data.status){
                    this.remove(remove_index);
                }else{
                    toast.error(response.data.message);
                }
            }
        }else{
            this.remove(remove_index);
        }
    };

    remove(remove_index)
    {
        let { forms } = this.state;
        let productLifeCycle;
        if (forms.product_lifecycle.length > 0){
            productLifeCycle =  forms.product_lifecycle.filter((item, index) => index != remove_index);
            forms['product_lifecycle'] = productLifeCycle;
        }
        toast.success("O'chirildi");
        this.setState({ forms });
    };

    addManualDriverInfo = () => {
        let { forms } = this.state;
        forms['product_lifecycle'].push(JSON.parse(JSON.stringify(initialLifeCycle)));
        this.setState({forms});
    };

    async onSave(type, index, e){
        let { forms, products_list } = this.state;
        let data = {};
        switch (type) {
            case "PRODUCT_SAVE":
                data = forms.modal;
                break;
            case "PRODUCT_EQUIPMENT_SAVE":
                data = {
                    products: forms.products,
                    product_group_id: forms.product_group_id,
                    item: forms["product_lifecycle"][index]
                };
                break;
        }

        let response = await axios.post(API_URL + 'index?type=' + type, data);

        if (response.data.status) {
            switch (type) {
                case "PRODUCT_SAVE":
                    forms['modal'] =  {
                        id: null,
                        name: '',
                        part_number: '',
                        status_id: '',
                    };
                    products_list.push(response.data.item);
                    this.display();
                    break;
                case "PRODUCT_EQUIPMENT_SAVE":
                    forms["product_lifecycle"][index]['equipment_group_id'] = response.data.equipment_group_id;
                    forms["product_lifecycle"][index]['product_lifecycle_id'] = response.data.product_lifecycle_id;
                    forms['product_group_id'] = response.data.product_group_id;
                    break;
            }
            toast.success(response.data.message);
        } else {
            toast.error(response.data.message);
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
            products_list,
            isSubmitted
        } = this.state;
        let productLifecycleBody = "";

        if(forms.product_lifecycle.length > 0){
            productLifecycleBody = forms.product_lifecycle.map((item, index) => {
                return (
                    <div className={"driver col-lg-12"} key={index}>
                        {index != 0 ? (
                            <div className={"remove_driver remove"}>
                                <button  className={"btn btn-xs btn-danger"}  onClick={this.removeLifeCycle.bind(this,index)}><i className={"fa fa-times"}></i></button>
                            </div>
                        ) :
                            <div className={"add_driver"}>
                                <button className={"btn btn-xs btn-primary "} onClick={this.addManualDriverInfo.bind(this)}><i className={"fa fa-plus"}></i></button>
                            </div>}

                       <div className={"row"}>
                           <div className={"col-lg-6"}>
                               <label htmlFor={"equipments_"+index}>Equipments</label>
                               <Select
                                   styles={style}
                                   isMulti
                                   id={"equipments_"+index}
                                   onChange={this.onHandleChange.bind(this, 'multi-select', 'equipments','product_lifecycle',index)}
                                   value={item.equipments}
                                   placeholder={"Выбрать"}
                                   isClearable={true}
                                   options={equipments_list}
                               />
                           </div>
                           <div className={"col-lg-2"}>
                               <label htmlFor={"equipments_group_type_id_"+index}>Group type</label>
                               <Select
                                   styles={style}
                                   id={"equipments_group_type_id_"+index}
                                   onChange={this.onHandleChange.bind(this, 'select', 'equipments_group_type_id','product_lifecycle',index)}
                                   value={equipments_group_type_list.filter(({value}) => +value === +item.equipments_group_type_id)}
                                   placeholder={"Выбрать"}
                                   isClearable={true}
                                   options={equipments_group_type_list}
                               />
                           </div>
                           <div className={"col-lg-2"}>
                               <label htmlFor={"lifecycle"+index}>Lifecycle</label>
                               <input
                                   style={{borderColor: isSubmitted || item.lifecycle ? "#CCCCCC" : "#C82333"}}
                                   className={"form-control"}
                                   id={"lifecycle"+index}
                                   name="lifecycle"
                                   autoComplete={'off'}
                                   value={item.lifecycle}
                                   onChange={this.onHandleChange.bind(this, 'input', 'lifecycle', 'product_lifecycle', index)}
                               />
                           </div>
                           <div className={"col-lg-2"}>
                               <label htmlFor={"bypass"+index}>Bypass</label>
                               <div className={"flex"}>
                                   <input
                                       style={{borderColor: isSubmitted || item.bypass ? "#CCCCCC" : "#C82333"}}
                                       className={"form-control"}
                                       id={"bypass"+index}
                                       name="bypass"
                                       autoComplete={'off'}
                                       value={item.bypass}
                                       onChange={this.onHandleChange.bind(this, 'input', 'bypass', 'product_lifecycle', index)}
                                   />
                                   <button className="btn btn-success btn-xs" disabled={forms.products.length > 0 ? false : true} onClick={this.onSave.bind(this, "PRODUCT_EQUIPMENT_SAVE", index)}>
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
                            <label htmlFor={"products"}>Mahsulotlar</label>
                            <div className="flex">
                                <Select
                                    styles={style}
                                    isMulti
                                    id={"products"}
                                    onChange={this.onHandleChange.bind(this, 'multi-select', 'products','','')}
                                    value={forms.products}
                                    placeholder={"Выбрать"}
                                    isClearable={true}
                                    options={products_list}
                                />
                                <button className={"btn btn-xs btn-primary"} onClick={this.display.bind(this)} ><i className={"fa fa-plus"}></i></button>
                            </div>
                        </div>
                    </div>
                    <br/>

                    <div className={"card"}>
                        <div className="card-body">
                            <div className={"add_driver_block"}>
                                <h4 style={{"paddingLeft": "5px"}}>
                                    Lifecycle
                                </h4>
                            </div>
                            <div className={"row"}>
                                {productLifecycleBody}
                            </div>
                            <div className="row">
                                <div className="col-lg-12">
                                    <a href="" className={"btn btn-info"}>Orqaga</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div className="modal" id="settingModalBox1" style={{display: display ? "block" : "none"}}>
                        <div className="modal-dialog modal-xl custom-modal-xl" role="document">
                            <div className="modal-content">
                                <div className="modal-header">
                                    <span onClick={this.display.bind(this)} className={"my_times"}>&times;</span>
                                </div>
                                <div className="modal-body">
                                   <div style={{minHeight: "30vh"}}>
                                       <div className={"row"}>
                                           <div className="col-lg-12">
                                               <label className={"control-label"}><span className={"required"}>*</span>Nomi</label>
                                               <input onChange={this.onHandleChange.bind(this, 'input', 'name', 'modal', '')}
                                                      name={'name'} value={forms?.modal?.name} className={'form-control'}/>
                                           </div>
                                           <div className="col-lg-12">
                                               <label className={"control-label"}><span className={"required"}>*</span>Kodi</label>
                                               <input onChange={this.onHandleChange.bind(this, 'input', 'part_number', 'modal', '')}
                                                      name={'part_number'} value={forms?.modal?.part_number} className={'form-control'}/>
                                           </div>
                                           <div className="col-lg-12">
                                               <label className={"control-label"}><span className={"required"}>*</span>Holati</label>
                                               <Select
                                                   styles={style}
                                                   id={"status_id"}
                                                   onChange={this.onHandleChange.bind(this, 'select', 'status_id','modal','')}
                                                   value={status_list.filter(({value}) => +value === +forms?.modal?.status_id)}
                                                   placeholder={"Выбрать"}
                                                   isClearable={true}
                                                   options={status_list}
                                               />
                                           </div>
                                           <div className="col-lg-12">
                                               <br/>
                                               <button className="btn btn-xs btn-primary" onClick={this.onSave.bind(this, "PRODUCT_SAVE", '')}>Saqlash</button>
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

render((<Product/>), window.document.getElementById('root'));


