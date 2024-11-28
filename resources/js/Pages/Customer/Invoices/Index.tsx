import CustomerLayout from "@/Layouts/CustomerLayout.tsx";
import {usePage} from "@inertiajs/react";
import {
  Table,
  TableBody,
  TableCaption,
  TableCell,
  TableFooter,
  TableHead,
  TableHeader,
  TableRow
} from "@/components/ui/table.tsx";
import {__} from "@/translation.ts";
import {
  Pagination,
  PaginationContent, PaginationEllipsis,
  PaginationItem,
  PaginationLink, PaginationNext,
  PaginationPrevious
} from "@/components/ui/pagination.tsx";

type Props = {
  invoices: {
    current_page: number
    data: Daum[]
    first_page_url: string
    from: number
    last_page: number
    last_page_url: string
    links: Link[]
    next_page_url: any
    path: string
    per_page: number
    prev_page_url: any
    to: number
    total: number
  }
}

interface Daum {
  id: number
  increment_id: string
  customer_id: number
  due_date: string
  subtotal: string
  total: string
  amount: string
  discount: string
  outstanding_balance: string
  status: string
  payment_method: any
  notes: string
  created_at: string
  updated_at: string
  service: Service
  user: User
}

export interface Service {
  id: number
  router_id: number
  customer_id: number
  plan_id: number
  service_ip: string
  username_router: string
  password_router: string
  service_status: string
  activation_date: any
  deactivation_date: string
  bandwidth: number
  mac_address: string
  installation_date: any
  service_notes: any
  contract_id: number
  support_contact: any
  service_location: string
  service_type: string
  static_ip: number
  data_limit: any
  last_maintenance: string
  billing_cycle: any
  service_priority: string
  assigned_technician: number
  service_contract: any
  created_by: any
  updated_by: any
  created_at: string
  updated_at: string
}

interface User {
  id: number
  name: string
  email: string
  email_verified_at: any
  two_factor_secret: any
  two_factor_recovery_codes: any
  two_factor_confirmed_at: any
  telephone: any
  created_by: any
  updated_by: any
  created_at: string
  updated_at: string
}

interface Link {
  url?: string
  label: string
  active: boolean
}


export default function Index() {
  const {invoices} = usePage<Props>().props;
  const renderStatus = (status: string) => {
    switch (status) {
      case 'pending':
        return <span>{__(status)}</span>;
      case 'unpaid':
        return <span className="px-4 py-2 rounded-lg bg-red-50 text-red-500">{__(status)}</span>;
      case 'paid':
        return <span className="px-4 py-2 rounded-lg bg-green-50 text-green-500">{__(status)}</span>;
      case 'canceled':
        return <span className="px-4 py-2 rounded-lg bg-gray-50 text-gray-500">{__(status)}</span>;
      case 'overdue':
        return <span className="px-4 py-2 rounded-lg bg-red-50 text-red-500">{__(status)}</span>;
    }
  }

  return (
    <CustomerLayout>
      <div className="flex flex-col gap-4 justify-between">
        <h1 className="text-3xl font-semibold text-slate-950">{__('Invoices')}</h1>
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
              {invoices.data.map((invoice) => (
                <TableRow key={invoice.id}>
                  <TableCell className="font-medium">{invoice.id}</TableCell>
                  <TableCell>{invoice.increment_id}</TableCell>
                  <TableCell>{renderStatus(invoice.status)}</TableCell>
                  <TableCell>{invoice.notes}</TableCell>
                  <TableCell className="text-right font-medium">{invoice.subtotal}</TableCell>
                  <TableCell className="text-right font-medium">{invoice.discount}</TableCell>
                  <TableCell className="text-right font-medium">{invoice.total}</TableCell>
                  <TableCell>{invoice.created_at}</TableCell>
                </TableRow>
              ))}
            </TableBody>
            <TableFooter>
              <TableRow>
                <TableCell colSpan={8}>
                  <Pagination>
                    <PaginationContent>
                      <PaginationPrevious
                        className={!invoices.prev_page_url ? 'disabled' : ''}
                        href={invoices.prev_page_url || '#'}
                      />
                      {invoices.links.map((link, index) => (
                        <PaginationItem key={index}>
                          {link.url ? (
                            <PaginationLink isActive={link.active} href={link.url}>{link.label}</PaginationLink>
                          ) : (
                            <PaginationEllipsis>{link.label}</PaginationEllipsis>
                          )}
                        </PaginationItem>
                      ))}
                      <PaginationNext
                        className={!invoices.prev_page_url ? 'disabled' : ''}
                        href={invoices.next_page_url || '#'}
                      />
                    </PaginationContent>
                  </Pagination>
                </TableCell>
              </TableRow>
            </TableFooter>
          </Table>
        </div>
      </div>
    </CustomerLayout>
  )
}
