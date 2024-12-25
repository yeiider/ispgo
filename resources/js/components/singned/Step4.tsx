import { Button } from "@/components/ui/button"
import {Step4Props} from "@/interfaces/ISigned.ts";

export default function Step4({ signature }:Step4Props) {
  return (
    <div className="space-y-6">
      <h2 className="text-2xl font-semibold text-gray-800">Paso 4: Resumen y Confirmación</h2>
      <div className="bg-white shadow-md rounded-lg p-6">
        <h3 className="text-lg font-semibold mb-4 text-gray-700">Resumen de la Firma</h3>
        <p className="text-sm text-gray-600 mb-4">Has firmado exitosamente el documento. Aquí está tu firma:</p>
        <img src={signature} alt="Firma" className="border rounded-md mb-4" />
        <h3 className="text-lg font-semibold mb-4 text-gray-700">Documento Firmado</h3>
        <p className="text-sm text-gray-600 mb-4">
          En una aplicación real, aquí se mostraría el PDF del documento firmado.
          Por ahora, puedes descargar una versión simulada del documento.
        </p>
        <Button
          className="w-full bg-blue-600 hover:bg-blue-700 text-white text-sm py-2 px-4 rounded"
          onClick={() => alert('Descargando PDF firmado...')}
        >
          Descargar PDF Firmado
        </Button>
      </div>
    </div>
  )
}

