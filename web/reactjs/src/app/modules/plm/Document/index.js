import React from "react";
import {Link} from "react-router-dom";
import {Flip, toast, ToastContainer} from 'react-toastify';
import axios from "axios";
import ReactPaginate from "react-paginate";
import {loadingContent} from "../../../actions/functions";

const API_URL = window.location.protocol + "//" + window.location.host + "/api/v1/documents/";

class Index extends React.Component {
    constructor(props, context) {
        super(props, context);
        this.state = {
            documents: [],
            pagination: {
                totalCount: 0,
                defaultPageSize: 20
            },
            searchParams: {
                page: 0
            },
            isLoading: true
        };
    }

    async componentDidMount() {
        await this.onChangeItems(this.state.searchParams);
    }

    onChangeItems = async (searchParams) => {
        let {history} = this.props;
        const response = await axios.post(API_URL + 'search', searchParams);
        if (response.data.status) {
            this.setState({
                documents: response.data.documents,
                pagination: response.data.pagination,
                language: response.data.language,
                isLoading: false
            });
        } else {
            toast.error(response.data.message);
            setTimeout(function () {
                history.goBack()
            }, 5000);
        }
    };

    onPageChange = async (e) => {
        this.setState({isLoadingSearch: true});
        let page = e?.selected ? + e.selected : 0;
        let {searchParams} = this.state;
        searchParams.page = page;
        await this.onChangeItems( searchParams );
    };


    render() {
        const {
            isLoading,
            documents,
            pagination,
            searchParams
        } = this.state;
        if (isLoading) return loadingContent();
        let pageCount = Math.ceil(pagination.totalCount / pagination.defaultPageSize);
        return (
            <div>
                <div className="no-print">
                    <ToastContainer autoClose={3000} position={'top-right'} transition={Flip} draggablePercent={60}
                                    closeOnClick={true} pauseOnHover closeButton={true}/>
                </div>
                <div className={'card'}>
                    <div className={'card-header'}>
                        <Link to="/create" className={"btn btn-sm btn-success"}><i className={"fa fa-plus"}/></Link>
                    </div>
                    <div className={'card-body'}>
                        <table className={"table table-bordered"}>
                            <thead>
                            <tr>
                                <th width={"70px"}>#</th>
                                <th>Hujjat raqami</th>
                                <th>Bo'lim</th>
                                <th>Smena</th>
                                <th width={"100px"}/>
                            </tr>
                            </thead>
                            <tbody>
                            {
                                documents?.length > 0 && documents.map((item, key) => {
                                    return (
                                        <tr key={key}>
                                            <td>{key + 1}</td>
                                            <td>{item.doc_number}</td>
                                            <td>{item.department}</td>
                                            <td>{item.shift}</td>
                                            <td className={"text-center"}>
                                                <button className={"btn btn-info btn-xs"}>
                                                    <i className={"fa fa-eye"}/>
                                                </button>
                                                &nbsp;
                                                <Link to={'/update/' + item.id} className={"btn btn-success btn-xs"}>
                                                    <i className={"fa fa-pencil-alt"}/>
                                                </Link>
                                            </td>
                                        </tr>
                                    )
                                })
                            }
                            </tbody>
                        </table>
                    </div>
                    <div className={'card-footer'}>
                        {
                            documents?.length > 0 ?
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

export default Index;
