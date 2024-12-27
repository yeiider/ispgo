import {useEffect, useState} from "react";
import axios from "axios";
import {Table, TableBody, TableCaption, TableCell, TableHead, TableHeader, TableRow} from "@/components/ui/table.tsx";
import {__} from "@/translation.ts";
import {Invoice} from "@/interfaces/Invoice.ts";
import RenderInvoiceStatus from "@/components/RenderInvoiceStatus.tsx";

export default function InvoiceListComponent() {
  const [invoices, setInvoices] = useState<Invoice[]>([]);
  useEffect(() => {
    axios.get('/invoice/find-by-box')
      .then((response) => {
        setInvoices(response.data.invoices);
      })
      .catch((error) => {
        console.error("Error fetching invoices: ", error);
      });
  }, [])
  console.log(invoices)
  return (
    <div>
      <Table>
        <TableCaption>{__('A list of your recent invoices.')}</TableCaption>
        <TableHeader>
          <TableRow>
            <TableHead className="w-[100px]">#</TableHead>
            <TableHead>{__('Increment Id')}</TableHead>
            <TableHead>{__('Status')}</TableHead>
            <TableHead>{__('Notes')}</TableHead>
            <TableHead className="text-right">{__('Subtotal')}</TableHead>
            <TableHead className="text-right">{__('Discount')}</TableHead>
            <TableHead className="text-right">{__('Total')}</TableHead>
            <TableHead>{__('Created At')}</TableHead>
          </TableRow>
        </TableHeader>
        <TableBody>
          {invoices.map((invoice) => (
            <TableRow key={invoice.id}>
              <TableCell className="font-medium">{invoice.id}</TableCell>
              <TableCell>{invoice.increment_id}</TableCell>
              <TableCell>
                <RenderInvoiceStatus status={invoice.status}/>
              </TableCell>
              <TableCell>{invoice.notes}</TableCell>
              <TableCell className="text-right font-medium">{invoice.subtotal}</TableCell>
              <TableCell className="text-right font-medium">{invoice.discount}</TableCell>
              <TableCell className="text-right font-medium">{invoice.total}</TableCell>
              <TableCell>{invoice.created_at}</TableCell>
            </TableRow>
          ))}
        </TableBody>
      </Table>
    </div>
  )
}
