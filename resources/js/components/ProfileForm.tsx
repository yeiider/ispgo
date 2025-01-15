import {z} from "zod";
import {__} from "@/translation.ts";
import {Form, FormControl, FormField, FormItem, FormLabel, FormMessage} from "@/components/ui/form.tsx";
import {Input} from "@/components/ui/input.tsx";
import {Select, SelectContent, SelectItem, SelectTrigger, SelectValue} from "@/components/ui/select.tsx";
import {Button} from "@/components/ui/button.tsx";
import {usePage, useForm as inertiaUseForm} from "@inertiajs/react";
import {useForm} from "react-hook-form";
import {zodResolver} from "@hookform/resolvers/zod";
import React from "react";

type Props = {
  documentTypes: {
    label: string,
    value: string
  }[],
  customer: {
    id: number
    first_name: string
    last_name: string
    date_of_birth: string
    phone_number: string
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
    date_of_birth_formatted: string
  },
  routeUpdateCustomer: string,
}

export default function ProfileForm() {

  const {documentTypes, customer} = usePage<Props>().props;


  const formSchema = z.object({
    first_name: z.string().min(3),
    last_name: z.string().min(3),
    document_type: z.string().min(1),
    identity_document: z.string().min(3),
    date_of_birth: z.string().min(3),
    email_address: z.string().email({
      message: __("The email must be a valid email address.")
    }),
  })

  const {data, put, setData} = inertiaUseForm({
    first_name: customer?.first_name || "",
    last_name: customer?.last_name || "",
    document_type: customer?.document_type || "",
    identity_document: customer?.identity_document || "",
    email_address: customer?.email_address || "",
    phone_number: customer?.phone_number || "",
    date_of_birth: customer?.date_of_birth_formatted || "",

  });

  const form = useForm<z.infer<typeof formSchema>>({
    resolver: zodResolver(formSchema),
    defaultValues: data,
  })


  const onSubmit = async () => {
    put(`/customer-account/customer/update/${customer?.id}`)
  }
  const handleInput = (e: React.ChangeEvent<HTMLInputElement>) => {
    setData({
      ...data,
      [e.target.name]: e.target.value
    })
  }

  return (
    <>
      <h3 className="text-2xl mt-3 md:mt-10 font-light">{__('Update your account information')}</h3>
      <Form {...form}>
        <form onSubmit={form.handleSubmit(onSubmit)} className="space-y-5">
          <FormField
            control={form.control}
            name="first_name"
            render={({field}) => (
              <FormItem>
                <FormLabel htmlFor="first_name">{__('First Name')}</FormLabel>
                <FormControl>
                  <Input type="text" placeholder="Joe" {...field} onInput={handleInput}/>
                </FormControl>
                <FormMessage/>
              </FormItem>
            )}
          />

          <FormField
            control={form.control}
            name="last_name"
            render={({field}) => (
              <FormItem>
                <FormLabel htmlFor="last_name">{__('Last Name')}</FormLabel>
                <FormControl>
                  <Input type="text" placeholder="Millan" {...field} onInput={handleInput}/>
                </FormControl>
                <FormMessage/>
              </FormItem>
            )}
          />

          <FormField
            control={form.control}
            name="document_type"
            render={({field}) => (
              <FormItem>
                <FormLabel htmlFor="document_type">{__('Document Type')}</FormLabel>
                <Select name="document_type" value={field.value} onValueChange={(value) => {
                  field.onChange(value);
                  setData({
                    ...data,
                    document_type: value
                  })
                }} defaultValue={field.value}>
                  <FormControl>
                    <SelectTrigger>
                      <SelectValue placeholder="Select a document type"/>
                    </SelectTrigger>
                  </FormControl>
                  <SelectContent>
                    {documentTypes.map((item) => (
                      <SelectItem key={item.value} value={item.value}>{__(item.label)}</SelectItem>
                    ))}
                  </SelectContent>
                </Select>
                <FormMessage/>
              </FormItem>
            )}
          />

          <FormField
            control={form.control}
            name="identity_document"
            render={({field}) => (
              <FormItem>
                <FormLabel htmlFor="identity_document">{__('Identity Document')}</FormLabel>
                <FormControl>
                  <Input type="text" placeholder="1234567" {...field} onInput={handleInput}/>
                </FormControl>
                <FormMessage/>
              </FormItem>
            )}
          />
          <FormField
            control={form.control}
            name="date_of_birth"
            render={({field}) => (
              <FormItem>
                <FormLabel htmlFor="identity_document">{__('Date Of Birth')}</FormLabel>
                <FormControl>
                  <Input id="date_of_birth" type="date" {...field} onInput={handleInput}/>
                </FormControl>
                <FormMessage/>
              </FormItem>
            )}
          />


          <FormField
            control={form.control}
            name="email_address"
            render={({field}) => (
              <FormItem>
                <FormLabel htmlFor="email_address">{__('Email')}</FormLabel>
                <FormControl>
                  <Input type="email" placeholder="m@example.com" {...field} onInput={handleInput}/>
                </FormControl>
                <FormMessage/>
              </FormItem>
            )}
          />
          <Button type="submit" className="w-full md:w-auto">
            {__('Save Changes')}
          </Button>
        </form>
      </Form>
    </>
  )
}
