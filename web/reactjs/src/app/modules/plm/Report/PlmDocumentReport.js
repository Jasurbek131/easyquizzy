import React, {Component} from 'react';
import {render} from "react-dom";
import axios from "axios";
import {tr} from "react-date-range/dist/locale";
import {Search} from "./Search";
import ReactPaginate from "react-paginate";

const API_URL = window.location.protocol + "//" + window.location.host + "/api/v1/plm-document-reports/";

class PlmDocumentReport extends Component {
    constructor(props) {
        super(props);
        this.state = {
            items: [],
            pagination: {
                totalCount: 0,
                defaultPageSize: 20,
            },
            searchParams: {
                start_date: null,
                end_date: null,
                language: 'uz',
                page: 0
            }
        }
    }

    onHandleChange = (name, type, e) => {
        let v;
        switch (type) {
            case "select":
                v = e?.value ?? "";
                break;
            case "multi-select":
                v = e;
                break;
            case "date":
                v = e;
                break;
            case "input":
            case "textarea":
                v = e?.target?.value ?? "";
                break;
        }
        let {searchParams} = this.state;
        searchParams[name] = v;
        this.setState({searchParams});
    };

    onHandleSearch = () => {

    };

    onPageChange = (e) => {
        let page = e?.selected ? +e.selected : 0;
        let {searchParams} = this.state;
        searchParams.page = page;
        this.getReportData(searchParams).then(r => {

        });
    };

    componentDidMount() {
        this.getReportData(this.state.searchParams).then(r => {
        });
    };

    async getReportData(searchParams) {
        let response = await axios.post(API_URL + 'index?type=PLM_DOCUMENT_DATA', searchParams);
        if (response.data.status) {
            this.setState({
                items: response.data.items,
                pagination: response.data.pagination,
            })
        }
    };

    sumValue = (items, value) => {
        let summ = 0;
        if (items && items.length > 0){
            items.forEach(function (item, index){
                summ += item[value];
            });
        }
        return summ;
    }

