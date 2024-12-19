import {Invoice} from "@/interfaces/Invoice.ts";
import React from "react";
import RenderInvoice from "@/components/RenderInvoice.tsx";
import {__} from "@/translation.ts";
import {DollarSign} from "lucide-react";
import {Button} from "@/components/ui/button.tsx";

export default function InvoiceDetails({onSelectedInvoice, invoice}: {
  onSelectedInvoice:  React.Dispatch<React.SetStateAction<Invoice | null>>;
  invoice: Invoice;
}) {

  console.log(invoice)
  return (
    <>
      <RenderInvoice invoice={invoice} >
        <Button disabled={!invoice} type="button">
          <span>{__('Pay')}</span>
          <DollarSign />
        </Button>
      </RenderInvoice>
    </>
  )
}
