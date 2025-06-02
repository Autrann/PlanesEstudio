import Subjects from "./subject";

function SchoolPeriod({
    period,
    semester,
    handleOpenModal,
    menuMode,
    onSubjectClick,
    serializationInProgress,
    serializations = []
}) {
    const parsePeriod = [
        "I",
        "II",
        "III",
        "IV",
        "V",
        "VI",
        "VII",
        "VIII",
        "IX",
        "X",
    ];

    return (
        <div className="flex w-full">
            <div className="flex items-center justify-center w-8">
                <p className="text-xl font-bold text-black">
                    {parsePeriod[period]}
                </p>
            </div>
            <div className="flex border-2 border-[#A29797] w-full">
                {semester.courses.map((subject, index) => (
                    <Subjects
                        key={index}
                        index={index}
                        period={period}
                        subject={subject}
                        handleOpenModal={handleOpenModal}
                        containerClassName={"sm:h-24 xl:h-32 p-3"}
                        menuMode={menuMode}
                        onSubjectClick={onSubjectClick}
                        isSerializationSource={
                            serializationInProgress?.from?.period === period &&
                            serializationInProgress?.from?.index === index
                        }
                        serializations={serializations}
                    />
                ))}
            </div>
            <div className="flex items-center justify-center w-8 ml-2">
                <p className="text-xl font-bold text-black">
                    {semester.creditos}
                </p>
            </div>
        </div>
    );
}

export default SchoolPeriod;
