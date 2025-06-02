import Input from "../../../atoms/Input";

const EditSubModal = ({ handleCloseModal }) => {
    const handleSubmit = (e) => {
        e.preventDefault();
        const { subject } = Object.fromEntries(new FormData(e.target));
        const parsedSubject = JSON.parse(subject);
        console.log(parsedSubject);
    };

    return (
        <form onSubmit={(e) => handleSubmit(e)} className="space-y-2">
            <div className="grid grid-cols-3 gap-y-2">
                <Input
                    type={"text"}
                    name={"name"}
                    placeholder={"Nombre"}
                    containerClass={"col-span-3"}
                    placeholderPosition={"out"}
                />
                <Input
                    type={"text"}
                    name={"horasteoria"}
                    placeholder={"Horas Teoria"}
                    containerClass={""}
                    placeholderPosition={"out"}
                />
                <Input
                    type={"text"}
                    name={"horaspractica"}
                    placeholder={"Horas Práctica"}
                    containerClass={" col-start-3"}
                    placeholderPosition={"out"}
                />
                <Input
                    type={"text"}
                    name={"creditos"}
                    placeholder={"Creditos"}
                    containerClass={" col-start-1"}
                    placeholderPosition={"out"}
                />
                <Input
                    type={"text"}
                    name={"key"}
                    placeholder={"Clave de materia"}
                    containerClass={"col-start-1"}
                    placeholderPosition={"out"}
                />
                <Input
                    type={"text"}
                    name={"key_cacei"}
                    placeholder={"Clave CACEI"}
                    containerClass={"col-start-3"}
                    placeholderPosition={"out"}
                />
                <Input
                    type={"checkbox"}
                    name={"optativa"}
                    placeholder={"Optativa"}
                    containerClass={"col-start-1"}
                    placeholderPosition={"out"}
                />
            </div>

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
                    Guardar cambios
                </button>
            </div>
        </form>
    );
};

export default EditSubModal;
