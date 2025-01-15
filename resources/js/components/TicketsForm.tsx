import {z} from "zod";
import {useForm as inertiaUseForm} from "@inertiajs/react";
import {useForm} from "react-hook-form";
import {zodResolver} from "@hookform/resolvers/zod";
import {Form, FormControl, FormField, FormItem, FormLabel, FormMessage} from "@/components/ui/form.tsx";
import {__} from "@/translation.ts";
import {Select, SelectContent, SelectItem, SelectTrigger, SelectValue} from "@/components/ui/select.tsx";
import {Service} from "@/interfaces/Service.ts";
import {Input} from "@/components/ui/input.tsx";
import {Option} from "@/interfaces/Option.ts";
import {Textarea} from "@/components/ui/textarea.tsx";
import React, {FormEvent, useState} from "react";
import {Loader2, Server} from "lucide-react";
import {Button} from "@/components/ui/button.tsx";
import {FileType} from "@/types/FileType.ts";
import FilePreview from "@/components/FilePreview.tsx";

export default function TicketsForm({ticket, services, issueTypes}: {
  ticket?: any;
  services: Service[];
  issueTypes: Option[]
}) {
  const formSchema = z.object({
    service_id: z.string().min(1) || z.number().min(1),
    issue_type: z.string().min(1),
    title: z.string().min(3),
    description: z.string().min(5),
    attachments: z.string(),
    contact_method: z.string().min(4),
  })

  const {data, setData, post, put, processing} = inertiaUseForm({
    service_id: ticket?.service_id || "",
    issue_type: ticket?.issue_type || "",
    title: ticket?.title || "",
    description: ticket?.description || "",
    attachments: ticket?.attachments || "",
    contact_method: ticket?.contact_method || "",
  })


  const form = useForm<z.infer<typeof formSchema>>({
    resolver: zodResolver(formSchema),
    defaultValues: data,
  })


  const onSubmit = (values: z.infer<typeof formSchema>) => {
    console.log(values)

    if (typeof ticket !== "undefined") {
      put(`/customer-account/tickets/update/${ticket.id}`, {
        headers: {
          'Content-Type': 'multipart/form-data'
        }
      })
    } else {
      post("/customer-account/tickets/store", {
        headers: {
          'Content-Type': 'multipart/form-data'
        }
      })
    }
  }


  const [file, setFile] = useState<FileType | null>(null);

  const previewField = (e: FormEvent<HTMLInputElement>) => {
    const file = e.currentTarget.files?.[0];
    if (file) {
      const reader = new FileReader();
      reader.onload = (e) => {
        const result = e.target?.result;
        if (result) {
          setFile({
            url: reader.result as string,
            type: file.type,
            name: file.name,
            extension: file.name.split('.').pop() || '',
          });
        }
      }
      reader.readAsDataURL(file);
    } else {
      setFile(null);
    }
  }

  const handlerRemoveFile = () => {
    form.setValue("attachments", "")
    setFile(null);
  }

  const handleInput = (e: React.ChangeEvent<HTMLInputElement>) => {
    setData({
      ...data,
      [e.target.name]: e.target.value
    })
  }

  return (
    <Form {...form}>
      <form onSubmit={form.handleSubmit(onSubmit)} className=" max-w-5xl" encType="multipart/form-data">
        <div className="grid grid-cols-4 gap-4">
          <div className="col-span-2">
            <FormField
              control={form.control}
              name="service_id"
              render={({field}) => (
                <FormItem>
                  <FormLabel>{__('Service')}</FormLabel>
                  <Select name="service_id" onValueChange={(value) => {
                    field.onChange(value)
                    setData({
                      ...data,
                      service_id: value
                    })
                  }} defaultValue={field.value}>
                    <FormControl>
                      <SelectTrigger>
                        <SelectValue placeholder={__('Select a service')}/>
                      </SelectTrigger>
                    </FormControl>

                    <SelectContent>
                      {services.map(service => (
                        <SelectItem
                          key={service.id}
                          value={service.id.toString()}>
                          {`${service.service_ip} - ${service.username_router}`}
                        </SelectItem>
                      ))}
                    </SelectContent>
                  </Select>
                  <FormMessage/>
                </FormItem>
              )}
            />
          </div>
          <div className="col-span-2">
            <FormField
              control={form.control}
              name="issue_type"
              render={({field}) => (
                <FormItem>
                  <FormLabel>{__('Issue Type')}</FormLabel>
                  <Select onValueChange={(value) => {
                    field.onChange(value)
                    setData({
                      ...data,
                      issue_type: value
                    })
                  }} defaultValue={field.value}>
                    <FormControl>
                      <SelectTrigger>
                        <SelectValue placeholder={__('Select a Issue Type')}/>
                      </SelectTrigger>
                    </FormControl>

                    <SelectContent>
                      {issueTypes.map(option => (
                        <SelectItem
                          key={option.value}
                          value={option.value}>
                          {option.label}
                        </SelectItem>
                      ))}
                    </SelectContent>
                  </Select>
                  <FormMessage/>
                </FormItem>
              )}
            />
          </div>
          <div className="col-span-3">
            <FormField
              control={form.control}
              name="title"
              render={({field}) => (
                <FormItem>
                  <FormLabel htmlFor="title">{__('Title')}</FormLabel>
                  <FormControl>
                    <Input type="text" placeholder="Lorem #2" {...field} onInput={handleInput}/>
                  </FormControl>
                  <FormMessage/>
                </FormItem>
              )}
            />
          </div>
          <div className="col-span-4">
            <FormField
              control={form.control}
              name="description"
              render={({field}) => (
                <FormItem>
                  <FormLabel htmlFor="description">{__('Description')}</FormLabel>
                  <FormControl>
                    <Textarea placeholder="Lorem ipsum" {...field} onInput={(e) => {
                      field.onChange(e);
                      setData({
                        ...data,
                        description: e.currentTarget.value,
                      });
                    }}/>
                  </FormControl>
                  <FormMessage/>
                </FormItem>
              )}
            />
          </div>
          <div className="col-span-4 border-4 border-dashed rounded-md p-4">
            <FormField
              control={form.control}
              name="attachments"
              render={({field}) => (
                <FormItem>
                  <FormLabel htmlFor="attachments">{__('Attachments')}</FormLabel>
                  <FormControl>
                    <Input type="file" {...field} onInput={(e) => {
                      field.onChange(e);
                      previewField(e);
                      setData({
                        ...data,
                        attachments: e.currentTarget.files?.[0], // Incluye el archivo completo
                      });
                    }}/>
                  </FormControl>
                  <FormMessage/>
                  {file && (
                    <FilePreview file={file} handlerRemoveFile={handlerRemoveFile}/>
                  )}
                </FormItem>
              )}
            />
          </div>
          <div className="col-span-2">
            <FormField
              control={form.control}
              name="contact_method"
              render={({field}) => (
                <FormItem>
                  <FormLabel htmlFor="address">{__('Contact Method')}</FormLabel>
                  <FormControl>
                    <Input type="text" placeholder="+57 323221122 Or mail@mail.com" {...field} onInput={handleInput}/>
                  </FormControl>
                  <FormMessage/>
                </FormItem>
              )}
            />
          </div>
        </div>
        <Button type="submit" className="mt-4" disabled={processing}>
          {!processing ? (
            <>
              <Server/>
              <span>{__(ticket ? 'Save Changes' : 'Save Ticket')}</span>
            </>
          ) : (
            <>
              <Loader2 className="animate-spin"/>
              <span>{__('Please wait')}</span>
            </>
          )}
        </Button>
      </form>
    </Form>
  )
}

