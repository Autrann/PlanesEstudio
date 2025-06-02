import SelectDropDown from "../../../atoms/SelectDropDown";
import UseFetch from "../../../../hooks/useFetch/UseFetch";
import { useEffect, useState } from "react";

function CreateSubModal({ handleSetSubject,handleCloseModal }) {
    const { data, loading } = UseFetch("get", "getAllSubjects");
    const [subjectsOptions, setSubjectsOptions] = useState(null);

    useEffect(() => {
        const parseSubjects = () => {
            if (!data) return;
            const subjectsOptions = data.map((e, _) => {
                const option = {
                    label: e.nombreMateria,
                    value: JSON.stringify(e),
                };
                return option;
            });
            setSubjectsOptions(subjectsOptions);
        };
        parseSubjects();
    }, [data]);

    const handleSubmit = (e) => {
        e.preventDefault();
        const { subject } = Object.fromEntries(new FormData(e.target));
        const parsedSubject = JSON.parse(subject);
        handleSetSubject(parsedSubject);
    };

    return (
        <form onSubmit={(e) => handleSubmit(e)} className="space-y-2">
            {loading || !subjectsOptions ? (
                <div>Cargando...</div>
            ) : (
                <>
                    <SelectDropDown
                        containerClass={"h-12"}
                        options={subjectsOptions}
                        name={"subject"}
                        placeholder={"Selecciona una materia"}
                    />
                    <div className="flex justify-end space-x-2 font-semibold">
                        <button
                            onClick={handleCloseModal}
                            className="p-2 text-[#AFBEC9]"
                        >
                            Cancelar
                        </button>
                        <button
                            type="submit"
                            className="p-2 bg-[#AFBEC9] rounded-sm text-white"
                        >
                            Seleccionar
                        </button>
                    </div>
                </>
            )}
        </form>
    );
}

export default CreateSubModal;
