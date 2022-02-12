import React, {useEffect, useState} from "react";
import Select from "react-select";
import customStyles from "../../../../actions/style/customStyle";
import {removeElement} from "../../../../actions/functions";

function EquipmentGroup(props){
    const [variables, setVariables] = useState(props.appearance.variables);
    const [variableItems, setVarItems] = useState(props.appearance.variableItems);

    const [onOpen, setOnOpen] = useState(false);
    
    useEffect(() => {
        setVariables(props.appearance.variables);
        setVarItems(props.appearance.variableItems);
        setOnOpen(props.appearance.display === "block");
    })

    const onChange = (type, name, key, value) => {
        switch (type) {
            case "variables":
                variables[name] = value;
                props.onChangeProps('variables', variables);
                break;
            case "variableItems":
                variableItems[key][name] = value;
                props.onChangeProps('variableItems', variableItems);
                break;
            case "variableItems-plus":
                let item = {
                    equipment_id: ""
                };
                variableItems.push(item);
                props.onChangeProps('variableItems', variableItems);
                break;
            case "variableItems-minus":
                if (+key > 0) {
                    props.onChangeProps('variableItems', removeElement(variableItems, key));
                }
                break;
        }
    }

    const onSave = (event) => {
        switch (props.appearance.type) {
            case "equipment-group":
                let equipment_group = {
                    equipment_group: variables,
                    relation: variableItems
                };
                props.onSaveProps('equipmentGroup', equipment_group);
                break;
            case "product-lifecycle":
                props.onSaveProps('productLifecycle', {lifecycle: variables});
                break;
        }
    }
    let HTML = "";
    switch (props.appearance.type) {
        case "equipment-group":
            HTML = <div className={'row'}>
                <div className={"col-sm-12"}>
                    <div className={'form-group'}>
                        <label>Nomi</label>
                        <input onChange={(e) => {onChange('variables', 'name', '', e?.target?.value)}}
                               type={"text"} value={variables?.name} className={'form-control'}/>
                    </div>
                </div>
                <div className={"col-sm-12"}>
                    <table className={'table table-bordered'}>
                        <thead>
                        <tr>
                            <th width={"50px"}>#</th>
                            <th>Qurilmalar</th>
                            <th width={"50px"} className={'text-center'}>
                                <button onClick={() => {onChange('variableItems-plus', '', '', '')}}
                                        className={'btn btn-xs btn-primary'}>
                                    <i className={'fa fa-plus'}/>
                                </button>
                            </th>
                        </tr>
                        {
                            variableItems?.length > 0 && variableItems.map((item, key) => {
                                return (
                                    <tr key={key}>
                                        <td>{(key + 1)}</td>
                                        <td>
                                            <Select className={"aria-required"}
                                                    onChange={(e) => {onChange('variableItems', 'equipment_id', key, e?.value)}}
                                                    placeholder={"Tanlang ..."}
                                                    value={props?.appearance?.equipmentList.filter(({value}) => +value === +item?.equipment_id)}
                                                    options={props?.appearance?.equipmentList}
                                                    styles={customStyles}
                                            />
                                        </td>
                                        <td className={'text-center'}>
                                            <button onClick={() => {onChange('variableItems-minus', '', key, '')}}
                                                    className={'btn btn-xs btn-danger'}>
                                                <i className={'fa fa-times'}/>
                                            </button>
                                        </td>
                                    </tr>
                                )
                            })
                        }
                        </thead>
                    </table>
                </div>
                <div className={"col-sm-12 displayFlex"}>
                    <div className={'btn btn-sm btn-success'} onClick={onSave}>Saqlash</div>
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <div className={'btn btn-sm btn-danger'} onClick={props.onCancel}>Bekor qilish</div>
                </div>
            </div>;
            break;
        case "product-lifecycle":
            HTML = <div className={'row'}>
                <div className={'col-sm-12'}>
                    <div className={'form-group'}>
                        <label>Qurilmalar guruhi</label>
                        <Select className={"aria-required"}
                                onChange={(e) => {
                                    onChange('variables', 'equipment_group_id', '', e?.value)
                                }}
                                isDisabled={true}
                                placeholder={"Tanlang ..."}
                                value={props.appearance?.equipmentGroupList.filter(({value}) => +value === +variables?.equipment_group_id)}
                                options={props.appearance?.equipmentGroupList}
                                styles={customStyles}
                        />
                    </div>
                </div>
                <div className={"col-sm-12"}>
                    <div className={'form-group'}>
                        <label>Maxsulot</label>
                        <Select className={"aria-required"}
                                onChange={(e) => {
                                    onChange('variables', 'product_id', '', e?.value)
                                }}
                                placeholder={"Tanlang ..."}
                                value={props.appearance?.productList.filter(({value}) => +value === +variables?.product_id)}
                                options={props.appearance?.productList}
                                styles={customStyles}
                        />
                    </div>
                </div>
                <div className={'col-sm-12'}>
                    <div className={'form-group'}>
                        <label>Lifecycle <small>(sekund)</small></label>
                        <input onChange={(e) => {onChange('variables', 'lifecycle', '', e?.target?.value)}}
                               type={"number"} className={'form-control'} value={variables?.lifecycle}/>
                    </div>
                </div>
                <div className={'col-sm-12'}>
                    <div className={'form-group'}>
                        <label>Bypass Lifecycle <small>(sekund)</small></label>
                        <input onChange={(e) => {onChange('variables', 'bypass', '', e?.target?.value)}}
                               type={"number"} className={'form-control'} value={variables?.bypass}/>
                    </div>
                </div>
                <div className={"col-sm-12 displayFlex"}>
                    <div className={'btn btn-sm btn-success'} onClick={onSave}>Saqlash</div>
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <div className={'btn btn-sm btn-danger'} onClick={props.onCancel}>Bekor qilish</div>
                </div>
            </div>;
            break;
    }


    return <React.Fragment>
        <div className="fade modal show" role="dialog" tabIndex="-1" style={{display: onOpen ? "block" : "none"}} aria-modal="true">
            <div className="modal-dialog modal-lg" role="document">
                <div className="modal-content">
                    <div className="modal-header">
                        <h5>{props.appearance.title}</h5>
                        <button onClick={props.onCancel} className="close" data-dismiss="modal">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div className="modal-body" style={{minHeight: "420px"}}>
                        <div className={'card-body'}>
                            {HTML}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </React.Fragment>
}

export default EquipmentGroup;