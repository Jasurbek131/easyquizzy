    import React, {Component} from 'react';
import {render} from "react-dom";
import axios from "axios";
import {tr} from "react-date-range/dist/locale";
import ReactPaginate from "react-paginate";
import {SearchStop} from "./search/SearchStop";
import {SearchDocument} from "./search/SearchDocument";
import ReactExport from "react-data-export";
const ExcelFile = ReactExport.ExcelFile;
const ExcelSheet = ReactExport.ExcelFile.ExcelSheet;
const API_URL = window.location.protocol + "//" + window.location.host + "/api/v1/plm-stop-reports/";
const initialSearch = {
    language: 'uz',
    page: 0,
    is_search: false,
    begin_date: null,
    end_date: null,
    stop_id: '',
    category_id: '',
};

class PlmStopReport extends Component {

    constructor(props) {
        super(props);
        this.state = {
            items: [],
            stop_list: [],
            category_list: [],
            pagination: {
                totalCount: 0,
                defaultPageSize: 20,
            },
            searchParams: JSON.parse(JSON.stringify(initialSearch))
        }
    }

    componentDidMount() {
        this.getReportData(this.state.searchParams).then(r => {
        });
    };

    async getReportData(searchParams) {
        let response = await axios.post(API_URL + 'index?type=PLM_STOP_DATA', searchParams);
        if (response.data.status) {
            if (searchParams.is_search === false){
                this.setState({
                    stop_list: response.data.stop_list,
                    category_list: response.data.category_list,
                });
            }
            this.setState({
                items: response.data.items,
                pagination: response.data.pagination,
            })
        }
    };

