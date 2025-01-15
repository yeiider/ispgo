import CustomerLayout from "@/Layouts/CustomerLayout.tsx";
import {Link, usePage} from "@inertiajs/react";
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
import {
  AlertDialog,
  AlertDialogAction,
  AlertDialogCancel,
  AlertDialogContent,
  AlertDialogDescription,
  AlertDialogFooter,
  AlertDialogHeader,
  AlertDialogTitle
} from "@/components/ui/alert-dialog"
import {useEffect, useState} from "react";
import {Button} from "@/components/ui/button.tsx";
import {PencilLine, Plus, Trash, TriangleAlert} from "lucide-react";
import {toast} from "sonner";
import {useForm} from "@inertiajs/react";

type Props = {
  address_book: IAddressBook,
  addressCreateUrl: string,
  flash: {
    status: string | null,
  }
}
export default function Index() {
  const {address_book, flash} = usePage<Props>().props;
  const [isOpen, setIsOpen] = useState(false);
  const [id, setId] = useState<number | null>(null);
  const form = useForm();

  const deleteAddress = () => {
    if (typeof id == null) {
      return;
    }
    form.delete(`/customer-account/address-book/delete/${id}`)
    console.log(id)
  }
  useEffect(() => {
    if (flash.status) {
      toast.success(flash.status, {
        classNames: {
          title: "text-green-500",
          icon: "text-green-500",
        }
      });
    }
    return;
  }, []);

  return (
    <CustomerLayout>
      <div className="flex flex-col gap-4 justify-between">
        <h1 className="text-3xl font-semibold text-slate-950">{__('Address Book')}</h1>
        <div className="flex justify-end">
          <Button asChild>
            <Link href={"/customer-account/address-book/create"}>
              <Plus/>
              <span>{__('Create Address')}</span>
            </Link>
          </Button>
        </div>
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
                <TableHead>{__('Actions')}</TableHead>
              </TableRow>
            </TableHeader>
            <TableBody>
              {address_book.data.map((item) => (
                <TableRow key={item.id}>
                  <TableCell className="font-medium">{item.id}</TableCell>
                  <TableCell className="underline">{item.address}</TableCell>
                  <TableCell>{item.city}</TableCell>
                  <TableCell>{item.country}</TableCell>
                  <TableCell>{item.state_province}</TableCell>
                  <TableCell>{item.postal_code}</TableCell>
                  <TableCell className="flex items-center gap-4">
                    <Link href={`/customer-account/address-book/edit/${item.id}`}
                          className="text-yellow-500 flex items-center gap-2">
                      <PencilLine className="text-yellow-500"/>
                      <span>{__('Edit')}</span>
                    </Link>
                    {address_book.data.length > 1 && (
                      <Button variant="link" className="font-normal" onClick={() => {
                        setIsOpen(true);
                        setId(item.id);
                      }}>
                        <Trash className="text-red-500"/>
                        <span className="text-red-500">{__('Delete')}</span>
                      </Button>
                    )}
                  </TableCell>
                </TableRow>
              ))}
            </TableBody>
            <TableFooter>
              <TableRow>
                <TableCell colSpan={7}>
                  {address_book.data.length && (
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
                  )}
                </TableCell>
              </TableRow>
            </TableFooter>
          </Table>


          <AlertDialog open={isOpen} onOpenChange={setIsOpen}>
            <AlertDialogContent>
              <AlertDialogHeader className="flex flex-col items-center">
                <TriangleAlert/>
                <AlertDialogTitle>{__('Are you absolutely sure?')}</AlertDialogTitle>
                <AlertDialogDescription className="text-center">
                  {__('This action cannot be undone. This will permanently delete your address and remove your data from our servers.')}
                </AlertDialogDescription>
              </AlertDialogHeader>
              <AlertDialogFooter>
                <div className="flex items-center gap-4 w-full justify-center">
                  <AlertDialogAction onClick={deleteAddress}>Confirm</AlertDialogAction>
                  <AlertDialogCancel>Cancel</AlertDialogCancel>
                </div>
              </AlertDialogFooter>
            </AlertDialogContent>
          </AlertDialog>

        </div>
      </div>
    </CustomerLayout>
  )
}
