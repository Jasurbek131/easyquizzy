/*
 * Created By PhpStorm
 * User Doston Usmonov
 * Time: 23.11.20 21:08
 */

const dot = (color = '#ccc') => ({
    ':before': {
        display: 'none',
    },
});
const customStyles = {
    control: (provided, state) => ({
        ...provided,
        background: '#fff',
        borderRadius: 0,
        borderColor: state.selectProps.borderColor ?? '#d2d6de',
        minHeight: '24px',
        height: state.selectProps.height ?? '24px',
        boxShadow: state.isFocused ? null : null,
    }),

    valueContainer: (provided, state) => ({
        ...provided,
        height: '22px',
        padding: '0 4px'
    }),

    input1: (provided, state) => ({
        ...provided,
        margin: '0px',
    }),
    indicatorSeparator: state => ({
        display: 'none',
    }),
    dropdownIndicator: state => ({
        display: 'none'
    }),
    clearIndicator: state => ({
        width: '5px',
        marginRight: '15px'
    }),
    indicatorsContainer: (provided, state) => ({
        ...provided,
        height: '22px',
    }),
    input: styles => ({ ...styles, ...dot() }),
    placeholder: styles => ({ ...styles, ...dot() }),
    singleValue: (styles, { data }) => ({ ...styles, ...dot(data.color) }),
};
export default customStyles;