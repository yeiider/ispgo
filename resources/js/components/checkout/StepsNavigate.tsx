import {HTMLAttributes} from "react";
import {StepperState} from "headless-stepper";


interface Props {
  stepperProps: HTMLAttributes<HTMLElement>
  stepsProps: HTMLAttributes<HTMLElement>[]
  state: StepperState;
  steps: ({ label: string, disabled?: undefined } | { label: string, disabled: boolean })[]
  progressProps: HTMLAttributes<HTMLElement>;
  barSize: number
}

export default function StepsNavigate({stepperProps, stepsProps, state, steps, progressProps, barSize}: Props) {
  return (

    <nav className="my-4 w-100 grid grid-cols-6 relative" {...stepperProps}>
      <ol className="col-span-full flex flex-row z-1">
        {stepsProps?.map((step, index) => (
          <li className="text-center flex-[1_0_auto]" key={index}>
            <a
              className="group flex flex-col items-center cursor-pointer focus:outline-0"
              {...step}>
                      <span
                        className={`flex items-center justify-center w-8 h-8 border border-full rounded-full group-focus:ring-2 group-focus:ring-offset-2 group-focus:ring-primary transition-colors ease-in-out ${
                          state?.currentStep === index
                            ? "bg-primary text-white ring-1 ring-offset-2 ring-primary"
                            : "bg-white text-black"
                        }`}>
                          {index + 1}
                        </span>
              <span
                className={`mt-2 text-[13px] ${
                  state?.currentStep === index ? "font-bold" : ""
                }`}>
                        {steps[index].label}
                      </span>
            </a>
          </li>
        ))}
      </ol>
      <div
        style={{gridColumn: "2 / 8"}}
        className="flex items-center flex-row top-4 right-16 relative border-0.5 bg-gray-300 z-[-1] pointer-events-none row-span-full w-full h-0.5"
        {...progressProps}
      >
        <span className="h-full w=full flex"/>
        <div
          style={{
            width: `${barSize}%`,
            gridColumn: 1 / -1,
            gridRow: 1 / -1
          }}
          className="flex flex-row h-full overflow-hidden border-solid border-0 bg-primary"
        />
      </div>
    </nav>
  )
}
