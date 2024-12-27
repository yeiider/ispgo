import {
  Card,
  CardContent,
  CardDescription,
  CardHeader,
  CardTitle,
} from "@/components/ui/card"
import {ArrowRight, CircleHelp, MailOpen, MessagesSquare, SendHorizontal, SquareTerminal} from "lucide-react";
import {z} from "zod";
import {useForm} from "react-hook-form";
import {zodResolver} from "@hookform/resolvers/zod";
import {
  Form,
  FormControl,
  FormField,
  FormItem,
  FormMessage,
} from "@/components/ui/form"
import {Input} from "@/components/ui/input"
import {Button} from "@/components/ui/button.tsx";
import {Textarea} from "@/components/ui/textarea"
import {__} from "@/translation.ts";
import {useForm as inertiaUseForm, usePage} from "@inertiajs/react";
import React, {useEffect} from "react";
import {toast} from "sonner";

export default function ContactUs() {
  const formSchema = z.object({
    firstname: z.string().min(2, {
      message: "First name must be at least 2 characters.",
    }),
    lastname: z.string().min(2, {
      message: "Last name must be at least 2 characters.",
    }),
    email: z.string().email({
      message: "First name must be at least 2 characters.",
    }),
    phoneNumber: z.string().min(6, {
      message: "Phone number must be at least 6 characters.",
    }),
    details: z.string().min(2, {
      message: "Details must be at least 2 characters.",
    }),
  })

  const {data, setData, post} = inertiaUseForm({
    firstname: "",
    lastname: "",
    email: "",
    phoneNumber: "",
    details: "",
  })

  const form = useForm<z.infer<typeof formSchema>>({
    resolver: zodResolver(formSchema),
    defaultValues: data
  })

  function onSubmit(values: z.infer<typeof formSchema>) {
    // Do something with the form values.
    // ✅ This will be type-safe and validated.
    console.log(values)
    post('/contact-us', {
      onSuccess: () => {
        form.reset();
      }
    })
  }

  const handleInput = (e: React.ChangeEvent<HTMLInputElement>) => {
    setData({
      ...data,
      [e.target.name]: e.target.value
    })
  }

  type Props = {
    flash: {
      status?: string,
    }
  }
  console.log(usePage<Props>().props)

  const props = usePage<Props>().props;
  useEffect(() => {
    if (props.flash.status) {
      toast.success(props.flash.status, {
        classNames: {
          icon: "text-green-500",
          title: "text-green-500",
        }
      })
    }
  }, [])

  return (
    <div className="max-w-[70rem] mx-auto px-4 sm:px-6 lg:px-8 my-12 ">
      <div className="md:grid grid-cols-2 gap-10">
        <Card className="">
          <CardHeader>
            <CardTitle>{__('Contact us')}</CardTitle>
            <CardDescription>{__("We'd love to talk about how we can help you.")} 🤩</CardDescription>
          </CardHeader>
          <CardContent>
            <Form {...form}>
              <form onSubmit={form.handleSubmit(onSubmit)}
                    className="grid grid-cols-2 gap-x-4 gap-y-4 sm:gap-x-6 sm:gap-y-8">
                <div>
                  <FormField
                    control={form.control}
                    name="firstname"
                    render={({field}) => (
                      <FormItem>
                        <FormControl>
                          <Input placeholder={__('First Name')} {...field} onInput={handleInput}/>
                        </FormControl>
                        <FormMessage/>
                      </FormItem>
                    )}
                  />
                </div>

                <div>
                  <FormField
                    control={form.control}
                    name="lastname"
                    render={({field}) => (
                      <FormItem>
                        <FormControl>
                          <Input placeholder={__('Last Name')} {...field} onInput={handleInput}/>
                        </FormControl>
                        <FormMessage/>
                      </FormItem>
                    )}
                  />
                </div>

                <div className="col-span-2">
                  <FormField
                    control={form.control}
                    name="email"
                    render={({field}) => (
                      <FormItem>
                        <FormControl>
                          <Input placeholder={__('Email')} {...field} onInput={handleInput}/>
                        </FormControl>
                        <FormMessage/>
                      </FormItem>
                    )}
                  />
                </div>

                <div className="col-span-2">
                  <FormField
                    control={form.control}
                    name="phoneNumber"
                    render={({field}) => (
                      <FormItem>
                        <FormControl>
                          <Input placeholder={__('Phone Number')} {...field} onInput={handleInput}/>
                        </FormControl>
                        <FormMessage/>
                      </FormItem>
                    )}
                  />
                </div>
                <div className="col-span-2">
                  <FormField
                    control={form.control}
                    name="details"
                    render={({field}) => (
                      <FormItem>
                        <FormControl>
                          <Textarea placeholder={__('Details')} {...field} onInput={(e) => {
                            //@ts-ignore
                            handleInput(e)
                          }}/>
                        </FormControl>
                        <FormMessage/>
                      </FormItem>
                    )}
                  />
                </div>


                <div className="col-span-2">
                  <Button type="submit" className="w-full bg-[#0ea5e9]">
                    <span>{__('Send inquiry')}</span>
                    <SendHorizontal/>
                  </Button>
                </div>
              </form>
            </Form>
          </CardContent>
        </Card>

        <div className="divide-y divide-gray-200 dark:divide-neutral-800">
          {/* Icon Block */}
          <div className="flex gap-x-7 py-6">
            <CircleHelp size={25} className="text-gray-700"/>
            <div className="grow">
              <h3 className="font-semibold text-gray-800 dark:text-neutral-200">Knowledgebase</h3>
              <p className="mt-1 text-sm text-gray-500 dark:text-neutral-500">{__("We're here to help with any questions or code.")}</p>
              <a
                className="mt-2 inline-flex items-center gap-x-2 text-sm font-medium text-gray-600 hover:text-gray-800 focus:outline-none focus:text-gray-800 dark:text-neutral-400 dark:hover:text-neutral-200 dark:focus:text-neutral-200"
                href="#">
                {__('Contact support')}
                <ArrowRight size={16}
                            className="shrink-0 transition ease-in-out group-hover:translate-x-1 group-focus:translate-x-1"/>
              </a>
            </div>
          </div>
          {/* End Icon Block */}

          {/* Icon Block */}
          <div className="flex gap-x-7 py-6">
            <MessagesSquare size={25} className="text-gray-700"/>
            <div className="grow">
              <h3 className="font-semibold text-gray-800 dark:text-neutral-200">FAQ</h3>
              <p className="mt-1 text-sm text-gray-500 dark:text-neutral-500">{__('Search our FAQ for answers to anything you might ask.')}</p>
              <a
                className="mt-2 inline-flex items-center gap-x-2 text-sm font-medium text-gray-600 hover:text-gray-800 focus:outline-none focus:text-gray-800 dark:text-neutral-400 dark:hover:text-neutral-200 dark:focus:text-neutral-200"
                href="#">
                {__('Visit FAQ')}
                <ArrowRight size={16}
                            className="shrink-0 transition ease-in-out group-hover:translate-x-1 group-focus:translate-x-1"/>
              </a>
            </div>
          </div>
          {/* End Icon Block */}

          {/* Icon Block */}
          <div className=" flex gap-x-7 py-6">
            <SquareTerminal size={25} className="text-gray-700"/>
            <div className="grow">
              <h3 className="font-semibold text-gray-800 dark:text-neutral-200">{__('Developer APIs')}</h3>
              <p className="mt-1 text-sm text-gray-500 dark:text-neutral-500">{__('Check out our development quickstart guide.')}</p>
              <a
                className="mt-2 inline-flex items-center gap-x-2 text-sm font-medium text-gray-600 hover:text-gray-800 focus:outline-none focus:text-gray-800 dark:text-neutral-400 dark:hover:text-neutral-200 dark:focus:text-neutral-200"
                href="#">
                Contact sales
                <ArrowRight size={16}
                            className="shrink-0 transition ease-in-out group-hover:translate-x-1 group-focus:translate-x-1"/>
              </a>
            </div>
          </div>
          {/* End Icon Block */}

          {/* Icon Block */}
          <div className=" flex gap-x-7 py-6">
            <MailOpen size={25} className="text-gray-700"/>
            <div className="grow">
              <h3 className="font-semibold text-gray-800 dark:text-neutral-200">{__('Contact us by email')}</h3>
              <p className="mt-1 text-sm text-gray-500 dark:text-neutral-500">{__('If you wish to write us an email instead please use.')}</p>
              <a
                className="mt-2 inline-flex items-center gap-x-2 text-sm font-medium text-gray-600 hover:text-gray-800 focus:outline-none focus:text-gray-800 dark:text-neutral-400 dark:hover:text-neutral-200 dark:focus:text-neutral-200"
                href="#">
                example@site.com
              </a>
            </div>
          </div>
          {/* End Icon Block */}
        </div>
      </div>
    </div>
  )
}
