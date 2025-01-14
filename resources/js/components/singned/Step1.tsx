import { useState } from 'react'
import { Checkbox } from "@/components/ui/checkbox"
import { Button } from "@/components/ui/button"
import { ScrollArea } from "@/components/ui/scroll-area"
import {AcceptedPolicies, Step1Props} from "@/interfaces/ISigned"

export default function Step1({ acceptedPolicies, setAcceptedPolicies, nextStep }:Step1Props) {
  const [showTerms, setShowTerms] = useState(false)

  const handleCheckboxChange = (policy: keyof AcceptedPolicies) => {
    setAcceptedPolicies((prev: AcceptedPolicies) => ({
      ...prev,
      [policy]: !prev[policy],
    }));
  };


  const allPoliciesAccepted = Object.values(acceptedPolicies).every(Boolean)

  return (
    <div className="space-y-6">
      <h2 className="text-2xl font-semibold text-gray-800">Paso 1: Aceptar Políticas y Condiciones</h2>
      <div className="bg-white shadow-md rounded-lg p-6">
        <div className="space-y-4 mb-4">
          <div className="flex items-center space-x-2">
            <Checkbox
              id="dataPolicy"
              checked={acceptedPolicies.dataPolicy}
              onCheckedChange={() => handleCheckboxChange('dataPolicy')}
            />
            <label htmlFor="dataPolicy" className="text-sm text-gray-700">Acepto la Política de Tratamiento de Datos</label>
          </div>
          <div className="flex items-center space-x-2">
            <Checkbox
              id="privacyPolicy"
              checked={acceptedPolicies.privacyPolicy}
              onCheckedChange={() => handleCheckboxChange('privacyPolicy')}
            />
            <label htmlFor="privacyPolicy" className="text-sm text-gray-700">Acepto la Política de Privacidad</label>
          </div>
          <div className="flex items-center space-x-2">
            <Checkbox
              id="termsOfService"
              checked={acceptedPolicies.termsOfService}
              onCheckedChange={() => handleCheckboxChange('termsOfService')}
            />
            <label htmlFor="termsOfService" className="text-sm text-gray-700">Acepto las Condiciones de Servicio</label>
          </div>
        </div>
        <Button
          variant="outline"
          onClick={() => setShowTerms(!showTerms)}
          className="w-full mb-4"
        >
          {showTerms ? "Ocultar" : "Mostrar"} Términos y Condiciones
        </Button>
        {showTerms && (
          <ScrollArea className="h-[200px] w-full rounded border p-4">
            <h3 className="text-lg font-semibold mb-2">Términos y Condiciones</h3>
            <p className="text-sm text-gray-700">
              1. Aceptación de los Términos: Al utilizar nuestros servicios, usted acepta estar sujeto a estos Términos de Servicio. Si no está de acuerdo con alguna parte de los términos, entonces no podrá acceder al servicio.
            </p>
            <p className="text-sm text-gray-700 mt-2">
              2. Cambios en los Términos: Nos reservamos el derecho de modificar o reemplazar estos Términos en cualquier momento. Es su responsabilidad revisar estos Términos periódicamente para ver si hay cambios.
            </p>
            <p className="text-sm text-gray-700 mt-2">
              3. Privacidad: Su uso de nuestros servicios está también sujeto a nuestra Política de Privacidad, que describe cómo recopilamos, usamos y compartimos su información personal.
            </p>
            <p className="text-sm text-gray-700 mt-2">
              4. Uso Aceptable: Usted se compromete a no usar el servicio para ningún propósito ilegal o prohibido por estos términos, condiciones y avisos.
            </p>
            <p className="text-sm text-gray-700 mt-2">
              5. Terminación: Podemos terminar o suspender su acceso inmediatamente, sin previo aviso ni responsabilidad, por cualquier motivo, incluyendo, sin limitación, si usted incumple los Términos.
            </p>
          </ScrollArea>
        )}
      </div>
      <Button
        onClick={nextStep}
        disabled={!allPoliciesAccepted}
        className="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
      >
        Continuar
      </Button>
    </div>
  )
}

