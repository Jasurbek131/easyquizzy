import React from 'react';
import ru from "date-fns/locale/ru/index.js";
import DatePicker from "react-datepicker";
import Select from "react-select";
import customStyles from "../../../../actions/style/customStyle.js";

export function SearchStop(props) {
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
                    <label className={"control-label"}>To'xtalishlar guruhi</label>
                    <Select
                        onChange={props.onHandleChange.bind(this, 'category_id', 'select')}
                        placeholder={"Tanlang ..."}
                        id={"category_id"}
                        value={props.category_list.filter(({value}) => +value === +props?.searchParams?.category_id)}
                        options={props.category_list}
                        styles={customStyles}
                        isClearable={true}
                    />
                </div>
                <div className="col-lg-3">
                    <label className={"control-label"}>To'xtalishlar turi</label>
                    <Select
                        onChange={props.onHandleChange.bind(this, 'stop_id', 'select')}
                        placeholder={"Tanlang ..."}
                        id={"stop_id"}
                        value={props.stop_list.filter(({value}) => +value === +props?.searchParams?.stop_id)}
                        options={props.stop_list}
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