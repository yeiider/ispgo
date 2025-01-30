<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{__('Invoice')}}</title>
    @vite('resources/css/app.css')
</head>
<body>
<div class="relative flex flex-col bg-white shadow-lg rounded-xl pointer-events-auto dark:bg-neutral-800">
    <div class="relative overflow-hidden min-h-32 bg-gray-900 text-center rounded-t-xl dark:bg-neutral-950">
        <figure class="absolute inset-x-0 bottom-0 -mb-px">
            <svg preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px"
                 viewBox="0 0 1920 100.1">
                <path fill="currentColor" class="fill-white dark:fill-neutral-800"
                      d="M0,0c0,0,934.4,93.4,1920,0v100.1H0L0,0z"></path>
            </svg>
        </figure>
    </div>

    <div class="relative z-10 -mt-12">

        <span
            class="mx-auto flex justify-center items-center size-[62px] rounded-full border border-gray-200 bg-white text-gray-700 shadow-sm dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-400">
          <svg class="shrink-0 size-6" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
               viewBox="0 0 16 16">
            <path
                d="M1.92.506a.5.5 0 0 1 .434.14L3 1.293l.646-.647a.5.5 0 0 1 .708 0L5 1.293l.646-.647a.5.5 0 0 1 .708 0L7 1.293l.646-.647a.5.5 0 0 1 .708 0L9 1.293l.646-.647a.5.5 0 0 1 .708 0l.646.647.646-.647a.5.5 0 0 1 .708 0l.646.647.646-.647a.5.5 0 0 1 .801.13l.5 1A.5.5 0 0 1 15 2v12a.5.5 0 0 1-.053.224l-.5 1a.5.5 0 0 1-.8.13L13 14.707l-.646.647a.5.5 0 0 1-.708 0L11 14.707l-.646.647a.5.5 0 0 1-.708 0L9 14.707l-.646.647a.5.5 0 0 1-.708 0L7 14.707l-.646.647a.5.5 0 0 1-.708 0L5 14.707l-.646.647a.5.5 0 0 1-.708 0L3 14.707l-.646.647a.5.5 0 0 1-.801-.13l-.5-1A.5.5 0 0 1 1 14V2a.5.5 0 0 1 .053-.224l.5-1a.5.5 0 0 1 .367-.27zm.217 1.338L2 2.118v11.764l.137.274.51-.51a.5.5 0 0 1 .707 0l.646.647.646-.646a.5.5 0 0 1 .708 0l.646.646.646-.646a.5.5 0 0 1 .708 0l.646.646.646-.646a.5.5 0 0 1 .708 0l.646.646.646-.646a.5.5 0 0 1 .708 0l.646.646.646-.646a.5.5 0 0 1 .708 0l.509.509.137-.274V2.118l-.137-.274-.51.51a.5.5 0 0 1-.707 0L12 1.707l-.646.647a.5.5 0 0 1-.708 0L10 1.707l-.646.647a.5.5 0 0 1-.708 0L8 1.707l-.646.647a.5.5 0 0 1-.708 0L6 1.707l-.646.647a.5.5 0 0 1-.708 0L4 1.707l-.646.647a.5.5 0 0 1-.708 0l-.509-.51z"/>
            <path
                d="M3 4.5a.5.5 0 0 1 .5-.5h6a.5.5 0 1 1 0 1h-6a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h6a.5.5 0 1 1 0 1h-6a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h6a.5.5 0 1 1 0 1h-6a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h6a.5.5 0 0 1 0 1h-6a.5.5 0 0 1-.5-.5zm8-6a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5z"/>
          </svg>
        </span>
    </div>

    <div class="p-4 sm:p-7 overflow-y-auto">
        <div class="text-center">
            <h3 id="hs-ai-modal-label" class="text-lg font-semibold text-gray-800 dark:text-neutral-200">
                {{__('Invoice')}}
            </h3>
            <p class="text-sm text-gray-500 dark:text-neutral-500">
                {{__('Reference')}} #{{$invoice->increment_id}}
            </p>
        </div>


        <div class="mt-5 sm:mt-10 grid grid-cols-2 sm:grid-cols-3 gap-5">
            <div>
                <span class="block text-xs uppercase text-gray-500 dark:text-neutral-500">Total</span>
                <span class="block text-sm font-medium text-gray-800 dark:text-neutral-200">{{$invoice->total}}</span>
            </div>

            <div>
                <span class="block text-xs uppercase text-gray-500 dark:text-neutral-500">{{__('Status paid')}}:</span>
                <div class="block text-sm font-medium text-gray-800 dark:text-neutral-200">
                    @if($invoice->status === 'paid')
                        <div
                            class="flex items-center max-w-fit gap-1 px-4 py-2 rounded-full bg-green-50 text-green-500">
                            <span class="text-green-500">{{__('Paid')}}</span>
                        </div>
                    @elseif($invoice->status === 'unpaid')
                        <div
                            class="flex items-center max-w-fit gap-1 px-4 py-2 rounded-full bg-yellow-50 text-yellow-500">
                            <span class="">{{__('Unpaid')}}</span>
                        </div>

                    @elseif($invoice->status === 'canceled')
                        <div class="flex items-center max-w-fit gap-1 px-4 py-2 rounded-full bg-red-50 text-red-500">
                            <span class="">{{__('Canceled')}}</span>
                        </div>
                    @endif
                </div>
            </div>

            <div>
                <span class="block text-xs uppercase text-gray-500 dark:text-neutral-500">{{__('Customer')}}:</span>
                <div class="flex items-center gap-x-2">

              <span
                  class="block text-sm font-medium text-gray-800 dark:text-neutral-200">{{$invoice->full_name}}</span>
                </div>
            </div>
        </div>

        <div class="mt-5 sm:mt-10">
            <h4 class="text-xs font-semibold uppercase text-gray-800 dark:text-neutral-200">{{__('Summary')}}</h4>

            <ul class="mt-3 flex flex-col">
                <li
                    class="inline-flex items-center gap-x-2 py-3 px-4 text-sm border text-gray-800 -mt-px first:rounded-t-lg first:mt-0 last:rounded-b-lg dark:border-neutral-700 dark:text-neutral-200">
                    <div class="flex items-center justify-between w-full">
                        <span>{{__('Tax')}}</span>
                        <span>{{$invoice->tax}}</span>
                    </div>
                </li>
                <li
                    class="inline-flex items-center gap-x-2 py-3 px-4 text-sm font-semibold bg-gray-50 border text-gray-800 -mt-px first:rounded-t-lg first:mt-0 last:rounded-b-lg dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-200">
                    <div class="flex items-center justify-between w-full">
                        <span>{{__('Amount')}}</span>
                        <span>{{$invoice->amount}}</span>
                    </div>
                </li>
                <li
                    class="inline-flex items-center gap-x-2 py-3 px-4 text-sm border text-gray-800 -mt-px first:rounded-t-lg first:mt-0 last:rounded-b-lg dark:border-neutral-700 dark:text-neutral-200">
                    <div class="flex items-center justify-between w-full">
                        <span>{{__('Discount')}}</span>
                        <span>{{$invoice->discount}}</span>
                    </div>
                </li>
                <li
                    class="inline-flex items-center gap-x-2 py-3 px-4 text-sm font-semibold bg-gray-50 border text-gray-800 -mt-px first:rounded-t-lg first:mt-0 last:rounded-b-lg dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-200">
                    <div class="flex items-center justify-between w-full">
                        <span>SubTotal</span>
                        <span>{{$invoice->subtotal}}</span>
                    </div>
                </li>
                <li
                    class="inline-flex items-center gap-x-2 py-3 px-4 text-sm border text-gray-900 -mt-px first:rounded-t-lg first:mt-0 last:rounded-b-lg dark:border-neutral-700 dark:text-neutral-200">
                    <div
                        class="flex items-center justify-between w-full text-[18px] font-medium [dark:text-neutral-200]">
                        <span>Total</span>
                        <span>{{$invoice->total}}</span>
                    </div>
                </li>
            </ul>
        </div>


        <div class="mt-5 sm:mt-10">
            <p
                class="text-sm text-gray-500 dark:text-neutral-500">
                @if($companyEmail)
                    {{__('If you have any questions, please contact at ')}}
                    <a
                        class="inline-flex items-center gap-x-1.5 text-blue-600 decoration-2 hover:underline focus:outline-none focus:underline font-medium dark:text-blue-500"
                        href="mailto:{{$companyEmail}}">{{$companyEmail}}</a>
                @endif

                @if($companyPhone)
                    {{__(' or call at ')}}
                        <a
                            class="inline-flex items-center gap-x-1.5 text-blue-600 decoration-2 hover:underline focus:outline-none focus:underline font-medium dark:text-blue-500"
                            href="tel:{{$companyPhone}}">{{$companyPhone}}</a>
                @endif
            </p>
        </div>
    </div>
</div>
</body>
</html>
