const dot = (color = '#ccc') => ({
    alignItems: 'center',
    display: 'flex',
    ':before': {
        backgroundColor: color,
        borderRadius: 10,
        content: '" "',
        display: 'block',
        marginRight: 8,
        height: 5,
        width: 5,
    },
});
const style = {
    control: (provided, state) => ({
        ...provided,
        borderColor: state.selectProps.borderColor ?? '#d2d6de',
        background: state.selectProps.background ?? '#fff',
        pointerEvents: state.selectProps.pointerEvents ?? 'auto',
        minHeight: 'calc(1.8em + 0.25rem)',
        boxShadow: state.isFocused ? null : null,
    }),

    options: (provided, state) => ({

    }),

    valueContainer: (provided, state) => ({
        ...provided,
        margin: '-5px 1px'
    }),

    input1: (provided, state) => ({
        ...provided,
    }),
    indicatorSeparator: state => ({
        display: 'none',
    }),
    indicatorsContainer: (provided, state) => ({
        ...provided,
        height: '22px',
    }),

    multiValueLabel: (provided, state) => ({
        ...provided,
        padding: 0,
    }),

    input: styles => ({ ...styles, ...dot() }),
    placeholder: styles => ({ ...styles, ...dot() }),
    singleValue: (styles, { data }) => ({ ...styles, ...dot(data.color) }),
};
export default style;