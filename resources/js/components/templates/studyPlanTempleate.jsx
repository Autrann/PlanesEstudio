import Img from "../atoms/Img";
import Subjects from "../Molecules/subject";
import { useRef } from "react";

const StudyPlanTempleate = ({
    semesters,
    smallMatrices,
    smallMatrixTitles,
    notesContent,
    serializations = [],
}) => {
    const templateRef = useRef(null);

    return (
        <div id="planEstudios" className="mt-36 w-full" ref={templateRef}>
            <div className="flex flex-col p-2 bg-white text-black items-center relative">
                <div className="w-full flex justify-between items-center mb-4">
                    <Img className={"w-32"} params={{ icon: "UASLP" }} />
                    <div>
                        <h1 className="text-3xl font-bold text-center">
                            Plan de Estudios
                        </h1>
                        <p className="text-center">
                            Ingeniería en Sistemas Inteligentes
                        </p>
                    </div>
                    <Img className={"w-32"} params={{ icon: "UASLP" }} />
                </div>
                <div className="w-3/4">
                    <div className="flex justify-between items-center text-lg font-bold mb-2">
                        <p>Nivel</p>
                        <p>Creditos</p>
                    </div>
                    <hr className="h-1 bg-black w-full" />
                    <hr className="h-1 bg-black w-full" />
                    {semesters.map((semester, index) => {
                        return (
                            <div
                                key={`${index}`}
                                className="flex justify-between w-full"
                            >
                                <h2 className="text-xl font-semibold mr-1">
                                    {index + 1}
                                </h2>
                                <div className="flex-1 grid grid-cols-8 place-content-stretch">
                                    {semester.courses.map((subject, idx) => {
                                        return (
                                            <Subjects
                                                key={idx}
                                                period={index}
                                                index={idx}
                                                subject={subject}
                                                containerClassName={
                                                    "h-24 p-1 col-span-1"
                                                }
                                                serializations={serializations}
                                            />
                                        );
                                    })}
                                    <div className="flex items-center justify-center text-center col-span-1">
                                        <p className="border-2 border-black p-2 w-3/4 ">
                                            {semester.creditos}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        );
                    })}
                </div>

                <div className="mt-10 flex justify-center">
                    <img
                        src="/images/leyenda.png"
                        alt="Leyenda de Clasificaciones"
                        className="w-[90%] h-auto object-contain"
                    />
                </div>

                <div style={{ pageBreakAfter: "always" }} />

                {/* --- Segunda página: tus 3 matrices 3×2 --- */}
                <div className="w-full flex justify-between items-center mb-4">
                    <Img className={"w-32"} params={{ icon: "UASLP" }} />
                    <div>
                        <h1 className="text-3xl font-bold text-center">
                            Plan de Estudios
                        </h1>
                        <p className="text-center">
                            Ingeniería en Sistemas Inteligentes
                        </p>
                    </div>
                    <Img className={"w-32"} params={{ icon: "UASLP" }} />
                </div>

                <hr className="h-1 bg-black w-3/4" />
                <hr className="h-1 bg-black w-3/4" />

                <div className="w-full p-4 bg-white text-black">
                    <h2 className="text-2xl font-bold mb-4 text-center">
                        Materias Optativas
                    </h2>
                    <div className="grid grid-cols-3 gap-6">
                        {smallMatrices.map((matrix, mIdx) => (
                            <div key={mIdx} className="border p-2">
                                <h3 className="font-semibold mb-2">
                                    {smallMatrixTitles?.[mIdx] ??
                                        `Area ${mIdx + 1}`}
                                </h3>
                                <div className="grid grid-cols-2 gap-2">
                                    {matrix.courses
                                        .flat()
                                        .map((subject, flatIdx) => (
                                            <Subjects
                                                key={flatIdx}
                                                index={flatIdx}
                                                subject={subject}
                                                containerClassName="h-24 p-1"
                                                serializations={serializations}
                                            />
                                        ))}
                                </div>
                                <p className="mt-2 text-right font-bold">
                                    Créditos: {matrix.creditos}
                                </p>
                            </div>
                        ))}
                    </div>

                    <div className="mt-6 p-4 border-t border-gray-400">
                        <h2 className="text-xl font-semibold mb-2">
                            Notas / Comentarios
                        </h2>
                        <div
                            className="prose max-w-full"
                            dangerouslySetInnerHTML={{ __html: notesContent }}
                        />
                    </div>
                </div>
            </div>
        </div>
    );
};

export default StudyPlanTempleate;
