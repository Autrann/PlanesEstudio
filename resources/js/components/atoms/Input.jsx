import { useState, useRef } from "react";
import Img from "./Img";

function Input({
    name,
    containerClass,
    placeholder,
    inputClass,
    icon,
    iconClass,
    type,
    placeholderPosition=undefined,
}) {
    const [selectedInput, setSelectedInput] = useState(false);
    const inputRef = useRef(null);

    const handleOnFocus = () => {
        setSelectedInput(true);
    };

    const handleOnBlur = () => {
        if (inputRef.current && inputRef.current.value === "") {
            setSelectedInput(false);
        }
    };

    const handlePlaceHolderStyle = (placeholderPosition) => {
        let commonStyle = "transition-all pointer-events-none select-none";
        
        switch (placeholderPosition) {
            case "enter":
                return `${commonStyle} absolute ${
                    icon ? "left-[3rem]" : "left-[1rem]"
                } ${
                    selectedInput
                        ? `text-xs top-0 text-[#2B2B2B] font-bold`
                        : `text-[1.2rem] top-1/2 -translate-y-1/2 text-[#616161]`
                }`;
            case "out":
                return `${commonStyle} text-[1.2rem] top-0`;
            default:
                return "";
        }
    };

    return (
        <div
            className={`relative ${containerClass}`}
            onClick={() => inputRef.current.focus()}
        >
            {/* Ícono izquierdo opcional */}
            {icon && (
                <Img
                    type="icon"
                    params={{ icon }}
                    className={`${iconClass} absolute top-1/2 -translate-y-1/2 left-[1rem] transition-colors ${
                        selectedInput ? "invert-0" : "invert-50"
                    }`}
                />
            )}

            {/* Etiqueta flotante / placeholder */}
            <p
                className={`${handlePlaceHolderStyle(placeholderPosition)}`}
            >
                {placeholder}
            </p>

            {/* Campo de Input */}
            <input
                type={type}
                required
                name={name}
                id={name}
                ref={inputRef}
                className={`${inputClass} w-full text-xl ${
                    icon ? "pl-[3rem]" : "pl-[1rem]"
                } outline-0 rounded-lg border-[#CFC3C3]`}
                onFocus={handleOnFocus}
                onBlur={handleOnBlur}
            />
        </div>
    );
}

export default Input;
