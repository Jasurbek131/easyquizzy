export const items = {
    id: "",
    product_id: "",
    planned_stop_id: "",
    unplanned_stop_id: "",
    repaired_id: "",
    scrapped_id: "",
    planned_stop_change: false,
    unplanned_stop_change: false,
    repaired_change: false,
    scrapped_change: false,
    processing_time_id: "",
    start_work: "",
    end_work: "",
    qty: "",
    fact_qty: "",
    repaired: [],
    scrapped: [],
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
        equipmentGroupRelationEquipments: [
            { label: "", value: "" }
        ]
    }
};