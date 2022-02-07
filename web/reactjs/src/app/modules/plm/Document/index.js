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
        this._isMounted = true;
        let {history} = this.props;
        let {searchParams} = this.state;
        const response = await axios.get(API_URL + 'search', searchParams);
        if (response.data.status) {
            this.setState({
                documents: response.data.documents,
                pagination: response.data.pagination,
                // organisationList: response.data.organisationList,
                // departmentList: response.data.departmentList,
                // productList: response.data.productList,
                // equipmentList: response.data.equipmentList,
                // reasonList: response.data.reasonList,
                // repairedList: response.data.repaired,
                // scrappedList: response.data.scrapped,
                language: response.data.language,
                isLoading: false
            });
        } else {
            toast.error(response.data.message);
            setTimeout(function () {
                history.goBack()
            }, 5000);
        }
    }

    onPageChange = async (e) => {
        this.setState({isLoadingSearch: true});
        let page = e?.selected ? +e.selected : 0;
        let {searchParams} = this.state;
        searchParams.page = page;

    }


    render() {
        const {
            isLoading,
            documents,
            pagination,
            searchParams
        } = this.state;
        if (isLoading) return loadingContent();
        let pageCount  = Math.ceil(pagination.totalCount/pagination.defaultPageSize);
        return (
            <div>
                <div className="no-print">
                    <ToastContainer autoClose={3000} position={'top-right'} transition={Flip} draggablePercent={60} closeOnClick={true} pauseOnHover closeButton={true}/>
                </div>
                <div className={'card'}>
                    <div className={'card-header'}>
                        <Link to="/create" className={"btn btn-sm btn-primary"}><i className={"fa fa-plus"}/></Link>
                    </div>
                    <div className={'card-body'}>
                        Body
                    </div>
                    <div className={'card-footer'}>
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
                        />
                    </div>
                </div>
            </div>
        );
    }
}

export default Index;
