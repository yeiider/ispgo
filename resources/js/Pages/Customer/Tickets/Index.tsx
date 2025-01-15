import CustomerLayout from "@/Layouts/CustomerLayout.tsx";
import {
  Table,
  TableBody,
  TableCaption,
  TableCell,
  TableFooter,
  TableHead,
  TableHeader,
  TableRow,
} from "@/components/ui/table"
import {__} from "@/translation.ts"
import {usePage, Link} from "@inertiajs/react"
import {
  Pagination,
  PaginationContent,
  PaginationEllipsis,
  PaginationItem,
  PaginationLink,
  PaginationNext,
  PaginationPrevious,
} from "@/components/ui/pagination"
import {Button} from "@/components/ui/button.tsx";
import {ListPlus} from "lucide-react";
import {useEffect} from "react";
import {toast} from "sonner";

type Props = {
  tickets: {
    current_page: number
    data: Ticket[]
    first_page_url: string
    from: any
    last_page: number
    last_page_url: string
    links: Link[]
    next_page_url: null | string
    path: string
    per_page: number
    prev_page_url: null | string
    to: any
    total: number
  }
  allowCustomerCreateTickets: Boolean;
  flash: {
    status: Response | null,
  }
}

interface Response {
  message: string;
  status_code: number;
  type: 'success' | 'error' | 'info' | 'warning';
}

interface Ticket {
  id: number
  title: string
  description: string
  resolution_notes: string
  contact_method: string
  created_at: string
  updated_at: string
  closed_at: string
}

interface Link {
  url?: string
  label: string
  active: boolean
}

export default function Index() {
  const {tickets, allowCustomerCreateTickets, flash} = usePage<Props>().props;

  useEffect(() => {
    if (flash.status) {
      const toastType = flash.status.type as keyof typeof toast;
      //@ts-ignore
      toast[toastType](flash.status.message, {
        position: 'top-right',
      });
    }
  })


  return (
    <CustomerLayout>
      <div className="flex flex-col gap-4 justify-between">
        <h1 className="text-3xl font-semibold text-slate-950">Tickets</h1>
        <div className="actions">
          {allowCustomerCreateTickets && (
            <Button asChild>
              <Link href="/customer-account/tickets/create"
                    className="">
                <span>Create tickets</span>
                <ListPlus/>
              </Link>
            </Button>
          )}
        </div>
        <div>
          <Table>
            <TableCaption>{__('A list of your recent tickets.')}</TableCaption>
            <TableHeader>
              <TableRow>
                <TableHead className="w-[100px]">#</TableHead>
                <TableHead>{__('Title')}</TableHead>
                <TableHead>{__('Description')}</TableHead>
                <TableHead>{__('Resolution notes')}</TableHead>
                <TableHead>{__('Contact Method')}</TableHead>
              </TableRow>
            </TableHeader>
            <TableBody>
              {tickets.data.map((ticket) => (
                <TableRow key={ticket.id}>
                  <TableCell className="font-medium">{ticket.id}</TableCell>
                  <TableCell>{ticket.title}</TableCell>
                  <TableCell>{ticket.description}</TableCell>
                  <TableCell>{ticket.resolution_notes}</TableCell>
                  <TableCell>{ticket.contact_method}</TableCell>
                </TableRow>
              ))}
            </TableBody>
            <TableFooter>
              <TableRow>
                <TableCell colSpan={5}>
                  {tickets.data.length && (
                    <Pagination>
                      <PaginationContent>

                        <PaginationPrevious
                          className={!tickets.prev_page_url ? 'disabled' : ''}
                          href={tickets.prev_page_url || '#'}
                        >
                          <span>{__('Previous')}</span>
                        </PaginationPrevious>
                        {tickets.links.map((link, index) => (
                          <PaginationItem key={index}>
                            {link.url ? (
                              <PaginationLink isActive={link.active} href={link.url}>{link.label}</PaginationLink>
                            ) : (
                              <PaginationEllipsis>{link.label}</PaginationEllipsis>
                            )}
                          </PaginationItem>
                        ))}

                        <PaginationNext
                          className={!tickets.prev_page_url ? 'disabled' : ''}
                          href={tickets.next_page_url || '#'}
                        >
                          <span>{__('Next')}</span>
                        </PaginationNext>
                      </PaginationContent>
                    </Pagination>
                  )}
                </TableCell>
              </TableRow>
            </TableFooter>
          </Table>
        </div>
      </div>
    </CustomerLayout>
  )
}
