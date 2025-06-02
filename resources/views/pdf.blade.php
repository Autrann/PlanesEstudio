<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Canvas Avanzado con Exportación a PDF</title>
  <!-- Tailwind CSS y Material Icons -->
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <!-- CDNs para html2canvas y jsPDF -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js" integrity="sha512-BNa5ebnM+Jj0SkWkxt3yFpy0YVMGLW6/fv1se0sJcLZgOeSEYSkavE/YmWvYpF4D9xgNdT0osx3pDkLOuQ7i6Q==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js" integrity="sha512-AywyzEy65j6TWUoQpmuzHbdKxI1O+twWzv7SR42V7+14AdFv+BNs0xSEaxJ+c8Q1NKKYtM8YJz7fnBWm8rkP6Q==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <style>
    /* ===================== Canvas y Elementos ===================== */
    #canvas {
      position: relative;
      background-color: #e5e7eb;
      width: 100%;
      height: 100vh;
      overflow: hidden;
    }
    /* Modo conexión: cursor crosshair en cuadros y materias */
    #canvas.connection-mode .big-rectangle,
    #canvas.connection-mode .materia {
      cursor: crosshair !important;
    }
    #svgConnections {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      pointer-events: none;
      z-index: 50;
    }
    /* ===================== Materias ===================== */
    .materia-container {
      position: absolute;
      width: 100px;
      height: 60px;
      border: 2px solid #555;
      background-color: #fff;
    }
    .materia-top {
      width: 100%;
      height: 60%;
      border-bottom: 2px solid #555;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 0.65rem;
      font-weight: bold;
      overflow: hidden;
      text-align: center;
      padding: 2px;
    }
    .materia-bottom {
      width: 100%;
      height: 40%;
      display: flex;
      overflow: hidden;
    }
    .materia-col {
      flex: 1;
      display: flex;
      align-items: center;
      justify-content: center;
      border-right: 1px solid #555;
      font-size: 0.55rem;
      overflow: hidden;
    }
    .materia-col:last-child {
      border-right: none;
    }
    .materia {
      z-index: 100;
    }
    /* Efecto "cortado" para materias al arrastrarlas */
    .dragging-materia {
      opacity: 0.7;
      transform: scale(1.05);
      box-shadow: 0 4px 8px rgba(0,0,0,0.3);
      transition: transform 0.2s ease, opacity 0.2s ease;
    }
    /* Evitar selección de texto y cursor de arrastre */
    .big-rectangle, .materia {
      user-select: none;
      cursor: grab;
    }
    .dragging {
      cursor: grabbing !important;
    }
    /* ===================== Nuevo Elemento: Cuadro de Texto ===================== */
    .text-box-container {
      position: absolute;
      width: 200px;
      height: 100px;
      border: 1px dashed #aaa;
      background-color: transparent;
      resize: both;
      overflow: auto;
    }
    /* Clase para quitar el diseño (limpiar) */
    .clean-text-box {
      border: none;
      background-color: transparent;
    }
    .text-box-header {
      cursor: grab;
      background: #f0f0f0;
      padding: 4px;
      text-align: center;
      user-select: none;
      font-size: 0.9rem;
      color: #374151;
    }
    .text-box-content {
      height: calc(100% - 24px);
      padding: 4px;
      overflow: auto;
      user-select: text;
      background: transparent;
    }
    /* ===================== Modales ===================== */
    #colorModal, #materiaModal {
      z-index: 1000;
    }
    /* ===================== Sidebar ===================== */
    #sidebar {
      position: fixed;
      top: 20px;
      left: 20px;
      background: #fff;
      border-radius: 8px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.15);
      padding: 1rem;
      z-index: 1100;
      width: 240px;
      transition: transform 0.3s ease, opacity 0.3s ease;
      user-select: none;
    }
    #sidebarHeader {
      cursor: move;
      font-weight: 500;
      margin-bottom: 1rem;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 0.5rem;
      color: #374151;
      font-size: 1.125rem;
      user-select: none;
    }
    #sidebar button {
      width: 100%;
      background: none;
      border: none;
      outline: none;
      cursor: pointer;
      padding: 0.5rem;
      border-radius: 4px;
      transition: background 0.2s ease;
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }
    #sidebar button:hover {
      background: rgba(0, 0, 0, 0.08);
    }
    #sidebar button .material-icons {
      font-size: 24px;
      color: #4B5563;
    }
    #sidebar button span.label {
      font-size: 0.9rem;
      color: #4B5563;
    }
    /* Nuevo botón para cuadro de texto */
    #addTextBoxBtn {
      width: 100%;
      background: none;
      border: none;
      outline: none;
      cursor: pointer;
      padding: 0.5rem;
      border-radius: 4px;
      transition: background 0.2s ease;
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }
    #addTextBoxBtn:hover {
      background: rgba(0, 0, 0, 0.08);
    }
    /* Estado activo para el botón de conectar */
    #connectObjects.active {
      background: #4F46E5;
      color: #fff;
    }
    #connectObjects.active .material-icons,
    #connectObjects.active span.label {
      color: #fff;
    }
    #ungroupBtn {
      width: 100%;
      background: none;
      border: none;
      outline: none;
      cursor: pointer;
      padding: 0.5rem;
      border-radius: 4px;
      transition: background 0.2s ease;
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }
    #ungroupBtn:hover {
      background: rgba(0, 0, 0, 0.08);
    }
    #sidebar.hidden {
      transform: translateX(-300px);
      opacity: 0;
    }
    /* ===================== Menú Contextual (Globito) ===================== */
    #contextBubble {
      position: absolute;
      display: flex;
      gap: 8px;
      background: #fff;
      padding: 4px;
      border-radius: 24px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.2);
      z-index: 1200;
      transition: opacity 0.3s ease, transform 0.3s ease;
    }
    #contextBubble.hidden {
      opacity: 0;
      pointer-events: none;
      transform: scale(0.8);
    }
    #contextBubble button {
      background: none;
      border: none;
      cursor: pointer;
      padding: 6px;
      border-radius: 50%;
      transition: background 0.2s ease;
    }
    #contextBubble button:hover {
      background: rgba(0,0,0,0.1);
    }
    #contextBubble button .material-icons {
      font-size: 20px;
      color: #374151;
    }
    /* Menú Contextual para formateo de texto */
    #textFormatBubble {
      position: absolute;
      display: flex;
      gap: 8px;
      background: #fff;
      padding: 4px;
      border-radius: 24px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.2);
      z-index: 1200;
      transition: opacity 0.3s ease, transform 0.3s ease;
    }
    #textFormatBubble.hidden {
      opacity: 0;
      pointer-events: none;
      transform: scale(0.8);
    }
    #textFormatBubble button {
      background: none;
      border: none;
      cursor: pointer;
      padding: 6px;
      border-radius: 50%;
      transition: background 0.2s ease;
    }
    #textFormatBubble button:hover {
      background: rgba(0,0,0,0.1);
    }
    #textFormatBubble button .material-icons {
      font-size: 20px;
      color: #374151;
    }
    /* ===================== Guías de Alineación (solo para cuadros) ===================== */
    .guideline {
      position: absolute;
      background: rgba(0,0,0,0.5);
      z-index: 1050;
    }
  </style>
