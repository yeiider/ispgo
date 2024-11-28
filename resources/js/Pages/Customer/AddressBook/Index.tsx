import CustomerLayout from "@/Layouts/CustomerLayout.tsx";
import {usePage} from "@inertiajs/react";
import {IAddressBook} from "@/interfaces/IAddressBook.ts";
import {__} from "@/translation.ts";
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
import {
  Pagination,
  PaginationContent, PaginationEllipsis,
  PaginationItem,
  PaginationLink, PaginationNext,
  PaginationPrevious
} from "@/components/ui/pagination.tsx";

type Props = {
  address_book: IAddressBook
}
export default function Index() {
  const {address_book} = usePage<Props>().props;
  console.log(address_book)
  return (
    <CustomerLayout>
      <div className="flex flex-col gap-4 justify-between">
        <h1 className="text-3xl font-semibold text-slate-950">{__('Address Book')}</h1>
        <div>
          <Table>
            <TableCaption>{__('A list of your recent tickets.')}</TableCaption>
            <TableHeader>
              <TableRow>
                <TableHead className="w-[100px]">#</TableHead>
                <TableHead>{__('Address')}</TableHead>
                <TableHead>{__('City')}</TableHead>
                <TableHead>{__('Country')}</TableHead>
                <TableHead>{__('State Province')}</TableHead>
                <TableHead>{__('Postal Code')}</TableHead>
              </TableRow>
            </TableHeader>
            <TableBody>
              {address_book.data.map((item) => (
                <TableRow key={item.id}>
                  <TableCell className="font-medium">{item.id}</TableCell>
                  <TableCell>{item.address}</TableCell>
                  <TableCell>{item.city}</TableCell>
                  <TableCell>{item.country}</TableCell>
                  <TableCell>{item.state_province}</TableCell>
                  <TableCell>{item.postal_code}</TableCell>
                </TableRow>
              ))}
            </TableBody>
            <TableFooter>
              <TableRow>
                <TableCell colSpan={5}>
                  <Pagination>
                    <PaginationContent>

                      <PaginationPrevious
                        className={!address_book.prev_page_url ? 'disabled' : ''}
                        href={address_book.prev_page_url || '#'}
                      >
                        <span>{__('Previous')}</span>
                      </PaginationPrevious>
                      {address_book.links.map((link, index) => (
                        <PaginationItem key={index}>
                          {link.url ? (
                            <PaginationLink isActive={link.active} href={link.url}>{link.label}</PaginationLink>
                          ) : (
                            <PaginationEllipsis>{link.label}</PaginationEllipsis>
                          )}
                        </PaginationItem>
                      ))}
                      <PaginationNext
                        className={!address_book.prev_page_url ? 'disabled' : ''}
                        href={address_book.next_page_url || '#'}
                      >
                        <span>{__('Next')}</span>
                      </PaginationNext>
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
