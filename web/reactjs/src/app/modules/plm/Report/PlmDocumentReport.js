import React, {Component} from 'react';
import {render} from "react-dom";
import axios from "axios";
import {tr} from "react-date-range/dist/locale";
import {SearchDocument} from "./search/SearchDocument";
import ReactPaginate from "react-paginate";
import {PieChart, Pie, Sector, Cell, ResponsiveContainer} from 'recharts';

const API_URL = window.location.protocol + "//" + window.location.host + "/api/v1/plm-document-reports/";
const initialSearch = {
    begin_date: null,
    end_date: null,
    hr_department_id: '',
    shift_id: '',
    equipment_id: '',
    product_id: '',
    language: 'uz',
    page: 0,
    is_search: false,
};
const data = [
    {name: 'Group A', value: 400},
    {name: 'Group B', value: 300},
];

const COLORS = ['rgb(0, 136, 254)', 'rgb(255, 128, 66)'];

const RADIAN = Math.PI / 180;
const renderCustomizedLabel = ({cx, cy, midAngle, innerRadius, outerRadius, percent, index}) => {
    const radius = innerRadius + (outerRadius - innerRadius) * 0.5;
    const x = cx + radius * Math.cos(-midAngle * RADIAN);
    const y = cy + radius * Math.sin(-midAngle * RADIAN);

    return (
        <text x={x} y={y} fill="white" textAnchor={x > cx ? 'start' : 'end'} dominantBaseline="central">
            {`${(percent * 100).toFixed(0)}%`}
        </text>
    );
};

class PlmDocumentReport extends Component {
    constructor(props) {
        super(props);
        this.state = {
            items: [],
            pie_data: {
                a_data: [
                    {name: 'Group A', value: 400},
                    {name: 'Group B', value: 300},
                ],
                p_data: [
                    {name: 'Group A', value: 400},
                    {name: 'Group B', value: 300},
                ],
                q_data: [
                    {name: 'Group A', value: 400},
                    {name: 'Group B', value: 300},
                ],
                oee_data: [
                    {name: 'Group A', value: 400},
                    {name: 'Group B', value: 300},
                ],
            },
            hr_department_list: [],
            shift_list: [],
            equipment_list: [],
            product_list: [],
            pagination: {
                totalCount: 0,
                defaultPageSize: 20,
            },
            searchParams: JSON.parse(JSON.stringify(initialSearch))
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
        let {searchParams} = this.state;
        searchParams["is_search"] = true;
        this.getReportData(searchParams).then(r => {

        });
    };

    onCancelSearch = () => {
        let searchParams = JSON.parse(JSON.stringify(initialSearch));
        searchParams["is_search"] = true;
        this.getReportData(searchParams).then(r => {

        });
        this.setState({searchParams});
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
            if (searchParams.is_search === false) {
                this.setState({
                    hr_department_list: response.data.hr_department_list,
                    shift_list: response.data.shift_list,
                    equipment_list: response.data.equipment_list,
                    product_list: response.data.product_list,
                });
            }
            this.setState({
                items: response.data.items,
                pagination: response.data.pagination,
            })
        }
    };

