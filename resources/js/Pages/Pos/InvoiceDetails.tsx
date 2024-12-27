import {Invoice} from "@/interfaces/Invoice.ts";
import React, {useState} from "react";
import RenderInvoice from "@/components/RenderInvoice.tsx";
import {__} from "@/translation.ts";
import {DollarSign, LoaderCircle, TriangleAlert} from "lucide-react";
import {Button} from "@/components/ui/button.tsx";
import {Dialog, DialogContent, DialogFooter, DialogHeader, DialogTitle} from "@/components/ui/dialog.tsx";
import axios from "axios";
import {ICustomer} from "@/interfaces/ICustomer.ts";
import {usePage} from "@inertiajs/react";
import {TodayBox} from "@/Pages/Pos/MainContentPos.tsx";
import {toast} from "sonner";

type Props = {
  config: {
    currency: string
    currencySymbol: string
    todayBox: TodayBox
  }
}

export default function InvoiceDetails({onSelectedInvoice, invoice, customer}: {
  onSelectedInvoice: React.Dispatch<React.SetStateAction<Invoice | null>>;
  invoice: Invoice;
  customer: ICustomer | null;
}) {
  const [open, setOpen] = useState(false);
  const [loading, setLoading] = useState(false);
  const {config} = usePage<Props>().props

  const handleConfirm = () => {
    setOpen(false);
    console.log(invoice, onSelectedInvoice)
    const editableInvoice = {
      paymentMethod: 'cash',
      userName: customer?.full_name || '',
      paymentDate: new Date().toISOString().substring(0, 16),
      dueDate: invoice.due_date ? invoice.due_date.substring(0, 10) : '',
      subtotal: parseFloat(invoice.subtotal || '0').toFixed(2),
      tax: parseFloat(invoice.tax || '0').toFixed(2),
      totalToPay: parseFloat(invoice.total || '0').toFixed(2),
      paymentReference: invoice.increment_id || '',
      todaytBox: config.todayBox,
      printOption: '',
      actionOnPayment: '',
      note: ''
    }

    setLoading(true)
    console.log(editableInvoice)


    axios.post('/invoice/apply-payment', {
      ...editableInvoice
    }).then(res => {
      console.log(res)
      invoice.status = 'paid'
      onSelectedInvoice(invoice)

    }).catch(err => {
      toast.error(err.response.data.message, {
        classNames: {
          icon: 'text-red-500',
          title: 'text-red-500'
        }
      })
    }).finally(() => {
      setLoading(false)
    })
  }
  return (
    <>
      <RenderInvoice invoice={invoice}>
        <Button disabled={!invoice} type="button" onClick={() => setOpen(true)}>
          {!loading ? (
            <>
              <span>{__('Pay')}</span>
              <DollarSign/>
            </>
          ) : <LoaderCircle className="animate-spin"/>}
        </Button>
      </RenderInvoice>
      <Dialog open={open} onOpenChange={setOpen}>
        <DialogContent className="max-w-[400px]">
          <DialogHeader className="flex flex-col items-center gap-2">
            <TriangleAlert size={40}/>
            <DialogTitle>{__('Are you sure to pay?')}</DialogTitle>
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
