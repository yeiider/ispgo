import {
  Card,
  CardContent,
  CardDescription,
  CardHeader,
  CardTitle,
} from "@/components/ui/card"
import {__} from "@/translation.ts";
import {Button} from "@/components/ui/button.tsx";
import {
  Form,
  FormControl,
  FormField,
  FormItem,
  FormMessage,
} from "@/components/ui/form"
import {Input} from "@/components/ui/input"
import {z} from "zod"
import {zodResolver} from "@hookform/resolvers/zod"
import {useForm} from "react-hook-form"
import {ChevronRight, Search} from "lucide-react";
import React, {useEffect, useState} from "react";
import {useForm as inertiaUseForm} from "@inertiajs/react";
import axios from "axios";
import {Invoice} from "@/interfaces/Invoice.ts";
import RenderInvoice from "@/components/RenderInvoice.tsx";
import InvoiceSkeleton from "@/components/checkout/InvoiceSkeleton.tsx";
import {toast} from "sonner"

interface Props {
  navigation: (step: number) => void;
}

export default function Reference({navigation}: Props) {
  const [invoice, setInvoice] = useState<Invoice | null>(null);
  const [loading, setLoading] = useState<boolean>(false);

  const formSchema = z.object({
    reference: z.string().min(2, {
      message: "Reference must be at least 2 characters.",
    }),
  })

  const {data, setData} = inertiaUseForm({
    reference: "",
  })


  const form = useForm<z.infer<typeof formSchema>>({
    resolver: zodResolver(formSchema),
    defaultValues: data,
  })

  async function onSubmit(values: z.infer<typeof formSchema>) {
    // Do something with the form values.
    // ✅ This will be type-safe and validated.
    setLoading(true)
    try {
      const response = await axios.get('/invoice/search', {
        params: {
          input: values.reference,
        },
      });
      setInvoice(response.data.invoice);
    } catch (e: any) {
      toast.error(e.response?.data?.message || 'Error occurred', {
        classNames: {
          toast: "bg-red-100",
          title: "text-red-500",
          icon: "text-red-500",
        }
      })
      setInvoice(null)
      localStorage.removeItem('invoice')
    } finally {
      setLoading(false)
    }
  }

  const handleInput = (e: React.ChangeEvent<HTMLInputElement>) => {
    setData({
      ...data,
      [e.target.name]: e.target.value
    })
  }

  useEffect(() => {
    const invoice = JSON.parse(window.localStorage.getItem('invoice') || '{}')

    if (Object.keys(invoice).length > 0) {
      setInvoice(invoice);
    }

  }, [])

  const handleSubmit = () => {
    navigation(1);
  }

  const continueTitle = __('Continue');

  return (
    <div>
      <Card>
        <CardHeader>
          <CardTitle>{__('Payment reference')}</CardTitle>
          <CardDescription>{__('You can search by your ID or payment reference')}</CardDescription>
        </CardHeader>
        <CardContent>
          <Form {...form}>
            <form onSubmit={form.handleSubmit(onSubmit)} className="grid grid-cols-3 item-center gap-2">
              <div className="col-span-2">
                <FormField
                  control={form.control}
                  name="reference"
                  render={({field}) => (
                    <FormItem>
                      <FormControl>
                        <Input placeholder="Ej: 1234567890" {...field} onInput={handleInput}/>
                      </FormControl>
                      <FormMessage/>
                    </FormItem>
                  )}
                />
              </div>
              <Button disabled={loading} className="col-span-1 bg-[#0ea5e9]" type="submit">
                <Search/>
                <span>{__('Search')}</span>
              </Button>
            </form>
          </Form>
          <div className="mt-5">
            {loading && <InvoiceSkeleton/>}
            {invoice && !loading && (
              <RenderInvoice invoice={invoice}>
                {invoice.status !== 'paid' && (
                  <Button className="bg-[#0ea5e9]" onClick={handleSubmit} type="button">
                    <span>{continueTitle}</span>
                    <ChevronRight/>
                  </Button>
                )}
              </RenderInvoice>
            )}
          </div>
        </CardContent>
      </Card>
    </div>
  )
}
