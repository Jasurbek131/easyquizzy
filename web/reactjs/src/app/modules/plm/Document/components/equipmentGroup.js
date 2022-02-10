import React, {useEffect, useState} from "react";
import Select from "react-select";
import DatePicker from "react-datepicker";
import customStyles from "../../../../actions/style/customStyle";

function EquipmentGroup(props){
    const [val, setVal] = useState(props.appearance);
    const [onOpen, setOnOpen] = useState(false);
    
    useEffect(() => {
        console.log(val)
        setOnOpen(val.display === "block")
    })
    
    const clickHandle = () => {
        setOnOpen(!onOpen)
    }
    const changeHandle = (event) => {

    }

    const onSave = (event) => {
        props.onCancel();
        props.onSave(result, event)
    }


    const html = (onOpen ? (
        <div className={'row'}>
            <div className={"col-sm-12"}>
                <div className={'form-group'}>
                    <label>Nomi</label>
                    <input type={"text"} value={val.model.name} className={'form-control'}/>
                </div>
            </div>
            <div className={"col-sm-12"}>
                <table className={'table table-bordered'}>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Qurilmalar</th>
                            <th>
                                <button className={'btn btn-xs btn-primary'}><i className={'fa fa-plus'}/></button>
                            </th>
                        </tr>
                        {
                            props?.equipments?.length > 0 && props.equipments.map((item, key) => {
                                return (
                                    <tr>
                                        <td>{(++key)}</td>
                                        <td>
                                            <Select className={"aria-required"}
                                                    id={"hr_department_id"}
                                                    onChange={this.onHandleChange.bind(this, 'select', 'plm_document', 'hr_department_id', '', '', '')}
                                                    placeholder={"Tanlang ..."}
                                                    value={departmentList.filter(({value}) => +value === +plm_document?.hr_department_id)}
                                                    options={departmentList}
                                            />
                                        </td>
                                    </tr>
                                )
                            })
                        }
                    </thead>
                </table>
            </div>
            <div className={"col-sm-12"}>
                <div className={'btn btn-sm btn-success'} onClick={onSave}>Saqlash</div>
                &nbsp;&nbsp;&nbsp;&nbsp;
                <div className={'btn btn-sm btn-danger'} onClick={props.onCancel}>Bekor qilish</div>
            </div>
        </div>
    ):'');
    return <React.Fragment>
        <div className="fade modal show" role="dialog" tabIndex="-1" style={{display: onOpen ? "block" : "none"}} aria-modal="true">
            <div className="modal-dialog modal-lg" role="document">
                <div className="modal-content">
                    <div className="modal-header">
                        <h5>{val.title}</h5>
                        <button onClick={props.onCancel} className="close" data-dismiss="modal">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div className="modal-body none-scroll">
                        <div className={'card-body'}>
                            {html}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </React.Fragment>
}

export default EquipmentGroup;