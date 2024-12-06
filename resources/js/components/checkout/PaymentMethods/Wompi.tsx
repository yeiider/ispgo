import {PaymentMethod} from "@/interfaces/PaymentMethod.ts";
import {Invoice} from "@/interfaces/Invoice.ts";
import {__} from "@/translation.ts";
import {CreditCard} from "lucide-react"

interface Props {
  paymentMethod: PaymentMethod,
  invoice: Invoice,
}

export default function Wompi({paymentMethod}: Props) {

  const handleClick = () => {

  }

  return (
    <div className="flex gap-2">
      <div>
        <img src={paymentMethod.image} alt="" className="w-14"/>
      </div>
      <div className="w-full flex justify-end">
        <button className="flex items-center gap-2" onClick={handleClick}>
          <CreditCard size={20}/>
          <span>{__('Pay')}</span>
        </button>
      </div>
    </div>
  )
}
