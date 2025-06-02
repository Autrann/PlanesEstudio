const DeleteSubModal = ({handleCloseModal,handleDeleteSubject}) =>{
    const handleSubmit = (e) => {
        e.preventDefault();
        handleDeleteSubject();
    };

    return(
    <form onSubmit={(e) => handleSubmit(e)} className="space-y-2">
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
                    Eliminar
                </button>
            </div>
        </form>
    )


}

export default DeleteSubModal;