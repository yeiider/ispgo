import React, {useEffect, useState} from "react";
import {Invoice} from "@/interfaces/Invoice.ts";
import axios from "axios";
import {toast} from "sonner";
import {__} from "@/translation.ts";
import {Skeleton} from "@/components/ui/skeleton.tsx";
import {PaymentMethod} from "@/interfaces/PaymentMethod.ts";
import {TriangleAlert} from "lucide-react";
import Payu from "@/components/checkout/PaymentMethods/Payu.tsx";
import Wompi from "@/components/checkout/PaymentMethods/Wompi.tsx";


interface Props {
  navigation: (step: number) => void;
}

const componentsMap: Record<string, React.ComponentType<any>> = {
  Payu,
  Wompi
};

export function RenderPaymentComponent({paymentMethod, ...restProps}: {
  paymentMethod: PaymentMethod,
  [key: string]: any
}) {
  const Component = componentsMap[paymentMethod.payment_component];
  return Component ? <Component paymentMethod={paymentMethod} {...restProps} /> : null;
}

export default function PaymentMethods({navigation}: Props) {
  const invoice: Invoice | object = JSON.parse(localStorage.getItem("invoice") || '{}');

  const [loading, setLoading] = useState<boolean>(false);
  const [paymentMethods, setPaymentMethods] = useState<PaymentMethod[]>([]);
  const [methodSelected, setMethodSelected] = useState<PaymentMethod | null>(null);

  useEffect(() => {
    if (!Object.keys(invoice).length) {
      toast.warning(__('No invoice found'), {
        classNames: {
          icon: 'text-yellow-500'
        }
      });
      navigation(0);
      return;
    }

    setLoading(true);
    axios.get('/payment/configurations').then(response => {
      setPaymentMethods(response.data);
    }).catch(e => {
      toast.error(e.response?.data?.message || 'Error occurred', {
        classNames: {
          icon: 'text-red-500',
          title: 'text-red-500'
        }
      })
    }).finally(() => setLoading(false));
  }, []);

  return (
    <div>
      <h2 className="text-2xl mt-3 md:mt-10 font-light">{__('Payment methods')}</h2>
      <p className="text-gray-600">{__('Select a payment method')}</p>
      <div className="flex flex-col gap-5 mt-5">
        {/* Skeleton */}
        {loading && (
          <>
            {new Array(3).fill(0).map((_, i) => <SkeletonPaymentMethod key={i}/>)}
          </>
        )}

        {!loading && paymentMethods.length > 0 ? paymentMethods.map((paymentMethod, index) => (
          <div
            className={`cursor-pointer px-2 py-3 border-[1px] rounded-lg hover:shadow-md transition ${methodSelected?.payment_code === paymentMethod.payment_code ? 'border-gray-600 border-2 shadow-md' : 'border-gray-200'}`}
            key={index}
            onClick={() => setMethodSelected(paymentMethod)}>
            <RenderPaymentComponent
              paymentMethod={paymentMethod}
              invoice={invoice}
              isActive={methodSelected?.payment_code === paymentMethod.payment_code}
            />
          </div>
        )) : (
          <div className="flex items-center justify-center gap-2 bg-yellow-50 text-yellow-500 py-3">
            <TriangleAlert className="text-yellow-500"/>
            <p>{'Payment methods not found'}</p>
          </div>
        )}
      </div>
    </div>
  );
}

function SkeletonPaymentMethod() {
  return (
    <div className="px-2 ">
      <div className="flex items-center space-x-4">
        <Skeleton className="h-12 w-12 rounded-md"/>
        <div className="space-y-2">
          <Skeleton className="h-4 w-[250px] md:w-[350px]"/>
          <Skeleton className="h-4 w-[200px] md:w-[300px]"/>
        </div>
      </div>
    </div>
  );
}
