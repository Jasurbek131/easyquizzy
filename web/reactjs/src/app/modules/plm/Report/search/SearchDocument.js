import React from 'react';
import ru from "date-fns/locale/ru/index.js";
import DatePicker from "react-datepicker";
import Select from "react-select";
import customStyles from "../../../../actions/style/customStyle.js";

export function SearchDocument(props) {
    return <div className={"card"}>
        <div className="card-body">
            <div className="row">
                <div className="col-lg-3">
                    <label className={"control-label"} id={"begin_date"}>Boshlanish vaqti</label>
                    <DatePicker locale={ru}
                                dateFormat="dd.MM.yyyy HH:mm"
                                id={"begin_date"}
                                className={"form-control"}
                                onChange={(e) => {
                                    props.onHandleChange('begin_date', "date",  new Date(e))
                                }}
                                selected={props?.searchParams?.begin_date ? new Date(props?.searchParams?.begin_date) : ""}
                                autoComplete={'off'}
                                showTimeSelect
                                timeIntervals={5}
                                timeCaption="Вақт"
                    />
                </div>
                <div className="col-lg-3">
                    <label className={"control-label"} id={"end_date"}>Tugash vaqti</label>
                    <DatePicker locale={ru}
                                dateFormat="dd.MM.yyyy HH:mm"
                                id={"end_date"}
                                className={"form-control"}
                                onChange={(e) => {
                                    props.onHandleChange('end_date', "date",  new Date(e))
                                }}
                                selected={props?.searchParams?.end_date ? new Date(props?.searchParams?.end_date) : ""}
                                autoComplete={'off'}
                                showTimeSelect
                                minDate={props?.searchParams?.begin_date}
                                filterTime={(e) => {
                                    return new Date(props?.searchParams?.begin_date) <= new Date(e)
                                }}
                                timeIntervals={5}
                                timeCaption="Вақт"
                    />
                </div>
                <div className="col-lg-3">
                    <label className={"control-label"}>Bo'lim</label>
                    <Select
                        onChange={props.onHandleChange.bind(this, 'hr_department_id', 'select')}
                        placeholder={"Tanlang ..."}
                        id={"hr_department_id1"}
                        value={props.hr_department_list.filter(({value}) => +value === +props?.searchParams?.hr_department_id)}
                        options={props.hr_department_list}
                        styles={customStyles}
                        isClearable={true}
                    />
                </div>
                <div className="col-lg-3">
                    <label className={"control-label"}>Smena</label>
                    <Select
                        onChange={props.onHandleChange.bind(this, 'shift_id', 'select')}
                        placeholder={"Tanlang ..."}
                        id={"shift_id"}
                        value={props.shift_list.filter(({value}) => +value === +props?.searchParams?.shift_id)}
                        options={props.shift_list}
                        styles={customStyles}
                        isClearable={true}
                    />
                </div>
                <div className="col-lg-3">
                    <label className={"control-label"}>Uskuna</label>
                    <Select
                        onChange={props.onHandleChange.bind(this, 'equipment_id', 'select')}
                        placeholder={"Tanlang ..."}
                        id={"equipment_id"}
                        value={props.equipment_list.filter(({value}) => +value === +props?.searchParams?.equipment_id)}
                        options={props.equipment_list}
                        styles={customStyles}
                        isClearable={true}
                    />
                </div>
                <div className="col-lg-3">
                    <label className={"control-label"}>Mahsulot</label>
                    <Select
                        onChange={props.onHandleChange.bind(this, 'product_id', 'select')}
                        placeholder={"Tanlang ..."}
                        id={"product_id"}
                        value={props.product_list.filter(({value}) => +value === +props?.searchParams?.product_id)}
                        options={props.product_list}
                        styles={customStyles}
                        isClearable={true}
                    />
                </div>

            </div>
            <br/>
            <div className="row">
                <div className="col-lg-12">
                    <button onClick={props.onHandleSearch} className={"btn btn-success btn-sm"}>Qidiruv</button>&nbsp;
                    <button onClick={props.onCancelSearch} className={"btn btn-danger btn-sm"}>Bekor qilish</button>
                </div>
            </div>
        </div>
    </div>;
}