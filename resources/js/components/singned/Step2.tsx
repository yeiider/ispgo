import { Button } from "@/components/ui/button"
import {Step2Props} from "@/interfaces/ISigned.ts";

export default function Step2({nextStep, prevStep, contractHtml}:Step2Props) {
  return (
    <div className="space-y-6">
      <h2 className="text-2xl font-semibold text-gray-800">Paso 2: Detalles del Contrato</h2>
      <div
        dangerouslySetInnerHTML={{__html: contractHtml || ""}}
        className="text-gray-700"
      />
      <div className="flex justify-between">
        <Button onClick={prevStep} variant="outline" className="text-sm">
          Atrás
        </Button>
        <Button onClick={nextStep} className="text-sm bg-blue-600 hover:bg-blue-700 text-white">
          Continuar
        </Button>
      </div>
    </div>
  )
}

