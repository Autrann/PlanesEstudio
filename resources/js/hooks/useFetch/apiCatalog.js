// apiCatalog.js

const apiCatalog = {
    getAllSubjects: {
      url: '/materiasGet', //Endpoint para opts es materiasOptativasGet
    },
    getPlanEstudios: {
      url: '/plan-estudios/:carrera',
    },
    savePlanEstudios: {
      url: '/plan-estudios',
    },
  };
  
  export default apiCatalog;