</head>
<body class="bg-gray-100">
  <!-- Sidebar -->
  <div id="sidebar">
    <div id="sidebarHeader">
      <span class="material-icons">drag_handle</span>
      <span>Herramientas</span>
    </div>
    <div class="flex flex-col space-y-4">
      <button id="openModal" title="Agregar Rectángulo">
        <span class="material-icons">crop_square</span>
        <span class="label">Rectángulo</span>
      </button>
      <button id="addMateriaBtn" title="Agregar Materia">
        <span class="material-icons">subject</span>
        <span class="label">Materia</span>
      </button>
      <button id="addTextBoxBtn" title="Agregar Texto">
        <span class="material-icons">text_fields</span>
        <span class="label">Texto</span>
      </button>
      <button id="connectObjects" title="Conectar Materias">
        <span class="material-icons">call_merge</span>
        <span class="label">Conectar</span>
      </button>
      <button id="ungroupBtn" title="Desagrupar Materia">
        <span class="material-icons">call_split</span>
        <span class="label">Desagrupar</span>
      </button>
      <button id="exportPdfBtn" title="Exportar a PDF">
        <span class="material-icons">picture_as_pdf</span>
        <span class="label">Exportar PDF</span>
      </button>
    </div>
  </div>

  <!-- Canvas (con tabindex para capturar teclas, como DEL) -->
  <div id="canvas" tabindex="0"></div>

  <!-- SVG para conexiones -->
  <svg id="svgConnections"></svg>

  <!-- Menú Contextual (Globito) para cuadros principales (no para materias ni texto) -->
  <div id="contextBubble" class="hidden">
    <button id="bubbleColor" title="Cambiar color">
      <span class="material-icons">palette</span>
    </button>
    <button id="bubbleDelete" title="Eliminar">
      <span class="material-icons">delete</span>
    </button>
  </div>

  <!-- Menú Contextual para formateo de texto en cuadro de texto -->
  <div id="textFormatBubble" class="hidden">
    <button id="formatBold" title="Negrita">
      <span class="material-icons">format_bold</span>
    </button>
    <button id="formatItalic" title="Itálica">
      <span class="material-icons">format_italic</span>
    </button>
    <button id="formatUnderline" title="Subrayado">
      <span class="material-icons">format_underlined</span>
    </button>
    <button id="increaseFont" title="Aumentar tamaño">
      <span class="material-icons">arrow_upward</span>
    </button>
    <button id="decreaseFont" title="Disminuir tamaño">
      <span class="material-icons">arrow_downward</span>
    </button>
    <button id="toggleFontFamily" title="Cambiar fuente">
      <span class="material-icons">font_download</span>
    </button>
    <button id="toggleTextBoxStyle" title="Limpiar diseño">
      <span class="material-icons">format_clear</span>
    </button>
  </div>

  <!-- Modal para seleccionar color (cuadros principales) -->
  <div id="colorModal" class="fixed inset-0 flex items-center justify-center bg-gray-800 bg-opacity-50 hidden">
    <div class="bg-white p-6 rounded-lg shadow-xl max-w-sm w-full">
      <h2 id="modalTitle" class="text-2xl font-bold mb-4 text-center">Selecciona el color</h2>
      <div class="flex justify-center mb-4">
        <input id="colorPicker" type="color" class="w-16 h-16 border-2 border-gray-300 rounded">
      </div>
      <div class="flex justify-end space-x-2">
        <button id="cancelModal" class="bg-gray-400 text-white px-4 py-2 rounded">Cancelar</button>
        <button id="confirmModal" class="bg-blue-500 text-white px-4 py-2 rounded">Confirmar</button>
      </div>
    </div>
  </div>

  <!-- Modal para seleccionar una materia -->
  <div id="materiaModal" class="fixed inset-0 flex items-center justify-center bg-gray-800 bg-opacity-50 hidden">
    <div class="bg-white p-6 rounded-lg shadow-xl max-w-sm w-full">
      <h2 class="text-2xl font-bold mb-4 text-center">Agregar Materia</h2>
      <label for="materiaSelect" class="block mb-2">Selecciona la materia:</label>
      <select id="materiaSelect" class="w-full border border-gray-300 rounded mb-4 p-2">
        <option value="">-- Selecciona --</option>
        @foreach($materias as $mat)
          <option value="{{ $mat->id }}">{{ $mat->nombreMateria }}</option>
        @endforeach
      </select>
      <div class="flex justify-end space-x-2">
        <button id="cancelMateria" class="bg-gray-400 text-white px-4 py-2 rounded">Cancelar</button>
        <button id="confirmMateria" class="bg-green-500 text-white px-4 py-2 rounded">Generar</button>
      </div>
    </div>
  </div>

  <script>
    /* ========================================
       Variables y Referencias
       ======================================== */
    const materiasData = @json($materias);
    const canvas = document.getElementById('canvas');
    const svgConnections = document.getElementById('svgConnections');
    const openModalBtn = document.getElementById('openModal');
    const addMateriaBtn = document.getElementById('addMateriaBtn');
    const addTextBoxBtn = document.getElementById('addTextBoxBtn');
    const connectBtn = document.getElementById('connectObjects');
    const ungroupBtn = document.getElementById('ungroupBtn');
    const exportPdfBtn = document.getElementById('exportPdfBtn');
    const colorModal = document.getElementById('colorModal');
    const modalTitle = document.getElementById('modalTitle');
    const colorPicker = document.getElementById('colorPicker');
    const cancelModal = document.getElementById('cancelModal');
    const confirmModal = document.getElementById('confirmModal');
    const materiaModal = document.getElementById('materiaModal');
    const materiaSelect = document.getElementById('materiaSelect');
    const cancelMateriaBtn = document.getElementById('cancelMateria');
    const confirmMateriaBtn = document.getElementById('confirmMateria');
    const sidebar = document.getElementById('sidebar');
    const contextBubble = document.getElementById('contextBubble');
    const bubbleColor = document.getElementById('bubbleColor');
    const bubbleDelete = document.getElementById('bubbleDelete');

    let selectedElement = null;  // Elemento seleccionado (cuadro, materia o texto)
    let modalMode = 'add';
    let currentMateria = null;   // Para arrastre de elementos
    let offsetX = 0, offsetY = 0;

    // Modo conexión
    let connectionMode = false;
    let startNode = null;
    let connections = [];
    const svgNS = "http://www.w3.org/2000/svg";

    // Umbral para snapping: 5px para materias y texto, 10px para cuadros
    const snapThreshold = 10;
    let activeGuidelines = [];

    /* ========================================
       Funciones de Snapping y Guías
       ======================================== */
    function snapElementPosition(element, proposedLeft, proposedTop) {
      const canvasRect = canvas.getBoundingClientRect();
      if (element.classList.contains('materia') || element.classList.contains('text-box-container')) {
        const snappedLeft = Math.round(proposedLeft / 5) * 5;
        const snappedTop = Math.round(proposedTop / 5) * 5;
        return { left: snappedLeft, top: snappedTop, guidelines: [] };
      }
      let snappedLeft = proposedLeft;
      let snappedTop = proposedTop;
      const elementWidth = element.offsetWidth;
      const elementHeight = element.offsetHeight;
      let guidelines = [];
      let selector = '.big-rectangle';
      const others = document.querySelectorAll(selector);
      others.forEach(other => {
        if (other === element) return;
        const otherRect = other.getBoundingClientRect();
        const otherLeft = otherRect.left - canvasRect.left;
        const otherTop = otherRect.top - canvasRect.top;
        const otherRight = otherLeft + other.offsetWidth;
        const otherBottom = otherTop + other.offsetHeight;
        const currentLeft = proposedLeft;
        const currentTop = proposedTop;
        const currentRight = proposedLeft + elementWidth;
        const currentBottom = proposedTop + elementHeight;
        if (Math.abs(currentLeft - otherLeft) < snapThreshold) {
          snappedLeft = otherLeft;
          guidelines.push({ type: 'vertical', position: otherLeft });
        }
        if (Math.abs(currentRight - otherRight) < snapThreshold) {
          snappedLeft = otherRight - elementWidth;
          guidelines.push({ type: 'vertical', position: otherRight });
        }
        if (Math.abs(currentTop - otherTop) < snapThreshold) {
          snappedTop = otherTop;
          guidelines.push({ type: 'horizontal', position: otherTop });
        }
        if (Math.abs(currentBottom - otherBottom) < snapThreshold) {
          snappedTop = otherBottom - elementHeight;
          guidelines.push({ type: 'horizontal', position: otherBottom });
        }
      });
      return { left: snappedLeft, top: snappedTop, guidelines };
    }
    function displayGuidelines(guidelines) {
      activeGuidelines.forEach(el => el.remove());
      activeGuidelines = [];
      guidelines.forEach(g => {
        let line = document.createElement('div');
        line.className = 'guideline';
        if (g.type === 'vertical') {
          line.style.width = '1px';
          line.style.height = canvas.offsetHeight + 'px';
          line.style.left = g.position + 'px';
          line.style.top = '0px';
        } else if (g.type === 'horizontal') {
          line.style.height = '1px';
          line.style.width = canvas.offsetWidth + 'px';
          line.style.top = g.position + 'px';
          line.style.left = '0px';
        }
        canvas.appendChild(line);
        activeGuidelines.push(line);
      });
    }
    function clearGuidelines() {
      activeGuidelines.forEach(el => el.remove());
      activeGuidelines = [];
    }

    /* ========================================
       Funciones de Modales
       ======================================== */
    function showColorModal() { colorModal.classList.remove('hidden'); }
    function hideColorModal() { colorModal.classList.add('hidden'); }
    cancelModal.addEventListener('click', hideColorModal);
    function showMateriaModal() { materiaModal.classList.remove('hidden'); }
    function hideMateriaModal() { materiaModal.classList.add('hidden'); }
    cancelMateriaBtn.addEventListener('click', hideMateriaModal);

    /* ========================================
       Botones del Sidebar
       ======================================== */
    openModalBtn.addEventListener('click', () => {
      modalMode = 'add';
      modalTitle.textContent = 'Color del Rectángulo';
      colorPicker.value = '#ffffff';
      showColorModal();
    });
    addMateriaBtn.addEventListener('click', () => { showMateriaModal(); });
    addTextBoxBtn.addEventListener('click', () => { createTextBox(); });
    connectBtn.addEventListener('click', () => {
      connectionMode = !connectionMode;
      if (connectionMode) {
        canvas.classList.add("connection-mode");
        canvas.style.cursor = "crosshair";
        connectBtn.classList.add("active");
        connectBtn.innerHTML = `<span class="material-icons">call_merge</span><span class="label">Conectando</span>`;
      } else {
        canvas.classList.remove("connection-mode");
        canvas.style.cursor = "default";
        connectBtn.classList.remove("active");
        connectBtn.innerHTML = `<span class="material-icons">call_merge</span><span class="label">Conectar</span>`;
        if (startNode) {
          startNode.classList.remove("ring-2", "ring-red-500");
          startNode = null;
        }
      }
    });
    ungroupBtn.addEventListener('click', () => {
      if (selectedElement && selectedElement.classList.contains('materia') && selectedElement.parentElement !== canvas) {
        const canvasRect = canvas.getBoundingClientRect();
        const selRect = selectedElement.getBoundingClientRect();
        selectedElement.style.left = (selRect.left - canvasRect.left) + 'px';
        selectedElement.style.top = (selRect.top - canvasRect.top) + 'px';
        canvas.appendChild(selectedElement);
      }
    });
    exportPdfBtn.addEventListener('click', exportCanvasToPDF);

    /* ========================================
       Función para exportar el canvas a PDF
       ======================================== */
    function exportCanvasToPDF() {
      html2canvas(canvas).then(canvasCapture => {
        const imgData = canvasCapture.toDataURL('image/png');
        const pdf = new jspdf.jsPDF('p', 'pt', 'a4');
        const pdfWidth = pdf.internal.pageSize.getWidth();
        const pdfHeight = pdf.internal.pageSize.getHeight();
        // Ajusta la imagen al tamaño de la hoja
        pdf.addImage(imgData, 'PNG', 0, 0, pdfWidth, pdfHeight);
        pdf.save('canvas.pdf');
      });
    }

    /* ========================================
       Creación de Elementos: Cuadro, Materia y Cuadro de Texto
       ======================================== */
    function createRectangle(bgColor) {
      const rect = document.createElement('div');
      rect.className = 'big-rectangle absolute border-2 border-black p-2 box-border';
      rect.style.top = '50px';
      rect.style.left = '50px';
      rect.style.width = '150px';
      rect.style.height = '100px';
      rect.style.backgroundColor = bgColor;
      rect.dataset.bgcolor = bgColor;

      const title = document.createElement('h3');
      title.contentEditable = true;
      title.innerText = 'Editar Título';
      title.className = 'w-full text-center cursor-text m-0';
      rect.appendChild(title);

      const handle = document.createElement('div');
      handle.className = 'resize-handle absolute bg-gray-800';
      handle.style.width = '10px';
      handle.style.height = '10px';
      handle.style.right = '0';
      handle.style.bottom = '0';
      handle.style.cursor = 'se-resize';
      rect.appendChild(handle);

      canvas.appendChild(rect);

      rect.addEventListener('click', (e) => {
        if (e.target === handle) return;
        if (!connectionMode) {
          if (selectedElement && selectedElement !== rect) {
            selectedElement.classList.remove('ring-2', 'ring-blue-500');
          }
          selectedElement = rect;
          rect.classList.add('ring-2', 'ring-blue-500');
        }
        e.stopPropagation();
      });
      rect.addEventListener('contextmenu', (e) => {
        e.preventDefault();
        showContextBubble(e, rect);
      });

      let isDragging = false;
      let startX, startY;
      rect.addEventListener('mousedown', (e) => {
        if (e.target === handle) return;
        isDragging = true;
        rect.classList.add('dragging');
        startX = e.clientX - rect.offsetLeft;
        startY = e.clientY - rect.offsetTop;
      });
      document.addEventListener('mousemove', (e) => {
        if (isDragging) {
          let newLeft = e.clientX - startX;
          let newTop = e.clientY - startY;
          if (newLeft < 0) newLeft = 0;
          if (newTop < 0) newTop = 0;
          if (newLeft + rect.offsetWidth > canvas.clientWidth)
            newLeft = canvas.clientWidth - rect.offsetWidth;
          if (newTop + rect.offsetHeight > canvas.clientHeight)
            newTop = canvas.clientHeight - rect.offsetHeight;
          const snapResult = snapElementPosition(rect, newLeft, newTop);
          rect.style.left = snapResult.left + 'px';
          rect.style.top = snapResult.top + 'px';
          displayGuidelines(snapResult.guidelines);
        }
      });
      document.addEventListener('mouseup', () => { 
        isDragging = false;
        rect.classList.remove('dragging');
        clearGuidelines();
      });

      let isResizing = false;
      let initWidth, initHeight, initX, initY;
      handle.addEventListener('mousedown', (e) => {
        isResizing = true;
        initWidth = rect.offsetWidth;
        initHeight = rect.offsetHeight;
        initX = e.clientX;
        initY = e.clientY;
        e.stopPropagation();
        e.preventDefault();
      });
      document.addEventListener('mousemove', (e) => {
        if (isResizing) {
          let newWidth = initWidth + (e.clientX - initX);
          let newHeight = initHeight + (e.clientY - initY);
          const rectLeft = rect.offsetLeft;
          const rectTop = rect.offsetTop;
          if (rectLeft + newWidth > canvas.clientWidth) {
            newWidth = canvas.clientWidth - rectLeft;
          }
          if (rectTop + newHeight > canvas.clientHeight) {
            newHeight = canvas.clientHeight - rectTop;
          }
          let minWidth = 150, minHeight = 100;
          const subObjects = rect.querySelectorAll('.materia');
          subObjects.forEach(sub => {
            const subRight = sub.offsetLeft + sub.offsetWidth;
            const subBottom = sub.offsetTop + sub.offsetHeight;
            if (subRight > minWidth) minWidth = subRight;
            if (subBottom > minHeight) minHeight = subBottom;
          });
          newWidth = Math.max(newWidth, minWidth);
          newHeight = Math.max(newHeight, minHeight);
          rect.style.width = newWidth + 'px';
          rect.style.height = newHeight + 'px';
        }
      });
      document.addEventListener('mouseup', () => { isResizing = false; });
    }

    function createMateria(matData) {
      const mat = document.createElement('div');
      mat.className = 'materia materia-container';
      mat.style.top = '50px';
      mat.style.left = '50px';

      const topDiv = document.createElement('div');
      topDiv.className = 'materia-top';
      topDiv.textContent = matData.nombreMateria;
      mat.appendChild(topDiv);

      const bottomDiv = document.createElement('div');
      bottomDiv.className = 'materia-bottom';

      const col2 = document.createElement('div');
      col2.className = 'materia-col';
      col2.textContent = matData.horasTeoria;
      bottomDiv.appendChild(col2);

      const col3 = document.createElement('div');
      col3.className = 'materia-col';
      col3.textContent = matData.horasPractica;
      bottomDiv.appendChild(col3);

      const col4 = document.createElement('div');
      col4.className = 'materia-col';
      col4.textContent = matData.creditos;
      bottomDiv.appendChild(col4);

      const col5 = document.createElement('div');
      col5.className = 'materia-col';
      col5.textContent = matData.claveMateria;
      bottomDiv.appendChild(col5);

      const col6 = document.createElement('div');
      col6.className = 'materia-col';
      col6.textContent = matData.claveCacei;
      bottomDiv.appendChild(col6);

      mat.appendChild(bottomDiv);
      canvas.appendChild(mat);

      mat.addEventListener('click', (e) => {
        if (connectionMode) {
          e.stopPropagation();
          if (!startNode) {
            startNode = mat;
            mat.classList.add('ring-2', 'ring-red-500');
          } else if (startNode === mat) {
            startNode.classList.remove('ring-2', 'ring-red-500');
            startNode = null;
          } else {
            drawConnection(startNode, mat);
            startNode.classList.remove('ring-2', 'ring-red-500');
            startNode = null;
          }
        } else {
          if (selectedElement && selectedElement !== mat) {
            selectedElement.classList.remove('ring-2', 'ring-blue-500');
          }
          selectedElement = mat;
          mat.classList.add('ring-2', 'ring-blue-500');
          e.stopPropagation();
        }
      });
      mat.addEventListener('mousedown', (e) => {
        e.stopPropagation();
        currentMateria = mat;
        mat.classList.add('dragging-materia');
        mat.style.cursor = "grabbing";
        const parentRect = mat.parentElement.getBoundingClientRect();
        offsetX = e.clientX - mat.getBoundingClientRect().left;
        offsetY = e.clientY - mat.getBoundingClientRect().top;
      });
    }

    function createTextBox() {
      const textBox = document.createElement('div');
      textBox.className = 'text-box-container absolute';
      textBox.style.left = '50px';
      textBox.style.top = '50px';
      textBox.style.width = '200px';
      textBox.style.height = '100px';
      textBox.style.border = '1px dashed #aaa';
      textBox.style.backgroundColor = 'transparent';
      
      // Header para arrastrar
      const header = document.createElement('div');
      header.className = 'text-box-header';
      header.innerHTML = '<span class="material-icons" style="font-size:16px;">drag_handle</span>';
      header.title = 'Arrastrar';
      textBox.appendChild(header);
      
      // Área editable
      const content = document.createElement('div');
      content.className = 'text-box-content';
      content.contentEditable = true;
      content.style.outline = 'none';
      content.style.padding = '4px';
      content.style.height = 'calc(100% - 24px)';
      content.style.overflow = 'auto';
      content.style.userSelect = 'text';
      content.style.background = 'transparent';
      content.innerHTML = 'Escribe aquí...';
      textBox.appendChild(content);
      
      // Resize handle
      const resizeHandle = document.createElement('div');
      resizeHandle.className = 'resize-handle-text';
      resizeHandle.style.width = '10px';
      resizeHandle.style.height = '10px';
      resizeHandle.style.position = 'absolute';
      resizeHandle.style.right = '0';
      resizeHandle.style.bottom = '0';
      resizeHandle.style.cursor = 'se-resize';
      textBox.appendChild(resizeHandle);
      
      canvas.appendChild(textBox);
      
      // Arrastre con header
      let isDragging = false;
      let startX, startY;
      header.addEventListener('mousedown', (e) => {
         isDragging = true;
         header.style.cursor = 'grabbing';
         startX = e.clientX - textBox.offsetLeft;
         startY = e.clientY - textBox.offsetTop;
      });
      document.addEventListener('mousemove', (e) => {
         if (isDragging) {
           let newLeft = e.clientX - startX;
           let newTop = e.clientY - startY;
           if(newLeft < 0) newLeft = 0;
           if(newTop < 0) newTop = 0;
           textBox.style.left = newLeft + 'px';
           textBox.style.top = newTop + 'px';
         }
      });
      document.addEventListener('mouseup', () => {
         if(isDragging) {
           isDragging = false;
           header.style.cursor = 'grab';
         }
      });
      
      // Redimensionar con resize handle
      let isResizing = false, initWidth, initHeight, initX, initY;
      resizeHandle.addEventListener('mousedown', (e) => {
         isResizing = true;
         initWidth = textBox.offsetWidth;
         initHeight = textBox.offsetHeight;
         initX = e.clientX;
         initY = e.clientY;
         e.stopPropagation();
         e.preventDefault();
      });
      document.addEventListener('mousemove', (e) => {
         if(isResizing) {
           let newWidth = initWidth + (e.clientX - initX);
           let newHeight = initHeight + (e.clientY - initY);
           if(newWidth < 50) newWidth = 50;
           if(newHeight < 30) newHeight = 30;
           textBox.style.width = newWidth + 'px';
           textBox.style.height = newHeight + 'px';
         }
      });
      document.addEventListener('mouseup', () => { isResizing = false; });
      
      // Menú contextual para formateo de texto
      textBox.addEventListener('contextmenu', (e) => {
         e.preventDefault();
         showTextFormatBubble(e, textBox);
      });
    }

    /* ========================================
       Conexiones entre Elementos con Routing
       ======================================== */
    function computeConnectionPath(node1, node2) {
      const canvasRect = canvas.getBoundingClientRect();
      const r1 = node1.getBoundingClientRect();
      const r2 = node2.getBoundingClientRect();
      const sx = r1.left + r1.width / 2 - canvasRect.left;
      const sy = r1.top + r1.height / 2 - canvasRect.top;
      const tx = r2.left + r2.width / 2 - canvasRect.left;
      const ty = r2.top + r2.height / 2 - canvasRect.top;
      if (node1.classList.contains('materia') && node2.classList.contains('materia')) {
        return [{x: sx, y: sy}, {x: tx, y: sy}, {x: tx, y: ty}];
      } else {
        return [{x: sx, y: sy}, {x: tx, y: ty}];
      }
    }
    function updateConnectionPaths() {
      const canvasRect = canvas.getBoundingClientRect();
      connections.forEach(conn => {
        const path = computeConnectionPath(conn.node1, conn.node2);
        const pointsStr = path.map(pt => `${pt.x},${pt.y}`).join(" ");
        conn.polyline.setAttribute("points", pointsStr);
      });
    }
    function drawConnection(node1, node2) {
      const polyline = document.createElementNS(svgNS, "polyline");
      polyline.setAttribute("stroke", "black");
      polyline.setAttribute("stroke-width", "2");
      polyline.setAttribute("fill", "none");
      svgConnections.appendChild(polyline);
      connections.push({ node1, node2, polyline });
      updateConnectionPaths();
    }
    function removeConnectionsOf(node) {
      connections = connections.filter(conn => {
        if (conn.node1 === node || conn.node2 === node) {
          conn.polyline.remove();
          return false;
        }
        return true;
      });
    }
    function updateConnections() {
      updateConnectionPaths();
    }

    /* ========================================
       Eventos Globales: Arrastre y Snapping
       ======================================== */
    document.addEventListener('mousemove', (e) => {
      if (currentMateria) {
        const parentRect = currentMateria.parentElement.getBoundingClientRect();
        let newLeft = e.clientX - parentRect.left - offsetX;
        let newTop = e.clientY - parentRect.top - offsetY;
        if (newLeft < 0) newLeft = 0;
        if (newTop < 0) newTop = 0;
        if (newLeft + currentMateria.offsetWidth > currentMateria.parentElement.clientWidth)
          newLeft = currentMateria.parentElement.clientWidth - currentMateria.offsetWidth;
        if (newTop + currentMateria.offsetHeight > currentMateria.parentElement.clientHeight)
          newTop = currentMateria.parentElement.clientHeight - currentMateria.offsetHeight;
        if (currentMateria.classList.contains('materia') || currentMateria.classList.contains('text-box-container')) {
          newLeft = Math.round(newLeft / 5) * 5;
          newTop = Math.round(newTop / 5) * 5;
          clearGuidelines();
        } else {
          const snapResult = snapElementPosition(currentMateria, newLeft, newTop);
          newLeft = snapResult.left;
          newTop = snapResult.top;
          displayGuidelines(snapResult.guidelines);
        }
        currentMateria.style.left = newLeft + 'px';
        currentMateria.style.top = newTop + 'px';
        if (currentMateria.classList.contains('materia')) {
          const matRect = currentMateria.getBoundingClientRect();
          const centerX = matRect.left + matRect.width / 2;
          const centerY = matRect.top + matRect.height / 2;
          const bigRects = document.querySelectorAll('.big-rectangle');
          bigRects.forEach(bigRect => {
            const brRect = bigRect.getBoundingClientRect();
            if (
              centerX >= brRect.left &&
              centerX <= brRect.right &&
              centerY >= brRect.top &&
              centerY <= brRect.bottom
            ) {
              bigRect.classList.add('ring-4', 'ring-green-400');
            } else {
              bigRect.classList.remove('ring-4', 'ring-green-400');
            }
          });
        }
      }
      updateConnections();
    });
    document.addEventListener('mouseup', () => {
      document.querySelectorAll('.big-rectangle').forEach(bigRect => {
        bigRect.classList.remove('ring-4', 'ring-green-400');
      });
      clearGuidelines();
      if (currentMateria && currentMateria.parentElement === canvas) {
        const matRect = currentMateria.getBoundingClientRect();
        const centerX = matRect.left + matRect.width / 2;
        const centerY = matRect.top + matRect.height / 2;
        const bigRects = document.querySelectorAll('.big-rectangle');
        for (let bigRect of bigRects) {
          const brRect = bigRect.getBoundingClientRect();
          if (
            centerX >= brRect.left &&
            centerX <= brRect.right &&
            centerY >= brRect.top &&
            centerY <= brRect.bottom
          ) {
            const relativeLeft = centerX - brRect.left - matRect.width / 2;
            const relativeTop = centerY - brRect.top - matRect.height / 2;
            currentMateria.style.left = relativeLeft + 'px';
            currentMateria.style.top = relativeTop + 'px';
            bigRect.appendChild(currentMateria);
            break;
          }
        }
      }
      if (currentMateria) {
        currentMateria.classList.remove('dragging-materia');
        currentMateria.style.cursor = "grab";
      }
      currentMateria = null;
    });
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Delete' && selectedElement) {
        if (selectedElement.classList.contains('materia')) {
          removeConnectionsOf(selectedElement);
        }
        selectedElement.remove();
        selectedElement = null;
      }
    });
    canvas.addEventListener('click', () => {
      if (selectedElement) {
        selectedElement.classList.remove('ring-2', 'ring-blue-500');
        selectedElement = null;
      }
      hideContextBubble();
      hideTextFormatBubble();
    });

    /* ========================================
       Draggable Sidebar
       ======================================== */
    let sidebarDragging = false, sidebarOffsetX = 0, sidebarOffsetY = 0;
    const sidebarHeader = document.getElementById('sidebarHeader');
    sidebarHeader.addEventListener('mousedown', (e) => {
      sidebarDragging = true;
      sidebarOffsetX = e.clientX - sidebar.offsetLeft;
      sidebarOffsetY = e.clientY - sidebar.offsetTop;
    });
    document.addEventListener('mousemove', (e) => {
      if (sidebarDragging) {
        sidebar.style.left = (e.clientX - sidebarOffsetX) + 'px';
        sidebar.style.top = (e.clientY - sidebarOffsetY) + 'px';
      }
    });
    document.addEventListener('mouseup', () => { sidebarDragging = false; });

    /* ========================================
       Toggle Sidebar con Clic Derecho en Canvas
       ======================================== */
    canvas.addEventListener('contextmenu', (e) => {
      if (e.target === canvas) {
        e.preventDefault();
        sidebar.classList.toggle('hidden');
      }
    });

    /* ========================================
       Menú Contextual (Globito) para Cuadros Principales
       ======================================== */
    let currentContextObject = null;
    function showContextBubble(e, obj) {
      currentContextObject = obj;
      const canvasRect = canvas.getBoundingClientRect();
      const x = e.clientX - canvasRect.left + 10;
      const y = e.clientY - canvasRect.top + 10;
      contextBubble.style.left = x + 'px';
      contextBubble.style.top = y + 'px';
      contextBubble.classList.remove('hidden');
    }
    function hideContextBubble() {
      contextBubble.classList.add('hidden');
      currentContextObject = null;
    }
    bubbleColor.addEventListener('click', () => {
      if (currentContextObject) {
        modalMode = 'edit';
        selectedElement = currentContextObject;
        modalTitle.textContent = 'Color del Rectángulo';
        colorPicker.value = currentContextObject.dataset.bgcolor || '#ffffff';
        showColorModal();
        hideContextBubble();
      }
    });
    bubbleDelete.addEventListener('click', () => {
      if (currentContextObject) {
        if (currentContextObject.classList.contains('materia')) {
          removeConnectionsOf(currentContextObject);
        }
        currentContextObject.remove();
        hideContextBubble();
      }
    });
    canvas.addEventListener('click', () => { hideContextBubble(); });

    /* ========================================
       Menú Contextual para Formato de Texto (cuadro de texto)
       ======================================== */
    let currentTextBox = null;
    function showTextFormatBubble(e, textBox) {
      const textFormatBubble = document.getElementById('textFormatBubble');
      currentTextBox = textBox;
      const canvasRect = canvas.getBoundingClientRect();
      const x = e.clientX - canvasRect.left + 10;
      const y = e.clientY - canvasRect.top + 10;
      textFormatBubble.style.left = x + 'px';
      textFormatBubble.style.top = y + 'px';
      textFormatBubble.classList.remove('hidden');
    }
    function hideTextFormatBubble() {
      document.getElementById('textFormatBubble').classList.add('hidden');
      currentTextBox = null;
    }
    document.getElementById('formatBold').addEventListener('click', () => {
      document.execCommand('bold', false, null);
    });
    document.getElementById('formatItalic').addEventListener('click', () => {
      document.execCommand('italic', false, null);
    });
    document.getElementById('formatUnderline').addEventListener('click', () => {
      document.execCommand('underline', false, null);
    });
    document.getElementById('increaseFont').addEventListener('click', () => {
      if(currentTextBox) {
        const content = currentTextBox.querySelector('.text-box-content');
        let currentSize = parseInt(window.getComputedStyle(content).fontSize);
        content.style.fontSize = (currentSize + 2) + "px";
      }
    });
    document.getElementById('decreaseFont').addEventListener('click', () => {
      if(currentTextBox) {
        const content = currentTextBox.querySelector('.text-box-content');
        let currentSize = parseInt(window.getComputedStyle(content).fontSize);
        content.style.fontSize = (currentSize - 2) + "px";
      }
    });
    document.getElementById('toggleFontFamily').addEventListener('click', () => {
      if(currentTextBox) {
        const content = currentTextBox.querySelector('.text-box-content');
        let currentFamily = window.getComputedStyle(content).fontFamily;
        if(currentFamily.toLowerCase().includes("sans")) {
          content.style.fontFamily = "Times New Roman, serif";
        } else {
          content.style.fontFamily = "Arial, sans-serif";
        }
      }
    });
    document.getElementById('toggleTextBoxStyle').addEventListener('click', () => {
      if(currentTextBox) {
        currentTextBox.classList.toggle('clean-text-box');
      }
    });
    canvas.addEventListener('click', () => { hideTextFormatBubble(); });
  </script>
</body>
</html>
