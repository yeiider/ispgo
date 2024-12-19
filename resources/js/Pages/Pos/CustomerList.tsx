import {ICustomer} from "@/interfaces/ICustomer.ts";
import {Invoice} from "@/interfaces/Invoice.ts";
import React from "react";
import {__} from "@/translation.ts";
import {Accordion, AccordionContent, AccordionItem, AccordionTrigger} from "@/components/ui/accordion.tsx";

export default function CustomerList({customers, onInvoiceSelected, onCustomerSelected}: {
  customers: ICustomer[];
  onInvoiceSelected: React.Dispatch<React.SetStateAction<Invoice | null>>;
  onCustomerSelected: React.Dispatch<React.SetStateAction<ICustomer | null>>
}) {

  return (
    <div className="">
      <h1 className="text-[1.2rem]">{__('Customers')}</h1>
      {customers.length === 0 && <p className="text-sm">{__('No customers found')}</p>}
      <Accordion type="single" collapsible className="w-full">
        {customers.map(customer => (
          <AccordionItem value={`item-${customer.customer_id}`} key={customer.customer_id}>
            <AccordionTrigger
              className={`cursor-pointer flex justify-between items-center `}
              onClick={() => {
                onCustomerSelected(customer);
              }}>
              <span>{customer.full_name}</span>
            </AccordionTrigger>

            <AccordionContent>
              <div className="border-l-4 max-h-[40vh] overflow-y-auto">
                {customer?.invoices?.map((invoice, i) => (
                  <div key={i} className="cursor-pointer hover:bg-gray-50" onClick={() => onInvoiceSelected(invoice)}>
                    <ul className="px-2">
                      <li className="flex justify-between">
                        <dt className="min-w-40">Precio:</dt>
                        <dd>{invoice.total}</dd>
                      </li>
                      <li className="flex justify-between">
                        <dt className="min-w-40">Fecha de Pago:</dt>
                        <dd>{new Date(invoice.due_date).toLocaleDateString()}</dd>
                      </li>
                      <li className="flex justify-between">
                        <dt className="min-w-40">Increment ID:</dt>
                        <dd>{invoice.increment_id}</dd>
                      </li>
                    </ul>
                  </div>
                ))}
              </div>
            </AccordionContent>
          </AccordionItem>
        ))}
      </Accordion>
    </div>
  )
}
