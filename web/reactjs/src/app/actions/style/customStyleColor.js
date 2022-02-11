/*
 * Created By PhpStorm
 * User Doston Usmonov
 * Time: 23.11.20 21:08
 */

const dot = (color = '#ccc') => ({
    alignItems: 'center',
    display: 'flex',
    ':before': {
        backgroundColor: color,
        borderRadius: 2,
        content: '" "',
        display: 'block',
        marginRight: 8,
        height: 10,
        width: 35,
    },
});

const customStyles = {
    control: (provided, state) => ({
        ...provided,
        background: '#fff',
        borderRadius: 0,
        borderColor: '#d2d6de',
        minHeight: '24px',
        height: '24px',
        boxShadow: state.isFocused ? null : null,
    }),
    valueContainer: (provided, state) => ({
        ...provided,
        height: '22px',
        padding: '0 3px'
    }),

    input1: (provided, state) => ({
        ...provided,
        margin: '0px',
    }),
    indicatorSeparator: state => ({
        display: 'none',
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