    render() {

        let {
            items,
            searchParams,
            language,
            pagination,
        } = this.state;
        let pageCount = Math.ceil(pagination.totalCount / pagination.defaultPageSize);
        let dataBody = "";
        let iterator = 0;
        let itemProductLength = 0;

        const search = <Search
            searchParams={searchParams}
            onHandleChange={this.onHandleChange}
            onHandleSearch={this.onHandleSearch}
            language={language}
        />;

         dataBody = items?.map((item, index) => {

            itemProductLength = item?.products?.length ?? 0;
            let returnDocItemProductData;

            if (itemProductLength > 0){
                returnDocItemProductData = item?.products.map((productItem, productIndex) => {
                    if(+productIndex === 0){

                        let finalPlanDate =  +item.plan_date - (+item.plan_stop_date) - (+item.unplan_stop_date);

                        let sumFactQty = this.sumValue(item?.products, 'fact_qty');
                        let sumQty = this.sumValue(item?.products, 'qty');
                        let sumRepaired = this.sumValue(item?.products, 'repaired_count');
                        let sumScrapped = this.sumValue(item?.products, 'scrapped_count');

                        let percentA = ( finalPlanDate / item.plan_date * 100).toFixed(2);
                        let percentP = (( sumFactQty + sumQty) / (+item.target_qty) * 100).toFixed(2);
                        let percentQ = ((sumFactQty + sumQty - sumRepaired - sumScrapped) /  (sumFactQty +  sumQty) * 100).toFixed(2);

                        return (
                            <tr key={index + "_" + productIndex}>
                                <td rowSpan={itemProductLength}>{++iterator}</td>
                                <td rowSpan={itemProductLength}>{item.organisation_name ?? ""}</td>
                                <td rowSpan={itemProductLength}>{item.department_name ?? ""}</td>
                                <td rowSpan={itemProductLength}>{item.shift_name ?? ""}</td>
                                <td rowSpan={itemProductLength}>{item.format_reg_date ?? ""}</td>
                                <td rowSpan={itemProductLength}>{item.begin_date}</td>
                                <td rowSpan={itemProductLength}>{item.end_date}</td>
                                <td rowSpan={itemProductLength}>{item.equipment}</td>
                                <td>{productItem.product_name ?? ""}</td>
                                <td rowSpan={itemProductLength}>{item.lifecycle}</td>
                                <td rowSpan={itemProductLength}>{item.bypass}</td>
                                <td rowSpan={itemProductLength} className={"a"}>{item.plan_date}</td>
                                <td rowSpan={itemProductLength} className={"a"}>{ finalPlanDate }</td>
                                <td rowSpan={itemProductLength} className={"a"}>{ percentA }</td>
                                <td rowSpan={itemProductLength} className={"p"}>{+item.target_qty}</td>
                                <td className={"p"}>{+productItem.fact_qty + (+productItem.qty)}</td>
                                <td rowSpan={itemProductLength} className={"p"}>{ percentP }</td>
                                <td className={"q"}>{+productItem.fact_qty + (+productItem.qty) - (+productItem.repaired_count) - (+productItem.scrapped_count)}</td>
                                <td className={"q"}>{(+productItem.repaired_count)}</td>
                                <td className={"q"}>{(+productItem.scrapped_count)}</td>
                                <td className={"q"} rowSpan={itemProductLength}>{ percentQ }</td>
                                <td className={"oee"} rowSpan={itemProductLength}>{ (percentA * percentP * percentQ / 10000).toFixed(2)}</td>
                            </tr>
                        );
                    }else{
                        return (
                            <tr key={index + "_" + productIndex}>
                                <td>{productItem.product_name ?? ""}</td>
                                <td className={"p"}>{+productItem.fact_qty + (+productItem.qty)}</td>
                                <td className={"q"}>{+productItem.fact_qty + (+productItem.qty) - (+productItem.repaired_count) - (+productItem.scrapped_count)}</td>
                                <td className={"q"}>{(+productItem.repaired_count)}</td>
                                <td className={"q"}>{(+productItem.scrapped_count)}</td>
                            </tr>
                        );
                    }
                });
            }
            return (returnDocItemProductData);
        });

        return (<div>
            {search}
            <div className={"card"}>
                <div className={"card-body"}>
                    <table className={"table table-stripped table-condensed table-bordered"}>
                        <thead>
                        <tr>
                            <th rowSpan={2}>№</th>
                            <th rowSpan={2}>Tashkilot</th>
                            <th rowSpan={2}>Bo'lim</th>
                            <th rowSpan={2}>Smena</th>
                            <th rowSpan={2}>Sana</th>
                            <th rowSpan={2}>Boshlanish vaqti</th>
                            <th rowSpan={2}>Tugash vaqti</th>
                            <th rowSpan={2}>Uskunalar</th>
                            <th rowSpan={2}>Mahsulot</th>
                            <th rowSpan={2}>CT (sec)</th>
                            <th rowSpan={2}>By pass CT(sec)</th>
                            <th colSpan={3} className={"a"}>A</th>
                            <th colSpan={3} className={"p"}>P</th>
                            <th colSpan={4} className={"q"}>Q</th>
                            <th className={"oee"}>OEE</th>
                        </tr>
                        <tr>
                            <th className={"a"}>Reja</th>
                            <th className={"a"}>Fakt</th>
                            <th className={"a"}>%</th>
                            <th className={"p"}>Reja</th>
                            <th className={"p"}>Fakt</th>
                            <th className={"p"}>%</th>
                            <th className={"q"}>Ok</th>
                            <th className={"q"}>Tamir</th>
                            <th className={"q"}>Brak</th>
                            <th className={"q"}>%</th>
                            <th className={"oee"}>%</th>
                        </tr>

                        </thead>
                        <tbody>
                        {dataBody}
                        </tbody>
                    </table>
                </div>
                <div className={'card-footer'}>
                    {
                        items?.length > 0 ?
                            <ReactPaginate
                                pageCount={pageCount}
                                pageRangeDisplayed={7}
                                marginPagesDisplayed={1}
                                forcePage={+searchParams.page}
                                previousLabel={"«"}
                                nextLabel={"»"}
                                containerClassName={"pagination"}
                                activeClassName={"active"}
                                nextClassName={"lasts"}
                                previousClassName={"first"}
                                disabledClassName={"disable"}
                                onPageChange={(e) => {
                                    this.onPageChange(e).then(r => '')
                                }}
                            /> : ""
                    }
                </div>

            </div>
        </div>);
    }
}

render((<PlmDocumentReport/>), window.document.getElementById('root'));
