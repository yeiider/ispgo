import {Card, CardContent, CardDescription, CardHeader, CardTitle} from "@/components/ui/card.tsx";
import {__} from "@/translation.ts";
import {Link, usePage, useForm as inertiaUseForm} from "@inertiajs/react";
import {Form, FormControl, FormField, FormItem, FormLabel, FormMessage} from "@/components/ui/form.tsx";
import {Input} from "@/components/ui/input.tsx";
import {Button} from "@/components/ui/button.tsx";
import React, {useEffect, useState} from "react";
import {useForm} from "react-hook-form";
import {z} from "zod";
import {zodResolver} from "@hookform/resolvers/zod";
import {toast} from "sonner";
import {Select, SelectContent, SelectItem, SelectTrigger, SelectValue} from "@/components/ui/select.tsx";
import {Errors} from "@/interfaces/IRoot.ts";
import {Eye, EyeOff} from "lucide-react";

type Props = {
  errors: Errors
  documentTypes: DocumentType[]
}

export interface DocumentType {
  label: string
  value: string
}

const formSchema = z.object({
  first_name: z.string().min(3),
  last_name: z.string().min(3),
  document_type: z.string().min(3),
  identity_document: z.string().min(3),
  email_address: z.string().email({
    message: "The email must be a valid email address."
  }),
  password: z.string().min(3),
  password_confirmation: z.string().min(3),
}).refine(data => data.password === data.password_confirmation, {
  message: "The password confirmation does not match.",
  path: ['password_confirmation']
})

export default function Register() {

  const props = usePage<Props>().props;

  const {data, post, errors, setData} = inertiaUseForm({
    first_name: "",
    last_name: "",
    document_type: "",
    identity_document: "",
    email_address: "",
    password: "",
    password_confirmation: "",
  });

  const form = useForm<z.infer<typeof formSchema>>({
    resolver: zodResolver(formSchema),
    defaultValues: data,
  })

  const onSubmit = () => {
    post('register');
  }

  useEffect(() => {
    if (errors && "error" in errors) {
      // @ts-ignore
      toast.error(errors.error, {
        position: "top-right",
        autoClose: 5000,
        hideProgressBar: false,
        closeOnClick: true,
        pauseOnHover: true,
        draggable: true,
        progress: undefined,
        classNames: {
          toast: "bg-red-100",
          title: 'text-red-500',
          icon: "text-red-500",
        }
      })
    }
  }, [errors])

  const handleInput = (e: React.ChangeEvent<HTMLInputElement>) => {
    setData({
      ...data,
      [e.target.name]: e.target.value
    })
  }

  const [isVisible, setIsVisible] = useState<boolean>(false);

  const toggleVisibility = () => setIsVisible((prevState) => !prevState);

  return (
    <div className="flex min-h-screen w-full items-center justify-center px-4 mt-5">
      <Card className="mx-auto max-w-sm">
        <CardHeader>
          <CardTitle className="text-2xl">{__('Sign Up')}</CardTitle>
          <CardDescription>
            {__('Enter your email below to login to your account')}
          </CardDescription>
        </CardHeader>
        <CardContent>
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
                    <Select name="document_type" onValueChange={field.onChange} defaultValue={field.value}>
                      <FormControl>
                        <SelectTrigger>
                          <SelectValue placeholder="Select a document type"/>
                        </SelectTrigger>
                      </FormControl>
                      <SelectContent>
                        {props.documentTypes.map((item, i) => (
                          <SelectItem key={i} value={item.value}>{__(item.label)}</SelectItem>
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

              <FormField
                control={form.control}
                name="password"
                render={({field}) => (
                  <FormItem>
                    <FormLabel htmlFor="password">{__('Password')}</FormLabel>
                    <FormControl>
                      <div className="relative">
                        <Input type={isVisible ? "text" : "password"} placeholder="" {...field} onInput={handleInput}/>
                        <button
                          className="absolute inset-y-0 end-0 flex h-full w-9 items-center justify-center rounded-e-lg text-muted-foreground/80 ring-offset-background transition-shadow hover:text-foreground focus-visible:border focus-visible:border-ring focus-visible:text-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring/30 focus-visible:ring-offset-2 disabled:pointer-events-none disabled:cursor-not-allowed disabled:opacity-50"
                          type="button"
                          onClick={toggleVisibility}
                          aria-label={isVisible ? "Hide password" : "Show password"}
                          aria-pressed={isVisible}
                          aria-controls="password"
                        >
                          {isVisible ? (
                            <EyeOff size={16} strokeWidth={2} aria-hidden="true"/>
                          ) : (
                            <Eye size={16} strokeWidth={2} aria-hidden="true"/>
                          )}
                        </button>
                      </div>
                    </FormControl>
                    <FormMessage/>
                  </FormItem>
                )}
              />

              <FormField
                control={form.control}
                name="password_confirmation"
                render={({field}) => (
                  <FormItem>
                    <FormLabel htmlFor="password_confirmation">{__('Confirm Password')}</FormLabel>
                    <FormControl>
                      <Input type={isVisible ? "text" : "password"} placeholder="" {...field} onInput={handleInput}/>
                    </FormControl>
                    <FormMessage/>
                  </FormItem>
                )}
              />

              <Button type="submit" className="w-full">
                {__('Login')}
              </Button>
            </form>
          </Form>
          <div className="mt-4 text-center text-sm">
            {__("Already have an account?")} {" "}
            <Link href="/customer/login" className="underline">
              {__('Sign In')}
            </Link>
          </div>
        </CardContent>
      </Card>
    </div>
  )
}


