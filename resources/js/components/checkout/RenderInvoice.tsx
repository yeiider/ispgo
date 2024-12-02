import {Invoice} from "@/interfaces/Invoice.ts";
import {__} from "@/translation.ts";
import {ChevronRight} from "lucide-react";
import {Button} from "@/components/ui/button.tsx";
import RenderInvoiceStatus from "@/components/RenderInvoiceStatus.tsx";

interface Props {
  invoice: Invoice | null
  navigation: (step: number) => void
}

export default function RenderInvoice({invoice, navigation}: Props) {
  return (
    <div className="relative flex flex-col bg-white shadow-lg rounded-xl pointer-events-auto dark:bg-neutral-800">
      <div className="relative overflow-hidden min-h-32 bg-gray-900 text-center rounded-t-xl dark:bg-neutral-950">
        {/* SVG Background Element */}
        <figure className="absolute inset-x-0 bottom-0 -mb-px">
          <svg preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px"
               viewBox="0 0 1920 100.1">
            <path fill="currentColor" className="fill-white dark:fill-neutral-800"
                  d="M0,0c0,0,934.4,93.4,1920,0v100.1H0L0,0z"></path>
          </svg>
        </figure>
        {/* End SVG Background Element */}
      </div>

      <div className="relative z-10 -mt-12">
        {/* Icon */}
        <span
          className="mx-auto flex justify-center items-center size-[62px] rounded-full border border-gray-200 bg-white text-gray-700 shadow-sm dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-400">
          <svg className="shrink-0 size-6" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
               viewBox="0 0 16 16">
            <path
              d="M1.92.506a.5.5 0 0 1 .434.14L3 1.293l.646-.647a.5.5 0 0 1 .708 0L5 1.293l.646-.647a.5.5 0 0 1 .708 0L7 1.293l.646-.647a.5.5 0 0 1 .708 0L9 1.293l.646-.647a.5.5 0 0 1 .708 0l.646.647.646-.647a.5.5 0 0 1 .708 0l.646.647.646-.647a.5.5 0 0 1 .801.13l.5 1A.5.5 0 0 1 15 2v12a.5.5 0 0 1-.053.224l-.5 1a.5.5 0 0 1-.8.13L13 14.707l-.646.647a.5.5 0 0 1-.708 0L11 14.707l-.646.647a.5.5 0 0 1-.708 0L9 14.707l-.646.647a.5.5 0 0 1-.708 0L7 14.707l-.646.647a.5.5 0 0 1-.708 0L5 14.707l-.646.647a.5.5 0 0 1-.708 0L3 14.707l-.646.647a.5.5 0 0 1-.801-.13l-.5-1A.5.5 0 0 1 1 14V2a.5.5 0 0 1 .053-.224l.5-1a.5.5 0 0 1 .367-.27zm.217 1.338L2 2.118v11.764l.137.274.51-.51a.5.5 0 0 1 .707 0l.646.647.646-.646a.5.5 0 0 1 .708 0l.646.646.646-.646a.5.5 0 0 1 .708 0l.646.646.646-.646a.5.5 0 0 1 .708 0l.646.646.646-.646a.5.5 0 0 1 .708 0l.646.646.646-.646a.5.5 0 0 1 .708 0l.509.509.137-.274V2.118l-.137-.274-.51.51a.5.5 0 0 1-.707 0L12 1.707l-.646.647a.5.5 0 0 1-.708 0L10 1.707l-.646.647a.5.5 0 0 1-.708 0L8 1.707l-.646.647a.5.5 0 0 1-.708 0L6 1.707l-.646.647a.5.5 0 0 1-.708 0L4 1.707l-.646.647a.5.5 0 0 1-.708 0l-.509-.51z"/>
            <path
              d="M3 4.5a.5.5 0 0 1 .5-.5h6a.5.5 0 1 1 0 1h-6a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h6a.5.5 0 1 1 0 1h-6a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h6a.5.5 0 1 1 0 1h-6a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h6a.5.5 0 0 1 0 1h-6a.5.5 0 0 1-.5-.5zm8-6a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5z"/>
          </svg>
        </span>
        {/* End Icon */}
      </div>

      {/* Body */}
      <div className="p-4 sm:p-7 overflow-y-auto">
        <div className="text-center">
          <h3 id="hs-ai-modal-label" className="text-lg font-semibold text-gray-800 dark:text-neutral-200">
            Invoice from Preline
          </h3>
          <p className="text-sm text-gray-500 dark:text-neutral-500">
            Invoice #{invoice?.increment_id}
          </p>
        </div>

        {/* Grid */}
        <div className="mt-5 sm:mt-10 grid grid-cols-2 sm:grid-cols-3 gap-5">
          <div>
            <span className="block text-xs uppercase text-gray-500 dark:text-neutral-500">Total</span>
            <span className="block text-sm font-medium text-gray-800 dark:text-neutral-200">{invoice?.total}</span>
          </div>
          {/* End Col */}

          <div>
            <span className="block text-xs uppercase text-gray-500 dark:text-neutral-500">Status paid:</span>
            <div className="block text-sm font-medium text-gray-800 dark:text-neutral-200">
              <RenderInvoiceStatus status={invoice?.status}/>
            </div>
          </div>
          {/* End Col */}

          <div>
            <span className="block text-xs uppercase text-gray-500 dark:text-neutral-500">Customer:</span>
            <div className="flex items-center gap-x-2">

              <span
                className="block text-sm font-medium text-gray-800 dark:text-neutral-200">{invoice?.customer_name}</span>
            </div>
          </div>
          {/* End Col */}
        </div>
        {/* End Grid */}

        <div className="mt-5 sm:mt-10">
          <h4 className="text-xs font-semibold uppercase text-gray-800 dark:text-neutral-200">Summary</h4>

          <ul className="mt-3 flex flex-col">
            <li
              className="inline-flex items-center gap-x-2 py-3 px-4 text-sm border text-gray-800 -mt-px first:rounded-t-lg first:mt-0 last:rounded-b-lg dark:border-neutral-700 dark:text-neutral-200">
              <div className="flex items-center justify-between w-full">
                <span>Tax fee</span>
                <span>{invoice?.tax}</span>
              </div>
            </li>
            <li
              className="inline-flex items-center gap-x-2 py-3 px-4 text-sm font-semibold bg-gray-50 border text-gray-800 -mt-px first:rounded-t-lg first:mt-0 last:rounded-b-lg dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-200">
              <div className="flex items-center justify-between w-full">
                <span>Amount</span>
                <span>{invoice?.amount}</span>
              </div>
            </li>
            <li
              className="inline-flex items-center gap-x-2 py-3 px-4 text-sm border text-gray-800 -mt-px first:rounded-t-lg first:mt-0 last:rounded-b-lg dark:border-neutral-700 dark:text-neutral-200">
              <div className="flex items-center justify-between w-full">
                <span>Discount</span>
                <span>{invoice?.discount}</span>
              </div>
            </li>
            <li
              className="inline-flex items-center gap-x-2 py-3 px-4 text-sm font-semibold bg-gray-50 border text-gray-800 -mt-px first:rounded-t-lg first:mt-0 last:rounded-b-lg dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-200">
              <div className="flex items-center justify-between w-full">
                <span>SubTotal</span>
                <span>{invoice?.subtotal}</span>
              </div>
            </li>
            <li
              className="inline-flex items-center gap-x-2 py-3 px-4 text-sm border text-gray-800 -mt-px first:rounded-t-lg first:mt-0 last:rounded-b-lg dark:border-neutral-700 dark:text-neutral-200">
              <div className="flex items-center justify-between w-full">
                <span>Total</span>
                <span>{invoice?.total}</span>
              </div>
            </li>
          </ul>
        </div>

        {/* Button */}
        <div className="mt-5 flex justify-end gap-x-2">
          <Button disabled={!invoice} onClick={() => navigation(1)} type="button">
            <span>{__('Continue')}</span>
            <ChevronRight/>
          </Button>

          <a
            className="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none"
            href="#">
            <svg className="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                 viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round"
                 strokeLinejoin="round">
              <polyline points="6 9 6 2 18 2 18 9"/>
              <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/>
              <rect width="12" height="8" x="6" y="14"/>
            </svg>
            Print
          </a>
        </div>
        {/* End Buttons */}

        <div className="mt-5 sm:mt-10">
          <p className="text-sm text-gray-500 dark:text-neutral-500">If you have any questions, please contact us
            at <a
              className="inline-flex items-center gap-x-1.5 text-blue-600 decoration-2 hover:underline focus:outline-none focus:underline font-medium dark:text-blue-500"
              href="#">example@site.com</a> or call at <a
              className="inline-flex items-center gap-x-1.5 text-blue-600 decoration-2 hover:underline focus:outline-none focus:underline font-medium dark:text-blue-500"
              href="tel:+1898345492">+1 898-34-5492</a></p>
        </div>
      </div>
      {/* End Body */}
    </div>
  )
}
