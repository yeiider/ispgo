// Tipos de políticas de aceptación
interface AcceptedPolicies {
  dataPolicy: boolean;
  privacyPolicy: boolean;
  termsOfService: boolean;
}

// Props comunes para la navegación entre pasos
interface StepNavigationProps {
  nextStep: () => void;
  prevStep?: () => void; // Opcional porque algunos pasos no necesitan prevStep
}

// Props específicas para cada paso
interface Step1Props {
  acceptedPolicies: AcceptedPolicies; // Objeto representando las políticas aceptadas
  setAcceptedPolicies: React.Dispatch<React.SetStateAction<AcceptedPolicies>>; // Define el tipo para 'setAcceptedPolicies'
  nextStep: () => void; // Función para avanzar al siguiente paso
}

interface Step2Props extends StepNavigationProps {}

interface Step3Props extends StepNavigationProps {
  setSignature: (signature: string) => void;
}

interface Step4Props {
  signature: string;
}


export type {Step1Props, Step2Props, Step3Props, Step4Props,AcceptedPolicies};
