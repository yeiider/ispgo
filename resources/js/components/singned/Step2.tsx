import { Button } from "@/components/ui/button"
import {Step2Props} from "@/interfaces/ISigned.ts";

export default function Step2({ nextStep, prevStep }:Step2Props) {
  return (
    <div className="space-y-6">
      <h2 className="text-2xl font-semibold text-gray-800">Paso 2: Detalles del Contrato</h2>
      <div className="bg-white shadow-md rounded-lg p-6">
        <h3 className="text-lg font-semibold mb-4 text-gray-700">Contrato de Servicios</h3>
        <div className="space-y-4 text-sm text-gray-600">
          <p>
            Este Contrato de Servicios (el "Contrato") se celebra entre [Nombre de la Empresa] ("Proveedor") y el cliente ("Cliente").
          </p>
          <p>
            1. Servicios: El Proveedor se compromete a proporcionar los siguientes servicios al Cliente: [Descripción detallada de los servicios].
          </p>
          <p>
            2. Duración: Este Contrato entrará en vigor a partir de la fecha de firma y continuará hasta [fecha de finalización o condición].
          </p>
          <p>
            3. Compensación: El Cliente acuerda pagar al Proveedor [monto] por los servicios prestados, según los términos establecidos en este Contrato.
          </p>
          <p>
            4. Confidencialidad: Ambas partes acuerdan mantener la confidencialidad de toda la información compartida durante la prestación de los servicios.
          </p>
          <p>
            5. Terminación: Cualquiera de las partes puede terminar este Contrato con un aviso por escrito de [número] días.
          </p>
        </div>
      </div>
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

