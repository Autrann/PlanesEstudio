import Img from "../atoms/Img";

function Subjects({
    period = undefined,
    subject,
    index = undefined,
    handleOpenModal = undefined,
    containerClassName = undefined,
    menuMode = 1,
    onSubjectClick = () => {},
    isSerializationSource = false,
    serializations = [],
}) {
    // Get prerequisites for this subject
    const prerequisites = serializations
        .filter(serial => serial.to.period === period && serial.to.index === index)
        .map(serial => {
            // Find the source subject in the DOM
            const sourceElement = document.querySelector(`[data-subject="${serial.from.period}-${serial.from.index}"]`);
            const sourceKey = sourceElement?.querySelector('[data-subject-key]')?.textContent;
            return sourceKey || '';
        })
        .filter(key => key);

    const handleOnClickSubject = (e) => {
        e.preventDefault();
        if (menuMode === 2) {
            if (subject) {
                onSubjectClick(period, index);
                return;
            }
        } else if (subject) return;

        const modalInstructions = {
            title: "Agregar Materia",
            subtitle: "Seleccionar materia a insertar",
            icon: "addSubject",
            type: "createSubject",
        };
        handleOpenModal(modalInstructions, period, index);
    };

    const handleCaceiBG = (ClaCA) => {
        switch (ClaCA) {
            case "IA":
                return "bg-[#FC0000] text-white";
            case "CB":
                return "bg-[#B2A1C8] text-white ";
            case "CI":
                return "bg-[#3366FF] text-white";
            case "CS":
                return "bg-[#FFFF00] text-red-900";
            case "CE":
                return "bg-[#FF6804] text-white";
            case "CC":
                return "bg-white text-black";
            default:
                return "";
        }
    };

    const handleSubClickSubject = (e) => {
        e.preventDefault();
        if (!subject || menuMode === 2) return;
        const modalInstructions = {
            type: "secundarySubject",
        };
        handleOpenModal(modalInstructions, period, index);
    };

    return (
        <div className={`${containerClassName} w-full`}>
            <div
                data-subject={`${period}-${index}`}
                onClick={(e) => handleOnClickSubject(e)}
                onContextMenu={(e) => handleSubClickSubject(e)}
                className={`flex flex-col transition-all ${
                    handleOpenModal && "cursor-pointer"
                } text-center ${
                    !subject
                        ? handleOpenModal
                            ? "hover:bg-[#b0cadf] items-center justify-center border-dashed border-[#879CAC]"
                            : "border-white"
                        : "flex-col border-black"} 
                        ${subject && subject.tipoMateria === "dfm" ? "border-4" :
                            subject && subject.tipoMateria ==="common" ? "border-4 border-double" :
                            subject && subject.tipoMateria === "ing" ? "border-2 border-dashed" : "border-2"
                        } w-full h-full bg-white select-none rounded-none 
                        ${menuMode === 2 && subject ? "hover:bg-blue-100" : ""}
                        ${isSerializationSource ? "bg-blue-200" : ""}
                        ${menuMode === 2 && subject ? "cursor-crosshair ring-2 ring-blue-400" : ""}`}
            >
                {/* Prerequisites box - always show if there are prerequisites */}
                {prerequisites.length > 0 && subject && (
                    <div className="w-full px-1 py-0.5 bg-gray-100 border-b border-gray-300 text-[10px] font-medium">
                        Pre: {prerequisites.join(', ')}
                    </div>
                )}
                
                {subject ? (
                    <>
                        <div className="p-0.5 flex flex-1 items-center justify-center text-[12px] overflow-hidden">
                            {subject.nombreMateria}
                        </div>
                        <div className="grid grid-cols-7 text-[12px] border-t-2 border-black">
                            <div
                                className={`border-l-2 border-black ${
                                    !handleOpenModal && "pb-1"
                                }`}
                            >
                                {subject.horasTeoria}
                            </div>
                            <div className="border-l-2 border-black ">
                                {subject.horasPractica}
                            </div>
                            <div className="border-l-2 border-black">
                                {subject.creditos}
                            </div>
                            <div className="col-span-2 border-l-2 border-black">
                                <span data-subject-key>{subject.claveMateria}</span>
                            </div>
                            <div
                                className={`col-span-2 border-l-2 border-black ${handleCaceiBG(
                                    subject.claveCacei
                                )}`}
                            >
                                {subject.claveCacei}
                            </div>
                        </div>
                    </>
                ) : (
                    handleOpenModal && (
                        <Img
                            className={"w-8"}
                            params={{ icon: "addSubject" }}
                        />
                    )
                )}
            </div>
        </div>
    );
}

export default Subjects;
