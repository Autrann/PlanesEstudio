import { useRef, useState } from "react";
import Img from "./Img";

function SelectDropDown({ options, name, containerClass, placeholder, inputClass, icon, iconClass }) {
    const [isOpen, setIsOpen] = useState(false);
    const [selected, setSelected] = useState(null);
    const containerRef = useRef(null);

    const toggleDropdown = () => setIsOpen(prev => !prev);

    const handleSelect = (option) => {
        setSelected(option);
        setIsOpen(false);
    };

    const handleBlur = (e) => {
        if (!containerRef.current.contains(e.relatedTarget)) {
            setIsOpen(false);
        }
    };

    return (
        <div
            className={`relative ${containerClass}`}
            tabIndex={0}
            ref={containerRef}
            onBlur={handleBlur}
        >
            {/* Ícono de flecha */}
            <Img
                type="icon"
                params={{ icon: "downA" }}
                className={`${iconClass} size-8 flex items-center justify-center p-2 rounded-sm bg-[#CAD4DC] absolute top-1/2 -translate-y-1/2 right-[1rem] transition-transform ${isOpen ? "rotate-180" : "rotate-0"}`}
                onClick={toggleDropdown}
            />

            {/* Ícono izquierdo opcional */}
            {icon && (
                <Img
                    type="icon"
                    params={{ icon }}
                    className={`${iconClass} absolute top-1/2 -translate-y-1/2 left-[1rem] transition-colors ${selected || isOpen ? "invert-0" : "invert-50"}`}
                />
            )}

            {/* Etiqueta flotante / placeholder */}
            <p
                className={`absolute transition-all pointer-events-none select-none  ${icon ? "left-[3rem]" : "left-[1rem]"}  ${selected || isOpen ? `text-xs top-0 text-[#2B2B2B] font-bold` : `text-[1.2rem] top-1/2 -translate-y-1/2 text-[#616161]`
                    }`}
            >
                {placeholder}
            </p>

            {/* Campo de selección */}
            <div
                className={`dropdown-scroll w-full h-full border-2 px-4 py-2 cursor-pointer ${inputClass} flex justify-center items-center ${selected || isOpen && "border-[#AFBEC9]"}`}
                onClick={toggleDropdown}
            >
                {selected ? selected.label : ""}
            </div>

            {/* Opciones desplegables */}
            {isOpen && (
                <div
                    className="dropdown-scroll absolute z-10 mt-1 w-full max-h-48 overflow-y-auto bg-white border rounded shadow-md"
                    onClick={(e) => {
                        const optionIndex = e.target.getAttribute('data-index');
                        if (optionIndex !== null) {
                            handleSelect(options[optionIndex]);
                        }
                    }}
                >
                    {options.map((option, i) => (
                        <div
                            key={i}
                            data-index={i}
                            className={`${selected && selected.value === option.value ? "bg-gray-300" : "hover:bg-gray-100"} px-4 py-2 cursor-pointer`}
                        >
                            {option.label}
                        </div>
                    ))}
                </div>
            )}
            <input type="hidden" required name={name} value={selected?.value || ''} />
        </div>
    );
}

export default SelectDropDown;
