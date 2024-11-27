import {Card, CardContent, CardDescription, CardHeader, CardTitle} from "@/components/ui/card.tsx";
import {__} from "@/translation.ts";
import React, {useEffect, useState} from "react";
import {z} from "zod";
import {useForm} from "react-hook-form";
import {zodResolver} from "@hookform/resolvers/zod";
import {useForm as inertiaUseForm, usePage} from "@inertiajs/react";
import {toast} from "sonner";
import {Form, FormControl, FormField, FormItem, FormLabel, FormMessage} from "@/components/ui/form.tsx";
import {Eye, EyeOff, Loader2} from "lucide-react";
import {Button} from "@/components/ui/button.tsx";
import {Input} from "@/components/ui/input.tsx";

type Props = {
  email_address: string,
  routeCreatePassword: string,
  customer: {
    email_address: string
  }
}

export default function CreatePassword() {

  const props = usePage<Props>().props

  const formSchema = z.object({
    password: z.string().min(3),
    password_confirmation: z.string().min(3),
    email_address: z.string().email()
  }).refine(data => data.password === data.password_confirmation, {
    message: __("The password confirmation does not match."),
    path: ['password_confirmation']
  })

  const {data, post, errors, setData, processing} = inertiaUseForm({
    password: "",
    password_confirmation: "",
    email_address: props.customer.email_address
  })

  const form = useForm<z.infer<typeof formSchema>>({
    resolver: zodResolver(formSchema),
    defaultValues: data,
  })

  useEffect(() => {
    if (errors && "password" in errors) {
      toast.error(<span className="text-destructive text-md">{errors.password}</span>, {
        position: "top-right",
        classNames: {
          toast: "bg-red-100",
          title: 'text-red-500',
          icon: "text-red-500",
        }
      });

      form.setError("password", {
        type: "manual",
        message: "",
      });
    }
  }, [errors])

  const onSubmit = () => {
    post(props.routeCreatePassword);
  }

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
          <CardTitle className="text-2xl">{__('Change password')}</CardTitle>
          <CardDescription>

          </CardDescription>
        </CardHeader>
        <CardContent className="w-full">
          <Form {...form}>
            <form onSubmit={form.handleSubmit(onSubmit)} className="space-y-5">
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

              <Button type="submit" className="w-full bg-[#0ea5e9] hover:bg-[#38bdf8]" disabled={processing}>
                {processing ? (
                  <>

                    <Loader2 className="animate-spin"/>
                    {__('Please wait')}
                  </>
                ) : (
                  <>
                    {__('Change password')}
                  </>
                )}
              </Button>
            </form>
          </Form>
        </CardContent>
      </Card>
    </div>
  )
}