    sumValue = (items, value) => {
        let summ = 0;
        if (items && items.length > 0) {
            items.forEach(function (item, index) {
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
            hr_department_list,
            shift_list,
            equipment_list,
            product_list,
            pagination,
            pie_data,
        } = this.state;
        let pageCount = Math.ceil(pagination.totalCount / pagination.defaultPageSize);
        let dataBody = "";
        let iterator = 0;
        let itemProductLength = 0;

        const search = <SearchDocument
            searchParams={searchParams}
            onHandleChange={this.onHandleChange}
            onHandleSearch={this.onHandleSearch}
            onCancelSearch={this.onCancelSearch}
            hr_department_list={hr_department_list}
            shift_list={shift_list}
            equipment_list={equipment_list}
            product_list={product_list}
            language={language}
        />;

        let sumPercentA = 1;
        let sumPercentP = 1;
        let sumPercentQ = 1;
        let sumPercentOee = 1;
        dataBody = items?.map((item, index) => {

            itemProductLength = item?.products?.length ?? 0;
            let returnDocItemProductData;

            if (itemProductLength > 0) {
                returnDocItemProductData = item?.products.map((productItem, productIndex) => {
                    if (+productIndex === 0) {

                        let finalPlanDate = +item.plan_date - (+item.plan_stop_date) - (+item.unplan_stop_date);

                        let sumFactQty = this.sumValue(item?.products, 'fact_qty');
                        let sumQty = this.sumValue(item?.products, 'qty');
                        let sumRepaired = this.sumValue(item?.products, 'repaired_count');
                        let sumScrapped = this.sumValue(item?.products, 'scrapped_count');

                        let percentA = (finalPlanDate / item.plan_date * 100).toFixed(2);
                        let percentP = ((sumFactQty + sumQty) / (+item.target_qty) * 100).toFixed(2);
                        let percentQ = ((sumFactQty + sumQty - sumRepaired - sumScrapped) / (sumFactQty + sumQty) * 100).toFixed(2);
                        sumPercentA *= (percentA / 100);
                        sumPercentP *= (percentP / 100);
                        sumPercentQ *= (percentQ / 100);
                        sumPercentOee *= (percentA * percentP * percentQ / 1000000);
                        console.log(sumPercentA, sumPercentP, sumPercentQ, sumPercentOee);
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
                                <td rowSpan={itemProductLength} className={"a"}>{(+item.plan_date).toFixed(2)}</td>
                                <td rowSpan={itemProductLength} className={"a"}>{finalPlanDate}</td>
                                <td rowSpan={itemProductLength} className={"a"}>{percentA}</td>
                                <td rowSpan={itemProductLength} className={"p"}>{+item.target_qty}</td>
                                <td className={"p"}>{+productItem.fact_qty + (+productItem.qty)}</td>
                                <td rowSpan={itemProductLength} className={"p"}>{percentP}</td>
                                <td className={"q"}>{+productItem.fact_qty + (+productItem.qty) - (+productItem.repaired_count) - (+productItem.scrapped_count)}</td>
                                <td className={"q"}>{(+productItem.repaired_count)}</td>
                                <td className={"q"}>{(+productItem.scrapped_count)}</td>
                                <td className={"q"} rowSpan={itemProductLength}>{percentQ}</td>
                                <td className={"oee"}
                                    rowSpan={itemProductLength}>{(percentA * percentP * percentQ / 10000).toFixed(2)}</td>
                            </tr>
                        );
                    } else {
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
        sumPercentA = sumPercentA*100;
        sumPercentP = sumPercentP*100;
        sumPercentQ = sumPercentQ*100;
        sumPercentOee = sumPercentOee*100;
        pie_data.a_data = [
            {name: 'Benefit percent', value: sumPercentA},
            {name: 'Loss percent', value: 100 - sumPercentA}
        ];
        pie_data.p_data = [
            {name: 'Benefit percent', value: sumPercentP},
            {name: 'Loss percent', value: 100 - sumPercentP}
        ];
        pie_data.q_data = [
            {name: 'Benefit percent', value: sumPercentQ},
            {name: 'Loss percent', value: 100 - sumPercentQ}
        ];
        pie_data.oee_data = [
            {name: 'Benefit percent', value: sumPercentOee},
            {name: 'Loss percent', value: 100 - sumPercentOee}
        ];
        return (<div>
            {search}
            <div className={"card"}>
                <div className={"card-body"} style={{overflow: "scroll"}}>
                    <table className={"table table-stripped table-condensed table-bordered table-reponsive"}>
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
                <div className="card card-info" >
                    <div className="card-header" style={{backgroundColor:'#05d0ebad'}}>
                        <h3 className="card-title">
                            <i className="fas fa-chart-pie"/>
                        </h3>
                        <div className="card-tools">
                            <button type="button" className="btn btn-tool" data-card-widget="maximize">
                                <i className="fas fa-expand"/>
                            </button>
                            <button type="button" className="btn btn-tool" data-card-widget="collapse">
                                <i className="fas fa-minus"/>
                            </button>
                            <button type="button" className="btn btn-tool" data-card-widget="remove">
                                <i className="fas fa-times"/>
                            </button>
                        </div>

                    </div>

                    <div className="card-body">
                        <div className="row">
                            <div className="col-md-3">
                                <h3>A</h3>
                                <PieChart width={250} height={250}>
                                    <Pie
                                        data={pie_data.a_data}
                                        cx="50%"
                                        cy="50%"
                                        labelLine={false}
                                        label={renderCustomizedLabel}
                                        outerRadius={100}
                                        fill="#8884d8"
                                        dataKey="value"
                                    >
                                        {data.map((entry, index) => (
                                            <Cell key={`cell-${index}`} fill={COLORS[index % COLORS.length]}/>
                                        ))}
                                    </Pie>
                                </PieChart>
                            </div>
                            <div className="col-md-3">
                                <h3>P</h3>
                                <PieChart width={250} height={250}>
                                    <Pie
                                        data={pie_data.p_data}
                                        cx="50%"
                                        cy="50%"
                                        labelLine={false}
                                        label={renderCustomizedLabel}
                                        outerRadius={100}
                                        fill="#8884d8"
                                        dataKey="value"
                                    >
                                        {data.map((entry, index) => (
                                            <Cell key={`cell-${index}`} fill={COLORS[index % COLORS.length]}/>
                                        ))}
                                    </Pie>
                                </PieChart>
                            </div>
                            <div className="col-md-3">
                                <h3>Q</h3>
                                <PieChart width={250} height={250}>
                                    <Pie
                                        data={pie_data.q_data}
                                        cx="60%"
                                        cy="50%"
                                        labelLine={false}
                                        label={renderCustomizedLabel}
                                        outerRadius={100}
                                        fill="#8884d8"
                                        dataKey="value"
                                    >
                                        {data.map((entry, index) => (
                                            <Cell key={`cell-${index}`} fill={COLORS[index % COLORS.length]}/>
                                        ))}
                                    </Pie>
                                </PieChart>
                            </div>
                            <div className="col-md-3">
                                <h3>OEE</h3>
                                <PieChart width={250} height={250}>
                                    <Pie
                                        data={pie_data.oee_data}
                                        cx="50%"
                                        cy="50%"
                                        labelLine={false}
                                        label={renderCustomizedLabel}
                                        outerRadius={100}
                                        fill="#8884d8"
                                        dataKey="value"
                                    >
                                        {data.map((entry, index) => (
                                            <Cell key={`cell-${index}`} fill={COLORS[index % COLORS.length]}/>
                                        ))}
                                    </Pie>
                                </PieChart>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>);
    }
}

render((<PlmDocumentReport/>), window.document.getElementById('root'));
