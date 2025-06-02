import { useEffect, useRef, useState } from "react";
import SchoolPeriod from "../components/Molecules/schoolPeriod";
import CreateSubModal from "../components/organism/Modal/subModals/createSubModal";
import EditSubModal from "../components/organism/Modal/subModals/editSubModal";
import DeleteSubModal from "../components/organism/Modal/subModals/deleteSubModal";
import Modal from "../components/organism/Modal/Modal";
import Menu from "../components/organism/Menu";
import ContextMenu from "../components/organism/ContextMenu";
import StudyPlanTempleate from "../components/templates/studyPlanTempleate";
import html2pdf from "html2pdf.js";
import useFetch from "../hooks/useFetch/UseFetch";
import RichTextEditor from "../components/organism//Editor/RichTextEditor";
import SerializationLine from "../components/Molecules/SerializationLine";

function Canvas() {
    const carrera = document.getElementById("app")?.dataset?.carrera;
    const [isModalOpen, setIsModalOpen] = useState(null);
    const [page, setPage] = useState(false);
    const [serializations, setSerializations] = useState([]);
    const [serializationInProgress, setSerializationInProgress] = useState(null);
    const [semesters, setSemesters] = useState(
        Array.from({ length: 10 }, () => ({
            creditos: 0,
            courses: Array(7).fill(null),
        }))
    );
    const [smallMatrices, setSmallMatrices] = useState(
        Array.from({ length: 3 }, () => ({
            creditos: 0,
            courses: Array.from({ length: 3 }, () => Array(2).fill(null)),
        }))
    );
    const [menuMode, setMenuMode] = useState(1);
    const selectedPosition = useRef({
        area: "main",
        semester: null,
        index: null,
    });
    const modalInstructions = useRef({
        title: null,
        subtitle: null,
        icon: null,
        type: null,
    });
    const canvasRef = useRef(null);
    const mousePosition = useRef({ x: 0, y: 0 });

    const {
        data: savedState,
        loading: loadingPlan,
        error: errorPlan,
    } = useFetch("get", "getPlanEstudios", true, { pathParams: { carrera } });

    const { execute: savePlan, loading: savingPlan } = useFetch(
        "post",
        "savePlanEstudios",
        false
    );
    const [notesContent, setNotesContent] = useState("");
    

    // Load saved state for main and small matrices
    useEffect(() => {
        if (savedState) {
            if (Array.isArray(savedState)) {
                setSemesters(savedState);
            } else {
                if (Array.isArray(savedState.main)) {
                    setSemesters(savedState.main);
                }
                if (Array.isArray(savedState.smalls)) {
                    setSmallMatrices(savedState.smalls);
                }
                if (typeof savedState.notes === "string") {
                    setNotesContent(savedState.notes);
                }
                if (Array.isArray(savedState.serializations)) {
                    setSerializations(savedState.serializations);
                }
            }
        }
    }, [savedState]);

    useEffect(() => {
        const handleMouse = (e) => {
            mousePosition.current = { x: e.x, y: e.y };
        };
        window.addEventListener("mousemove", handleMouse);
        return () => window.removeEventListener("mousemove", handleMouse);
    }, []);

    useEffect(() => {
        const handleKeyDown = (e) => {
            if (e.key === 'Escape' && serializationInProgress) {
                handleCancelSerialization();
            }
        };

        window.addEventListener('keydown', handleKeyDown);
        return () => window.removeEventListener('keydown', handleKeyDown);
    }, [serializationInProgress]);

    const handleSetPage = () => {
        setPage((prevState) => !prevState);
    };

    const handleCreatePDF = () => {
        const element = document.getElementById("planEstudios");
        element.classList.remove("mt-36");

        html2pdf()
            .from(element)
            .set({
                margin: 0,
                filename: "plan_de_estudios.pdf",
                html2canvas: { scale: 3 },
                precision: 16,
                jsPDF: {
                    unit: "mm",
                    format: [350, 450],
                    orientation: "landscape",
                },
            })
            .outputPdf("blob")
            .then((pdfBlob) => {
                const pdfUrl = URL.createObjectURL(pdfBlob);
                window.open(pdfUrl);
                element.classList.add("mt-36");
            })
            .catch((error) => {
                console.error("Error generando PDF:", error);
                element.classList.add("mt-36");
            });
    };

    const handleSetSubject = (parsedSubject) => {
        const { area, semester, index } = selectedPosition.current;
        if (semester === null || index === null) return;

        if (area === "main") {
            setSemesters((prev) => {
                const updated = [...prev];
                updated[semester].courses = [...updated[semester].courses];
                updated[semester].creditos += parsedSubject.creditos;
                updated[semester].courses[index] = parsedSubject;
                return updated;
            });
        } else if (area.startsWith("small")) {
            const matIndex = parseInt(area.replace("small", ""), 10);
            setSmallMatrices((prev) => {
                const updatedAll = [...prev];
                const matrix = { ...updatedAll[matIndex] };
                matrix.courses = [...matrix.courses];
                matrix.creditos += parsedSubject.creditos;
                matrix.courses[index] = parsedSubject;
                updatedAll[matIndex] = matrix;
                return updatedAll;
            });
        }
        handleCloseModal();
    };

    const handleDeleteSubject = () => {
        const { area, semester, index } = selectedPosition.current;
        if (semester === null || index === null) return;

        if (area === "main") {
            setSemesters((prev) => {
                const updated = [...prev];
                const subject = updated[semester].courses[index];
                if (subject) {
                    updated[semester].creditos -= subject.creditos;
                }
                updated[semester].courses[index] = null;
                return updated;
            });
        } else if (area.startsWith("small")) {
            const matIndex = parseInt(area.replace("small", ""), 10);
            setSmallMatrices((prev) => {
                const updatedAll = [...prev];
                const matrix = { ...updatedAll[matIndex] };
                const subject = matrix.courses[index];
                if (subject) {
                    matrix.creditos -= subject.creditos;
                }
                matrix.courses = [...matrix.courses];
                matrix.courses[index] = null;
                updatedAll[matIndex] = matrix;
                return updatedAll;
            });
        }
        handleCloseModal();
    };

    const handleSetSubjectSmall = (parsedSubject) => {
        const {
            area,
            semester: rowIdx,
            index: colIdx,
        } = selectedPosition.current;
        if (rowIdx === null || colIdx === null) return;

        const matIndex = parseInt(area.replace("small", ""), 10);

        setSmallMatrices((prevAll) => {
            const updatedAll = [...prevAll];

            const matrix = { ...updatedAll[matIndex] };

            const courses2D = matrix.courses.map((row) => [...row]);

            const old = courses2D[rowIdx][colIdx];
            if (old) {
                matrix.creditos -= old.creditos;
            }

            courses2D[rowIdx][colIdx] = parsedSubject;
            matrix.creditos += parsedSubject.creditos;

            matrix.courses = courses2D;
            updatedAll[matIndex] = matrix;

            return updatedAll;
        });

        handleCloseModal();
    };

    const handleDeleteSubjectSmall = () => {
        const {
            area,
            semester: rowIdx,
            index: colIdx,
        } = selectedPosition.current;
        if (rowIdx === null || colIdx === null) return;

        const matIndex = parseInt(area.replace("small", ""), 10);

        setSmallMatrices((prevAll) => {
            const updatedAll = [...prevAll];
            const matrix = { ...updatedAll[matIndex] };

            const courses2D = matrix.courses.map((row) => [...row]);

            const toDelete = courses2D[rowIdx][colIdx];
            if (toDelete) {
                matrix.creditos -= toDelete.creditos;
            }

            courses2D[rowIdx][colIdx] = null;
            matrix.courses = courses2D;

            updatedAll[matIndex] = matrix;
            return updatedAll;
        });

        handleCloseModal();
    };

    const handleOpenModal = (instructions, area, period, index) => {
    console.log(
    "[handleOpenModal] instructions.type =",
    instructions.type,
    "| area =",
    area,
    "| period =",
    period,
    "| index =",
    index
  );
        selectedPosition.current = { area, semester: period, index };
        modalInstructions.current = instructions;
        setIsModalOpen(instructions.type);
    };

    const handleCloseModal = () => {
        selectedPosition.current = {
            area: "main",
            semester: null,
            index: null,
        };
        setIsModalOpen(null);
    };

    const handleChangeMenuMode = (mode) => {
        // If clicking the same mode button, turn it off
        if (mode === menuMode) {
            setMenuMode(1); // Reset to default mode (add subject)
            // If we're in serialization mode, cancel any in-progress serialization
            if (mode === 2) {
                handleCancelSerialization();
            }
        } else {
            setMenuMode(mode);
        }
    };

    const handleSave = async () => {
        try {
            await savePlan("post", {
                data: {
                    carrera,
                    state: {
                        main: semesters,
                        smalls: smallMatrices,
                        notes: notesContent,
                        serializations: serializations,
                    },
                },
            });
            alert("Plan guardado con éxito");
        } catch (err) {
            console.error(err);
            alert("Error al guardar el plan");
        }
    };

    const handleRenderModal = () => {
        switch (modalInstructions.current.type) {
            case "createSubject":
                return (
                    <Modal
                        modalInstructions={modalInstructions}
                        handleCloseModal={handleCloseModal}
                    >
                        <CreateSubModal
                            handleSetSubject={(parsedSubject) => {
                                const { area } = selectedPosition.current;
                                if (area === "main") {
                                    handleSetSubject(parsedSubject);
                                } else {
                                    handleSetSubjectSmall(parsedSubject);
                                }
                            }}
                            handleCloseModal={handleCloseModal}
                        />
                    </Modal>
                );
            case "editSubject":
                return (
                    <Modal
                        modalInstructions={modalInstructions}
                        handleCloseModal={handleCloseModal}
                    >
                        <EditSubModal handleCloseModal={handleCloseModal} />
                    </Modal>
                );
            case "deleteSubject":
                return (
                    <Modal
                        modalInstructions={modalInstructions}
                        handleCloseModal={handleCloseModal}
                    >
                        <DeleteSubModal
       handleDeleteSubject={handleDeleteSubject}
       handleCloseModal={handleCloseModal}
     />
                    </Modal>
                );
            case "secundarySubject":
                return (
                    <ContextMenu
                        handleOpenModal={handleOpenModal}
                        mousePosition={mousePosition}
                        handleCloseModal={handleCloseModal}
                        selectedPosition={selectedPosition}
                    />
                );
            default:
                return null;
        }
    };

    const pizzaraStyle = {
        backgroundColor: "white",
        backgroundImage: `linear-gradient(to right, gray 1px, transparent 1px), \
           linear-gradient(to bottom, gray 1px, transparent 1px)`,
        backgroundSize: "20px 20px",
    };

    const [smallMatrixTitles, setSmallMatrixTitles] = useState(
        Array.from({ length: 3 }, (_, i) => `Area ${i + 1}`)
    );

    const handleSmallMatrixTitleChange = (idx, value) => {
        setSmallMatrixTitles((prev) => {
            const updated = [...prev];
            updated[idx] = value;
            return updated;
        });
    };

    
    const handleSubjectClick = (period, index) => {
        if (menuMode !== 2) return; 

        if (!serializationInProgress) {
            
            setSerializationInProgress({ from: { period, index } });
        } else {
            
            const newSerialization = {
                from: serializationInProgress.from,
                to: { period, index }
            };
            setSerializations(prev => [...prev, newSerialization]);
            setSerializationInProgress(null);
        }
    };

    
    const handleCancelSerialization = () => {
        setSerializationInProgress(null);
    };

    
    const renderSerializations = () => {
        return (
            <>
                {serializations.map((serial, idx) => (
                    <SerializationLine
                        key={idx}
                        from={serial.from}
                        to={serial.to}
                        canvasRef={canvasRef}
                    />
                ))}
                {serializationInProgress && (
                    <SerializationLine
                        from={serializationInProgress.from}
                        to={mousePosition.current}
                        isTemp={true}
                        canvasRef={canvasRef}
                    />
                )}
            </>
        );
    };

    return (
        <div className="relative h-full w-full flex flex-col overflow-auto">
            {isModalOpen && handleRenderModal()}

            <div className="fixed z-10 w-full">
                <div className="bg-[#CAD4DC] font-bold p-2 flex justify-between items-center">
                    <p className="text-4xl text-white">
                        PLAN DE ESTUDIO -{" "}
                        <span className="text-[#879CAC] uppercase">
                            {carrera}
                        </span>
                    </p>
                    <button
                        onClick={handleSave}
                        className="px-4 py-2 bg-green-500 text-white rounded-lg"
                        disabled={savingPlan}
                    >
                        {savingPlan ? "Guardando..." : "Guardar"}
                    </button>
                </div>
                <Menu
                    menuMode={menuMode}
                    handleChangeMenuMode={handleChangeMenuMode}
                    handleSetPage={handleSetPage}
                    page={page}
                    handleCreatePDF={handleCreatePDF}
                />
            </div>

            {page ? (
                <StudyPlanTempleate
                    semesters={semesters}
                    smallMatrices={smallMatrices}
                    smallMatrixTitles={smallMatrixTitles}
                    notesContent={notesContent}
                    serializations={serializations}
                    id="planEstudios"
                />
            ) : (
                <div
                    className="mt-36 grow w-full p-4 relative"
                    ref={canvasRef}
                    style={pizzaraStyle}
                    id="planEstudios"
                >
                    {loadingPlan && <p>Cargando plan...</p>}
                    {!loadingPlan && errorPlan && <p>Error cargando plan</p>}
                    {Array.isArray(semesters) ? (
                        <div className="flex flex-col">
                            <div className="flex flex-col space-y-4">
                                {semesters.map((semester, idx) => (
                                    <SchoolPeriod
                                        key={`main-${idx}`}
                                        period={idx}
                                        semester={semester}
                                        handleOpenModal={(instr, p, i) =>
                                            handleOpenModal(instr, "main", p, i)
                                        }
                                        menuMode={menuMode}
                                        onSubjectClick={handleSubjectClick}
                                        serializationInProgress={serializationInProgress}
                                        serializations={serializations}
                                    />
                                ))}
                            </div>
                            <div className="mt-8 grid grid-cols-3 gap-4">
                                {smallMatrices.map((matrix, mIdx) => (
                                    <div key={`small-${mIdx}`}>
                                        <input
                                            type="text"
                                            className="font-bold mb-2 w-full px-2 py-1 border rounded"
                                            value={smallMatrixTitles[mIdx]}
                                            onChange={(e) =>
                                                handleSmallMatrixTitleChange(
                                                    mIdx,
                                                    e.target.value
                                                )
                                            }
                                        />

                                        <div
                                            className="p-2"
                                            style={pizzaraStyle}
                                        >
                                            {matrix.courses.map(
                                                (rowArray, rowIdx) => (
                                                    <SchoolPeriod
                                                        key={`small-${mIdx}-row${rowIdx}`}
                                                        period={rowIdx}
                                                        semester={{
                                                            creditos:
                                                                matrix.creditos,
                                                            courses: rowArray,
                                                        }}
                                                        handleOpenModal={(
                                                            instr,
                                                            p,
                                                            i
                                                        ) =>
                                                            handleOpenModal(
                                                                instr,
                                                                `small${mIdx}`,
                                                                p,
                                                                i
                                                            )
                                                        }
                                                        serializations={serializations}
                                                    />
                                                )
                                            )}
                                        </div>
                                    </div>
                                ))}
                            </div>
                            <div className="mt-8 px-2">
                                <h2 className="text-xl font-semibold mb-2">
                                    Notas / Comentarios:
                                </h2>
                                <RichTextEditor
                                    value={notesContent} 
                                    onChange={(html) => setNotesContent(html)}
                                />
                            </div>
                        </div>
                    ) : (
                        <p>No hay datos del plan para mostrar</p>
                    )}
                </div>
            )}
        </div>
    );
}

export default Canvas;
