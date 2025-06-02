import Img from "../atoms/Img";

const ContextMenu = ({ handleOpenModal, mousePosition, handleCloseModal, selectedPosition }) => {

    const handleOnEditClick = (e) =>{
        e.preventDefault();
        const modalInstructions = {
            title:'Editar Materia' ,
            subtitle:'Actualiza la información de la materia',
            icon:'addSubject',
            type:'editSubject',
        }
        +   handleOpenModal(
         modalInstructions,
         selectedPosition.current.area,
         selectedPosition.current.semester,
         selectedPosition.current.index
       );
    }

    const handleOnDeleteClick = (e)=>{
        e.preventDefault();
        const modalInstructions = {
            title:'Borrar Materia' ,
            subtitle:'Elimina la materia',
            icon:'addSubject',
            type:'deleteSubject',
        }
   handleOpenModal(
     modalInstructions,
     selectedPosition.current.area,
     selectedPosition.current.semester,
     selectedPosition.current.index
   );
    }
    

    return (
        <div
            className="absolute z-20 w-auto h-auto bg-[#CAD4DC] shadow-xl p-2 rounded-sm"
            style={{
                top: `${mousePosition.current.y}px`,
                left: `${mousePosition.current.x}px`,
            }}
        >
            <div className="flex flex-col space-y-2 text-white text-center">
                <button onClick={handleCloseModal}>
                    X
                </button>
                <button className="flex space-x-4 hover:bg-[#879CAC] rounded-sm p-2 cursor-pointer transition-colors">
                    {/* Icon */}
                    <div className="size-6 border-2 border-white"/>
                    <p className="w-full">Tipo Materia</p>
                </button>
                <button onClick={(e)=>handleOnEditClick(e)} className="flex space-x-4 hover:bg-[#879CAC] rounded-sm p-2 cursor-pointer transition-colors">
                    {/* Icon */}
                    <Img params={{icon:'edit'}} className={"size-6"}/>
                    <p className="w-full">Editar</p>
                </button>
                <button onClick={(e)=>handleOnDeleteClick(e)} className="flex space-x-4 hover:bg-[#879CAC] rounded-sm p-2 cursor-pointer transition-colors">
                    {/* Icon */}
                    <Img params={{icon:'trash'}} className={"size-6"}/>
                    <p className="w-full">Eliminar</p>
                </button>
            </div>

        </div>
    );
};

export default ContextMenu;
