import {PaymentMethod} from "@/interfaces/PaymentMethod.ts";
import {Invoice} from "@/interfaces/Invoice.ts";
import {__} from "@/translation.ts";
import {CreditCard, LoaderCircle, TriangleAlert} from "lucide-react"
import {useState} from "react";
import {
  Dialog,
  DialogContent,
  DialogFooter,
  DialogHeader,
  DialogTitle,
} from "@/components/ui/dialog"
import {Button} from "@/components/ui/button.tsx";
import axios from "axios";
import {toast} from "sonner";
import {usePage} from "@inertiajs/react";

interface Props {
  paymentMethod: PaymentMethod,
  invoice: Invoice,
}

type Config = {
  config: {
    companyEmail: string
    companyPhone: string
    currency: string
    currencySymbol: string
  }
}
export default function Wompi({paymentMethod, invoice}: Props) {

  const [open, setOpen] = useState(false);
  const [loading, setLoading] = useState(false);

  const {config} = usePage<Config>().props;


  const handleClick = () => {
    setOpen(true);
  }


  const calculateAmountInCents = (price: string) => {
    return parseInt(parseInt(price).toString() + '00');
  }

  const getSignature = async () => {
    return await axios.post('/payment/wompi/signature', {
      reference: invoice.increment_id,
      amount: calculateAmountInCents(invoice.total)
    });
  }

  const handleConfirm = () => {
    setLoading(true)
    setOpen(false);
    getSignature().then(response => {
      window.localStorage.removeItem('invoice');
      const checkout = new WidgetCheckout({
        currency: config.currency,
        amountInCents: calculateAmountInCents(invoice.total),
        reference: invoice.increment_id,
        publicKey: paymentMethod.public_key,
        signature: {integrity: response.data.signature},
        redirectUrl: paymentMethod.confirmation_url, // Opcional

        customerData: { // Opcional
          email: invoice.customer.email_address,
          fullName: invoice.customer_name,
          phoneNumber: invoice.customer.phone_number,
          phoneNumberPrefix: '+57',
          legalId: invoice.customer.identity_document,
          legalIdType: invoice.customer.document_type
        },
        shippingAddress: { // Opcional
          addressLine1: invoice.address.address,
          city: invoice.address.city,
          phoneNumber: invoice.customer.phone_number,
          region: invoice.address.state_province,
          country: invoice.address.country
        }
      });
      checkout.open((result: { transaction: { id: string } }) => {
        const transaction = result.transaction;
        window.location.href = `${paymentMethod.confirmation_url}?id=${transaction.id}`
      });
    })
      .catch(e => {
        toast.error(e.response?.data?.message || 'Error occurred', {
          classNames: {
            icon: 'text-red-500',
            title: 'text-red-500'
          }
        })
      })
      .finally(() => setLoading(false))
  }

  return (
    <>
      <div className="flex items-center gap-2">
        <div>
          <img src={paymentMethod.image} alt="" className="w-14"/>
        </div>
        <div className="w-full flex justify-end">
          <button
            disabled={loading}
            className="flex items-center gap-2 border-[1px] border-blue-100 text-blue-500 bg-blue-50 py-2 px-3 rounded-lg hover:bg-blue-100"
            onClick={handleClick}>
            {loading && <LoaderCircle className="animate-spin" size={20}/>}
            <CreditCard size={20}/>
            <span>{__('Pay')}</span>
          </button>
        </div>
      </div>
      <Dialog open={open} onOpenChange={setOpen}>
        <DialogContent className="max-w-[400px]">
          <DialogHeader className="flex flex-col items-center gap-2">
            <TriangleAlert size={40}/>
            <DialogTitle>{`${__('Are you sure to pay')} ${paymentMethod.payment_code}?`}</DialogTitle>
          </DialogHeader>
          <DialogFooter className="mt-3">
            <div className="w-full flex justify-center gap-3">
              <Button
                className="px-8 bg-red-50 text-red-500 border-[1px] border-red-100 rounded-lg hover:bg-red-100 hover:underline"
                type="button" onClick={() => setOpen(false)}>{__('Cancel')}</Button>
              <Button
                className="px-8 bg-green-50 text-green-500 border-[1px] border-green-100 rounded-lg hover:bg-green-100 hover:underline"
                type="button" onClick={handleConfirm}>{__('Confirm')}</Button>
            </div>
          </DialogFooter>
        </DialogContent>
      </Dialog>
    </>
  )
}
