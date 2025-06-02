import { useEffect, useState } from 'react';

function SerializationLine({ from, to, isTemp = false, canvasRef }) {
    const [path, setPath] = useState('');

    useEffect(() => {
        const calculatePath = () => {
            if (!canvasRef.current) return;

            if (isTemp) {
                
                const fromElement = document.querySelector(`[data-subject="${from.period}-${from.index}"]`);
                if (!fromElement) return;

                const fromRect = fromElement.getBoundingClientRect();
                const canvasRect = canvasRef.current.getBoundingClientRect();

                
                const startX = fromRect.left + (fromRect.width / 2) - canvasRect.left;
                const startY = fromRect.bottom - canvasRect.top;
                
                
                const endX = to.x - canvasRect.left;
                const endY = to.y - canvasRect.top;

                
                const path = `M ${startX} ${startY} ` + 
                           `V ${(startY + endY) / 2} ` + 
                           `H ${endX} ` + 
                           `V ${endY}`; 

                setPath(path);
            } else {
                
                const fromElement = document.querySelector(`[data-subject="${from.period}-${from.index}"]`);
                const toElement = document.querySelector(`[data-subject="${to.period}-${to.index}"]`);
                
                if (!fromElement || !toElement) return;

                const fromRect = fromElement.getBoundingClientRect();
                const toRect = toElement.getBoundingClientRect();
                const canvasRect = canvasRef.current.getBoundingClientRect();

                
                const allSubjects = Array.from(document.querySelectorAll('[data-subject]'))
                    .map(el => el.getBoundingClientRect())
                    .filter(rect => {
                        
                        const isSource = rect.left === fromRect.left && rect.top === fromRect.top;
                        const isTarget = rect.left === toRect.left && rect.top === toRect.top;
                        return !isSource && !isTarget;
                    });

                
                const startX = fromRect.left + (fromRect.width / 2) - canvasRect.left;
                const startY = fromRect.bottom - canvasRect.top;
                const endX = toRect.left + (toRect.width / 2) - canvasRect.left;
                const endY = toRect.top - canvasRect.top;

                
                const verticalGap = 20; 

                
                const directPath = `M ${startX} ${startY} V ${endY} H ${endX}`;
                const hasObstacle = allSubjects.some(rect => {
                    const subjectX = rect.left + (rect.width / 2) - canvasRect.left;
                    const subjectY = rect.top + (rect.height / 2) - canvasRect.top;
                    return (
                        subjectX > Math.min(startX, endX) &&
                        subjectX < Math.max(startX, endX) &&
                        subjectY > startY &&
                        subjectY < endY
                    );
                });

                if (!hasObstacle) {
                    setPath(directPath);
                } else {
                    
                    const midY = startY + verticalGap;
                    const path = `M ${startX} ${startY} ` + 
                               `V ${midY} ` + 
                               `H ${endX} ` + 
                               `V ${endY}`; 

                    setPath(path);
                }
            }
        };

        calculatePath();
        window.addEventListener('resize', calculatePath);
        window.addEventListener('scroll', calculatePath);
        return () => {
            window.removeEventListener('resize', calculatePath);
            window.removeEventListener('scroll', calculatePath);
        };
    }, [from, to, isTemp]);

    return (
        <svg
            style={{
                position: 'absolute',
                top: 0,
                left: 0,
                width: '100%',
                height: '100%',
                pointerEvents: 'none',
            }}
        >
            <path
                d={path}
                fill="none"
                stroke={isTemp ? "#666" : "#000"}
                strokeWidth="2"
                strokeDasharray={isTemp ? "5,5" : "none"}
            />
        </svg>
    );
}

export default SerializationLine;