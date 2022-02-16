export const items = {
    id: "",
    product_id: "",
    planned_stop_id: "",
    unplanned_stop_id: "",
    processing_time_id: "",
    equipment_group_id: "",
    start_work: "",
    end_work: "",
    planned_stopped: {
        id: "",
        begin_date: "",
        end_time: "",
        add_info: "",
        reason_id: ""
    },
    unplanned_stopped: {
        id: "",
        begin_date: "",
        end_time: "",
        add_info: "",
        reason_id: "",
        bypass: ""
    },
    equipmentGroup: {
        equipments: [],
        productLifecycles: [],
    },
    products: [{
        label: "",
        value: "",
        qty: "",
        fact_qty: "",
        product_id: "",
        product_lifecycle_id: "",
        lifecycle: "",
        repaired: [],
        scrapped: [],
    }],
    equipments: []
};