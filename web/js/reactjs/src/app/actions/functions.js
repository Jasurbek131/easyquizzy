var hasOwnProp = Object.prototype.hasOwnProperty;
export const isEmpty = (obj)=>{
    if(obj == null) return true;
    if(obj == "undefined") return true;
    if(obj.length>0) return false;
    if(obj.length === 0) return true;
    if(typeof obj !== "object") return true;
    for (var  key in obj){
        if(hasOwnProp.call(obj, key)) return false;
    }
    return true;
};
export const convertStringToDate=(date="1970-01-01", input="Y-m-d", output="d-m-Y", split="-")=>{
    switch (output){
        case "d-m-Y":
            var DH = date.split(' ');
            var D = DH[0].split(split);
            return D[2]+"-"+D[1]+"-"+D[0];
            break;
        default:
            var DH = date.split(' ');
            var D = DH[0].split(split);
            return D[2]+"-"+D[1]+"-"+D[0];
            break;
    }
};
export const getPriority = (id) => {
    const priorityList = [];
    priorityList[1] = "Muhim emas";
    priorityList[2] = "Normal";
    priorityList[3] = "Muhim";
    priorityList[4] = "O'ta muhim";
    return priorityList[id];
}
