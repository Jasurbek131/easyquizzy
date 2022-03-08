import React from 'react';
import ru from "date-fns/locale/ru/index.js";
import DatePicker from "react-datepicker";

export function Search(props) {
    return <div className={"card"}>
        <div className="card-body">
            <div className="row">
                <div className="col-lg-6">
                    <label className={"control-label"} id={"start_date"}>Sanadan</label>
                    <DatePicker locale={ru}
                                dateFormat="dd.MM.yyyy HH:mm"
                                id={"start_date"}
                                className={"form-control"}
                                onChange={(e) => {
                                    props.onHandleChange('start_date', "date",  new Date(e))
                                }}
                                selected={props?.searchParams?.start_date ? new Date(props?.searchParams?.start_date) : ""}
                                autoComplete={'off'}
                                showTimeSelect
                                timeIntervals={5}
                                timeCaption="Вақт"
                    />
                </div>
                <div className="col-lg-6">
                    <label className={"control-label"} id={"end_date"}>Sanagacha</label>
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
                                minDate={props?.searchParams?.start_date}
                                timeIntervals={5}
                                timeCaption="Вақт"
                    />
                </div>
            </div>
            <br/>
            <div className="row">
                <div className="col-lg-12">
                    <button onClick={props.onHandleSearch} className={"btn btn-success btn-sm"}>Qidiruv</button>&nbsp;
                    <button onClick={props.onHandleSearch} className={"btn btn-danger btn-sm"}>Bekor qilish</button>
                </div>
            </div>
        </div>
    </div>;
}