    onPageChange = (e) => {
        let page = e?.selected ? +e.selected : 0;
        let {searchParams} = this.state;
        searchParams.page = page;
        this.getReportData(searchParams).then(r => {

        });
    };

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
        let { searchParams } = this.state;
        searchParams["is_search"] = true;
        this.getReportData(searchParams).then(r => {

        });
    };

    onCancelSearch = () => {
        let searchParams = JSON.parse(JSON.stringify(initialSearch));
        searchParams["is_search"] = true;
        this.getReportData(searchParams).then(r => {

        });
        this.setState({ searchParams });
    };
    writeSimpleExcelRows = (index, item) => {
        return [
            {
                value: (index + 1),
                style: {
                    alignment: {horizontal: "center", vertical: "center"},
                    font: {sz: "8"},
                    border: {
                        left: {style: 'thin', color: {rgb: '000000'}},
                        top: {style: 'thin', color: {rgb: '000000'}},
                        bottom: {style: 'thin', color: {rgb: '000000'}},
                        right: {style: 'thin', color: {rgb: '000000'}},
                    }
                }
            },
            {
                value: item.category_name ? item.category_name : '',
                style: {
                    alignment: {horizontal: "center", vertical: "center"},
                    font: {sz: "8"},
                    border: {
                        left: {style: 'thin', color: {rgb: '000000'}},
                        top: {style: 'thin', color: {rgb: '000000'}},
                        bottom: {style: 'thin', color: {rgb: '000000'}},
                        right: {style: 'thin', color: {rgb: '000000'}},
                    }
                }
            },
            {
                value: item.name ? item.name : '',
                style: {
                    alignment: {horizontal: "center", vertical: "center"},
                    font: {sz: "8"},
                    border: {
                        left: {style: 'thin', color: {rgb: '000000'}},
                        top: {style: 'thin', color: {rgb: '000000'}},
                        bottom: {style: 'thin', color: {rgb: '000000'}},
                        right: {style: 'thin', color: {rgb: '000000'}},
                    }
                }
            },
            {
                value: item.begin_date ? item.begin_date : '',
                style: {
                    alignment: {horizontal: "center", vertical: "center"},
                    font: {sz: "8"},
                    border: {
                        left: {style: 'thin', color: {rgb: '000000'}},
                        top: {style: 'thin', color: {rgb: '000000'}},
                        bottom: {style: 'thin', color: {rgb: '000000'}},
                        right: {style: 'thin', color: {rgb: '000000'}},
                    }
                }
            },
            {
                value: item.end_date ? item.end_date : '',
                style: {
                    alignment: {horizontal: "center", vertical: "center"},
                    font: {sz: "8"},
                    border: {
                        left: {style: 'thin', color: {rgb: '000000'}},
                        top: {style: 'thin', color: {rgb: '000000'}},
                        bottom: {style: 'thin', color: {rgb: '000000'}},
                        right: {style: 'thin', color: {rgb: '000000'}},
                    }
                }
            },
            {
                value: item.diff_date ? 1*item.diff_date/60 : '',
                style: {
                    alignment: {horizontal: "center", vertical: "center"},
                    font: {sz: "8"},
                    border: {
                        left: {style: 'thin', color: {rgb: '000000'}},
                        top: {style: 'thin', color: {rgb: '000000'}},
                        bottom: {style: 'thin', color: {rgb: '000000'}},
                        right: {style: 'thin', color: {rgb: '000000'}},
                    }
                }
            },
        ];
    }
    render() {
        let {
            items,
            pagination,
            searchParams,
            stop_list,
            category_list,
        } = this.state;
        let dataBody = "";
        let iterator = 0;
        let sumDiff = 0;
        let returnDataExl = [];
        let pageCount = Math.ceil(pagination.totalCount / pagination.defaultPageSize);

        const search = <SearchStop
            searchParams={searchParams}
            onHandleChange={this.onHandleChange}
            onHandleSearch={this.onHandleSearch}
            onCancelSearch={this.onCancelSearch}
            stop_list={stop_list}
            category_list={category_list}
        />;

        dataBody = items?.map((item, index) => {
            sumDiff +=  1 * (item.diff_date ?? 0)/60;
            returnDataExl.push(this.writeSimpleExcelRows(index, item));
            return (
                <tr key={index}>
                    <td>{++iterator}</td>
                    <td>{item.category_name ?? ""}</td>
                    <td>{item.name ?? ""}</td>
                    <td>{item.begin_date ?? ""}</td>
                    <td>{item.end_date ?? ""}</td>
                    <td>{1*item.diff_date/60 ?? ""}</td>
                </tr>
            );
        });

        returnDataExl.push([
            {
                value: 'Jami',
                style: {
                    alignment: {horizontal: "center", vertical: "center"},
                    font: {sz: "8"},
                    border: {
                        left: {style: 'thin', color: {rgb: '000000'}},
                        top: {style: 'thin', color: {rgb: '000000'}},
                        bottom: {style: 'thin', color: {rgb: '000000'}},
                        right: {style: 'thin', color: {rgb: '000000'}},
                    }
                },
                width: {hpx: 40}
            },
            {
                value: '',
                style: {
                    alignment: {horizontal: "center", vertical: "center"},
                    font: {sz: "8"},
                    border: {
                        left: {style: 'thin', color: {rgb: '000000'}},
                        top: {style: 'thin', color: {rgb: '000000'}},
                        bottom: {style: 'thin', color: {rgb: '000000'}},
                        right: {style: 'thin', color: {rgb: '000000'}},
                    }
                },
                width: {hpx: 40}
            },
            {
                value: '',
                style: {
                    alignment: {horizontal: "center", vertical: "center"},
                    font: {sz: "8"},
                    border: {
                        left: {style: 'thin', color: {rgb: '000000'}},
                        top: {style: 'thin', color: {rgb: '000000'}},
                        bottom: {style: 'thin', color: {rgb: '000000'}},
                        right: {style: 'thin', color: {rgb: '000000'}},
                    }
                },
                width: {hpx: 40}
            },
            {
                value: '',
                style: {
                    alignment: {horizontal: "center", vertical: "center"},
                    font: {sz: "8"},
                    border: {
                        left: {style: 'thin', color: {rgb: '000000'}},
                        top: {style: 'thin', color: {rgb: '000000'}},
                        bottom: {style: 'thin', color: {rgb: '000000'}},
                        right: {style: 'thin', color: {rgb: '000000'}},
                    }
                },
                width: {hpx: 40}
            },
            {
                value: '',
                style: {
                    alignment: {horizontal: "center", vertical: "center"},
                    font: {sz: "8"},
                    border: {
                        left: {style: 'thin', color: {rgb: '000000'}},
                        top: {style: 'thin', color: {rgb: '000000'}},
                        bottom: {style: 'thin', color: {rgb: '000000'}},
                        right: {style: 'thin', color: {rgb: '000000'}},
                    }
                },
                width: {hpx: 40}
            },
            {
                value: sumDiff,
                style: {
                    alignment: {horizontal: "center", vertical: "center"},
                    font: {sz: "8"},
                    border: {
                        left: {style: 'thin', color: {rgb: '000000'}},
                        top: {style: 'thin', color: {rgb: '000000'}},
                        bottom: {style: 'thin', color: {rgb: '000000'}},
                        right: {style: 'thin', color: {rgb: '000000'}},
                    }
                },
                width: {hpx: 40}
            }
        ]);

        const DataSet = [
            {
                columns: [
                    {
                        title: "№",
                        style: {alignment: {horizontal: "center", vertical: "center"}, font: {sz: "10", bold: true}},
                        width: {hpx: 40, wpx: 30}
                    }, // width in  pixcel
                    {
                        title: "To‘xtalish guruhi",
                        style: {alignment: {horizontal: "center", vertical: "center"}, font: {sz: "10", bold: true}},
                        width: {hpx: 40, wpx: 100}
                    }, // width in  characcters
                    {
                        title: "To‘xtalish turi",
                        style: {alignment: {horizontal: "center", vertical: "center"}, font: {sz: "10", bold: true}},
                        width: {hpx: 40, wpx: 100}
                    }, // width in  characcters
                    {
                        title: "Boshlanish vaqti",
                        style: {alignment: {horizontal: "center", vertical: "center"}, font: {sz: "10", bold: true}},
                        width: {hpx: 40, wpx: 100}
                    }, // width in  characcters
                    {
                        title: "Tugash vaqti",
                        style: {alignment: {horizontal: "center", vertical: "center"}, font: {sz: "10", bold: true}},
                        width: {hpx: 40, wpx: 100}
                    }, // width in  characcters
                    {
                        title: "To‘xtalish vaqti(min)",
                        style: {alignment: {horizontal: "center", vertical: "center"}, font: {sz: "10", bold: true}},
                        width: {hpx: 40, wpx: 120}
                    }, // width in  characcters


                ],
                data: returnDataExl
            }
        ];
        return (
            <div>
                {search}
                <div className="card">
                    <div>
                        <ExcelFile
                            filename={"To‘xtalishlar"}
                            element={<button type="button" className="btn btn-info btn-xs float-right m-3"><i className={'fa fa-file-excel-o'}/>Excel</button>}>
                            <ExcelSheet dataSet={DataSet} name="Download"/>
                        </ExcelFile>
                    </div>
                    <div className="card-body">
                        <table className={"table table-bordered table-stripped table-hover"}>
                            <thead>
                                <tr>
                                    <th>№</th>
                                    <th>To‘xtalish guruhi</th>
                                    <th>To‘xtalish turi</th>
                                    <th>Boshlanish vaqti</th>
                                    <th>Tugash vaqti</th>
                                    <th>To‘xtalish vaqti(min)</th>
                                </tr>
                            </thead>
                            <tbody>
                                {dataBody}
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colSpan={5}>Jami</td>
                                    <td>{sumDiff}</td>
                                </tr>
                            </tfoot>
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

            </div>
        );
    }
}

render((<PlmStopReport/>), window.document.getElementById('root'));
