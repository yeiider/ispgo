import { useState, useRef, useEffect } from 'react'
import { Button } from "@/components/ui/button"
import {Step3Props} from "@/interfaces/ISigned.ts";

export default function Step3({ nextStep, prevStep, setSignature }:Step3Props) {
  const [isSigned, setIsSigned] = useState(false)
  const canvasRef = useRef<HTMLCanvasElement | null>(null)
  const [isDrawing, setIsDrawing] = useState(false)

  useEffect(() => {
    const canvas = canvasRef.current;
    const context = canvas?.getContext("2d");
    if (context) {
      context.strokeStyle = "#000000";
      context.lineWidth = 2;
    }
  }, []);

  const startDrawing = (event: React.MouseEvent<HTMLCanvasElement>) => {
    const canvas = canvasRef.current;
    const context = canvas?.getContext("2d");
    if (canvas && context) {
      const rect = canvas.getBoundingClientRect();
      const scaleX = canvas.width / rect.width;
      const scaleY = canvas.height / rect.height;
      const x = (event.clientX - rect.left) * scaleX;
      const y = (event.clientY - rect.top) * scaleY;
      context.beginPath();
      context.moveTo(x, y);
      setIsDrawing(true);
    }
  };


  const draw = (event: React.MouseEvent<HTMLCanvasElement>) => {
    if (!isDrawing || !canvasRef.current) return;
    const canvas = canvasRef.current;
    const context = canvas.getContext("2d");
    if (context) {
      const rect = canvas.getBoundingClientRect();
      const scaleX = canvas.width / rect.width;
      const scaleY = canvas.height / rect.height;
      const x = (event.clientX - rect.left) * scaleX;
      const y = (event.clientY - rect.top) * scaleY;
      context.lineTo(x, y);
      context.stroke();
    }
  };

  const stopDrawing = () => {
    setIsDrawing(false)
    setIsSigned(true)
  }

  const handleSign = () => {
    const canvas = canvasRef.current;
    const signatureData = canvas?.toDataURL() || "";
    setSignature(signatureData);
  };

  const clearSignature = () => {
    const canvas = canvasRef.current;
    const context = canvas?.getContext("2d");
    if (context) {
      context.clearRect(0, 0, canvas!.width, canvas!.height);
      setIsSigned(false);
    }
  };

  return (
    <div className="space-y-6">
      <h2 className="text-2xl font-semibold text-gray-800">Paso 3: Firma del Contrato</h2>
      <div className="bg-white shadow-md rounded-lg p-6">
        <p className="text-sm text-gray-700 mb-4">Por favor, firme el contrato en el área designada abajo:</p>
        <div className="border-2 border-gray-300 rounded">
          <canvas
            ref={canvasRef}
            width={400}
            height={200}
            onMouseDown={startDrawing}
            onMouseMove={draw}
            onMouseUp={stopDrawing}
            onMouseOut={stopDrawing}
            className="w-full cursor-crosshair"
          />
        </div>
        <div className="flex justify-between mt-4">
          <Button onClick={clearSignature} variant="outline" className="text-sm">
            Limpiar Firma
          </Button>
          <Button onClick={handleSign} disabled={!isSigned} className="text-sm bg-blue-600 hover:bg-blue-700 text-white">
            Confirmar Firma
          </Button>
        </div>
      </div>
      <div className="flex justify-between">
        <Button onClick={prevStep} variant="outline" className="text-sm">
          Atrás
        </Button>
        <Button onClick={nextStep} disabled={!isSigned} className="text-sm bg-blue-600 hover:bg-blue-700 text-white">
          Continuar
        </Button>
      </div>
    </div>
  )
}

