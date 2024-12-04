import {__} from "@/translation.ts";
import {useMemo} from "react";
import {useStepper} from "headless-stepper";
import StepsNavigate from "@/components/checkout/StepsNavigate.tsx";
import Reference from "@/components/checkout/Reference.tsx";

export default function Checkout() {
  const stepsList = [
    {label: __("Reference"), disabled: false},
    {label: __("Payment method"), disabled: true},
    {label: __("Summary"), disabled: true}
  ]

  const steps = useMemo(
    () => stepsList,
    []);

  const {state, stepperProps, stepsProps, progressProps, setStep} = useStepper({
    steps
  })

  const barSize = useMemo(
    () => Math.ceil((state.currentStep / (steps?.length - 1)) * 100),
    [state, steps]
  )

  async function navigation(steteNumber: number) {
    if (steps[steteNumber]) {
      steps[steteNumber].disabled = false
    }
    setStep(steteNumber)
  }

  return (
    <div className="min-h-[80vh] max-w-2xl mx-auto mt-8 md:mt-12 mb-5">
      <div className="">
        <div className="max-w-2xl mx-auto mb-10">
          <StepsNavigate stepperProps={stepperProps} stepsProps={stepsProps} state={state} steps={steps}
                         progressProps={progressProps} barSize={barSize}/>
        </div>

        {state.currentStep == 0 && (
          <Reference navigation={navigation}/>
        )}
        {state.currentStep == 1 && (
          <>Payment method</>
        )}
        {state.currentStep == 2 && (
          <>
            Summary
          </>
        )}
      </div>
    </div>
  )
}
