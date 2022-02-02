export const removeElement = (arr, key) => {
    let i = 0;
    let newArr = [];
    if (arr?.length > 0) {
        arr.map((item, itemKey) => {
            if (key !== itemKey) {
                newArr[i] = item;
                i++;
            }
        });
    }
    return newArr;
};


