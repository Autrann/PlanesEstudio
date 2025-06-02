import { useRef, useEffect, useState } from "react";

const FONT_SIZES = [
    "8px",
    "9px",
    "10px",
    "11px",
    "12px",
    "13px",
    "14px",
    "15px",
    "16px",
    "17px",
    "18px",
    "20px",
    "22px",
    "24px",
    "26px",
    "28px",
    "30px",
    "32px",
    "34px",
    "36px",
    "40px",
    "44px",
    "48px",
    "54px",
    "60px",
    "66px",
    "72px",
    "80px",
    "88px",
    "96px",
];
const FONT_FAMILIES = [
    "Arial, sans-serif",
    "'Times New Roman', serif",
    "'Courier New', monospace",
    "'Georgia', serif",
    "'Verdana', sans-serif",
];

function RichTextEditor({ values = "", onChange }) {
    const editorRef = useRef(null);
    const [currentFontSize, setCurrentFontSize] = useState(14);

    const applyCommand = (command, value = null) => {
        document.execCommand(command, false, value);
        editorRef.current.focus();
        onChange(editorRef.current.innerHTML);
    };

    const handleInput = () => {
        if (onChange) {
            onChange(editorRef.current.innerHTML);
        }
    };

    useEffect(() => {
    if (editorRef.current) {
      editorRef.current.innerHTML = values;
    }
  }, [values]);

    const changeFontSize = (delta) => {
        let selection = window.getSelection();
        if (!selection.rangeCount) return;
        let range = selection.getRangeAt(0);

        let span = document.createElement("span");
        let computedSize = window.getComputedStyle(
            range.startContainer.parentElement
        ).fontSize;
        let size = parseInt(computedSize) || currentFontSize;
        let newSize = Math.max(8, Math.min(72, size + delta));
        span.style.fontSize = `${newSize}px`;

        range.surroundContents(span);

        setCurrentFontSize(newSize);
        editorRef.current.focus();
        onChange(editorRef.current.innerHTML);
    };

    return (
        <div className="border border-gray-300 rounded mb-4">
            {/* Toolbar */}
            <div className="bg-gray-100 p-2 flex flex-wrap items-center gap-2">
                <button
                    type="button"
                    className="px-2 py-1 bg-white border rounded hover:bg-gray-50"
                    onClick={() => applyCommand("bold")}
                >
                    <b>B</b>
                </button>
                <button
                    type="button"
                    className="px-2 py-1 bg-white border rounded hover:bg-gray-50"
                    onClick={() => applyCommand("italic")}
                >
                    <i>I</i>
                </button>
                <button
                    type="button"
                    className="px-2 py-1 bg-white border rounded hover:bg-gray-50"
                    onClick={() => applyCommand("underline")}
                >
                    <u>U</u>
                </button>
                <button
                    type="button"
                    className="px-2 py-1 bg-white border rounded hover:bg-gray-50"
                    onClick={() => applyCommand("insertUnorderedList")}
                >
                    • Lista
                </button>
                <select
                    className="px-2 py-1 border rounded bg-white"
                    onChange={(e) => applyCommand("fontSize", e.target.value)}
                    defaultValue=""
                >
                    <option value="" disabled>
                        Tamaño
                    </option>
                    {FONT_SIZES.map((size) => (
                        <option key={size} value={size}>
                            {size}
                        </option>
                    ))}
                </select>
                <select
                    className="px-2 py-1 border rounded bg-white"
                    onChange={(e) => applyCommand("fontName", e.target.value)}
                    defaultValue=""
                >
                    <option value="" disabled>
                        Fuente
                    </option>
                    {FONT_FAMILIES.map((face) => (
                        <option key={face} value={face}>
                            {face.split(",")[0]}
                        </option>
                    ))}
                </select>
                {/* Font size + and - buttons */}
                <button
                    type="button"
                    className="px-2 py-1 bg-white border rounded hover:bg-gray-50"
                    title="Aumentar tamaño de fuente"
                    onClick={() => changeFontSize(2)}
                >
                    +
                </button>
                <button
                    type="button"
                    className="px-2 py-1 bg-white border rounded hover:bg-gray-50"
                    title="Disminuir tamaño de fuente"
                    onClick={() => changeFontSize(-2)}
                >
                    -
                </button>
            </div>

            {/* Área editable */}
            <div
                ref={editorRef}
                contentEditable
                suppressContentEditableWarning
                onInput={handleInput}
                className="min-h-[180px] max-h-[350px] overflow-auto p-4 bg-white rounded-lg border border-gray-200 focus:outline-blue-400 transition-shadow shadow-inner text-gray-800"
                style={{
                    fontFamily: "Arial, sans-serif",
                    fontSize: "14px",
                    outline: "none",
                }}
            >
                Empieza a escribir aquí…
            </div>
        </div>
    );
}

export default RichTextEditor;
