import Img from "../../atoms/Img";

const Modal = ({
    modalInstructions,
    handleCloseModal,
    children,
}) => {
    const handleLocalCloseModal = (e) => {
        e.preventDefault();
        handleCloseModal();
    };
    return (
        <div className="fixed z-20 h-full w-full flex items-center justify-center">
            {/* Fondo con opacidad */}
            <div className="absolute inset-0 bg-black opacity-50 z-10"></div>

            {/* Modal centrado */}
            <div className="relative z-30 w-1/2 bg-white p-4 rounded space-y-2">
                <div className="flex space-x-2 justify-between">
                    <div className="flex items-center space-x-2">
                        <Img className={"w-10"} params={{ icon: modalInstructions.current.icon }} />
                        <h1 className="text-2xl font-bold">{modalInstructions.current.title}</h1>
                    </div>
                    <button onClick={(e) => handleLocalCloseModal(e)}>X</button>
                </div>
                <h3 className="text-base text-[#A29797]">{modalInstructions.current.subtitle}</h3>
                <hr />
                {children}
            </div>
        </div>
    );
};

export default Modal;
