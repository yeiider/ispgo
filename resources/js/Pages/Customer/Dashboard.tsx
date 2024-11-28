import CustomerLayout from "@/Layouts/CustomerLayout.tsx";
import {Link, usePage} from "@inertiajs/react";
import {__} from '@/translation.ts'
import {PencilLine} from "lucide-react";
import {Button} from "@/components/ui/button.tsx";

type Props = {
  customer: {
    id: number
    first_name: string
    last_name: string
    date_of_birth: any
    phone_number: any
    email_address: string
    document_type: string
    identity_document: string
    customer_status: string
    additional_notes: any
    created_by: any
    updated_by: any
    password_reset_token: any
    password_reset_token_expiration: any
    created_at: string
    updated_at: string
    date_of_birth_formatted: string,
  },
  route_edit_customer: string
}
export default function Dashboard() {
  const {customer, route_edit_customer} = usePage<Props>().props;

  const customerData = [
    {
      label: __('First Name'),
      value: customer.first_name
    },
    {
      label: __('Last Name'),
      value: customer.last_name
    },
    {
      label: __('Date of Birth'),
      value: customer.date_of_birth ? customer.date_of_birth_formatted : '—'
    },
    {
      label: __('Phone Number'),
      value: customer.phone_number ? customer.phone_number : '—',
    },
    {
      label: __('Email Address'),
      value: customer.email_address
    },
    {
      label: __('Document Type'),
      value: customer.document_type
    },
    {
      label: __('Identity Document'),
      value: customer.identity_document
    }
  ]

  return (
    <CustomerLayout>
      <h1 className="text-3xl font-semibold text-slate-950">{__('My Account')}</h1>
      <h2 className="text-2xl mt-5 md:mt-10 font-light">{__('Account information')}</h2>
      <hr className="my-2 border-gray-300"/>

      <div className="flex-1 overflow-auto p-6">
        <dl className="grid grid-cols-1 gap-4 sm:grid-cols-2">
          {customerData.map((item, index) => (
            <div key={index}>
              <dt className="text-sm font-medium text-gray-600">{item.label}</dt>
              <dd className="text-sm text-gray-900 font-semibold">
                {item.value}
              </dd>
            </div>
          ))}
        </dl>
        <div className="mt-6">
          <Button asChild>
            <Link href={route_edit_customer}>
              <span>{__('Edit')}</span>
              <PencilLine/>
            </Link>
          </Button>
        </div>
      </div>
    </CustomerLayout>
  )
}
