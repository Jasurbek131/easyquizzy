import {Flip, ToastContainer} from "react-toastify";
import React from "react";

export const removeElement = (arr, key) => {
    let i = 0;
    let newArr = [];
    if (arr?.length > 0) {
        arr.map((item, itemKey) => {
            if (+key !== +itemKey) {
                newArr[i] = item;
                i++;
            }
        });
    }
    return newArr;
};

export const loadingContent = () => {
    return (
        <div>
            <div className="no-print">
                <ToastContainer autoClose={6000} position={'top-center'} transition={Flip} draggablePercent={60} closeOnClick={true} pauseOnHover closeButton={true}/>
            </div>
            <div id="contentDiv" className="timeline-wrapper">
                <div className="timeline-item">
                    <div className="animated-background">
                        <div className="background-masker header-top"/>
                        <div className="background-masker header-left"/>
                        <div className="background-masker header-right"/>
                        <div className="background-masker header-bottom"/>
                        <div className="background-masker subheader-left"/>
                        <div className="background-masker subheader-right"/>
                        <div className="background-masker subheader-bottom"/>
                        <div className="background-masker content-top"/>
                        <div className="background-masker content-first-end"/>
                        <div className="background-masker content-second-line"/>
                        <div className="background-masker content-second-end"/>
                        <div className="background-masker content-third-line"/>
                        <div className="background-masker content-third-end"/>
                    </div>
                </div>
            </div>
        </div>
    )
}


