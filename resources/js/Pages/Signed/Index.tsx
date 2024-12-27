'use client'

import {useState} from 'react'
import Step1 from '@/components/singned/Step1'
import Step2 from '@/components/singned/Step2'
import Step3 from '@/components/singned/Step3'
import Step4 from '@/components/singned/Step4'
import {usePage} from "@inertiajs/react";
import SignedDocumentView from "@/components/singned/SignedDocumentView.tsx";


type Props = {
  contractHtml: string,
  url_signed:string
  flash: {
    status: string | null,
  }
  pdfUrl: string,
  signedAt: string,
}

export default function Signing() {

  const {
    contractHtml,
    url_signed,
    isSigned,
    signedAt,
    pdfUrl,
  } = usePage<Props>().props;

  if (isSigned) {
    return (
      <SignedDocumentView
        pdfUrl={pdfUrl}
        signedAt={signedAt}
      />
    )
  }

  const [currentStep, setCurrentStep] = useState(1)
  const [acceptedPolicies, setAcceptedPolicies] = useState({
    dataPolicy: false,
    privacyPolicy: false,
    termsOfService: false,
  })
  const [signature, setSignature] = useState('')

  const nextStep = () => setCurrentStep((prev) => Math.min(prev + 1, 4))
  const prevStep = () => setCurrentStep((prev) => Math.max(prev - 1, 1))

  const renderStep = () => {
    switch (currentStep) {
      case 1:
        return <Step1 acceptedPolicies={acceptedPolicies} setAcceptedPolicies={setAcceptedPolicies}
                      nextStep={nextStep}/>
      case 2:
        return <Step2 nextStep={nextStep} prevStep={prevStep} contractHtml={contractHtml}/>
      case 3:
        return <Step3 nextStep={nextStep} prevStep={prevStep} setSignature={setSignature} />
      case 4:
        return <Step4 signature={signature} url_signed={url_signed}/>
      default:
        return null
    }
  }

  return (
    <div className="max-w-3xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
      <div className="mb-8">
        <div className="flex justify-between">
          {[1, 2, 3, 4].map((step) => (
            <div
              key={step}
              className={`w-1/4 text-center ${
                currentStep >= step ? 'text-blue-600' : 'text-gray-400'
              }`}
            >
              <div className="relative">
                <div className="w-8 h-8 mx-auto bg-white border-2 rounded-full text-lg flex items-center">
                  <span className="text-center w-full">
                    {currentStep > step ? (
                      <svg className="w-6 h-6 text-blue-600 mx-auto" fill="none" strokeLinecap="round"
                           strokeLinejoin="round" strokeWidth="2" viewBox="0 0 24 24" stroke="currentColor">
                        <path d="M5 13l4 4L19 7"/>
                      </svg>
                    ) : (
                      step
                    )}
                  </span>
                </div>
              </div>
              <div className="text-xs mt-2">Paso {step}</div>
            </div>
          ))}
        </div>
        <div className="flex mt-4">
          {[1, 2, 3].map((step) => (
            <div
              key={step}
              className={`w-1/3 h-1 ${
                currentStep > step ? 'bg-blue-600' : 'bg-gray-200'
              }`}
            />
          ))}
        </div>
      </div>
      <div className="bg-white shadow-lg rounded-lg p-6">
        {renderStep()}
      </div>
    </div>
  )
}

