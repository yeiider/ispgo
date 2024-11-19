import {z} from "zod";
import {useForm} from "react-hook-form";
import {Link, useForm as inertiaUseForm, usePage} from "@inertiajs/react"
import {Card, CardContent, CardDescription, CardHeader, CardTitle} from "@/components/ui/card.tsx";
import {__} from "@/translation.ts";
import {Form, FormControl, FormField, FormItem, FormLabel, FormMessage} from "@/components/ui/form.tsx";
import {Input} from "@/components/ui/input.tsx";
import {Button} from "@/components/ui/button.tsx";
import {AlertCircle, Loader2} from "lucide-react";
import React, {useEffect} from "react";
import {zodResolver} from "@hookform/resolvers/zod";
import {toast} from "sonner";
import {Alert, AlertDescription} from "@/components/ui/alert.tsx";

type ResetPasswordProps = {
  routeResetPassword: string,
  flash: Flash
}

interface Flash {
  status: string
}

export default function ResetPassword() {
  const props = usePage<ResetPasswordProps>().props;

  const formSchema = z.object({
    email_address: z.string().email(__("The email must be a valid email address.")),
  })

  const {data, post, errors, setData, processing} = inertiaUseForm({
    email_address: "",
  })

  const form = useForm<z.infer<typeof formSchema>>({
    resolver: zodResolver(formSchema),
    defaultValues: data,
  })

  const onSubmit: () => void = () => {
    console.log("Hola", props.routeResetPassword, errors)
    post(props.routeResetPassword)

  }

  useEffect(() => {
    if (errors && "email_address" in errors) {
      toast.error(<span className="text-destructive text-md">{errors.email_address}</span>, {
        position: "top-right",
        classNames: {
          toast: "bg-red-100",
          title: 'text-red-500',
          icon: "text-red-500",
        }
      });

      form.setError("email_address", {
        type: "manual",
        message: "",
      });
    }
  }, [errors])

  const handleInput = (e: React.ChangeEvent<HTMLInputElement>) => {
    setData({
      ...data,
      [e.target.name]: e.target.value
    })
  }

  return (
    <div className="flex h-screen w-full items-center justify-center px-4">
      <Card className="mx-auto max-w-sm">
        <CardHeader>
          <CardTitle className="text-2xl">{__('You are having trouble logging in?')}</CardTitle>
          <CardDescription>
            {__('')}
          </CardDescription>
        </CardHeader>
        <CardContent>
          {'status' in props.flash && props.flash.status &&  (
            <Alert variant="default" className="mb-5">
              <AlertCircle className="h-4 w-4"/>
              <AlertDescription>
                {props.flash?.status}
              </AlertDescription>
            </Alert>
          )}

          <Form {...form}>
            <form onSubmit={form.handleSubmit(onSubmit)} className="space-y-5">
              <FormField
                control={form.control}
                name="email_address"
                render={({field}) => (
                  <FormItem>
                    <FormLabel htmlFor="email_address">{__('Email')}</FormLabel>
                    <FormControl>
                      <Input className={errors.email_address ? 'border-destructive' : ''} type="email"
                             placeholder="m@example.com" {...field} onInput={handleInput}/>
                    </FormControl>
                    <FormMessage/>
                  </FormItem>
                )}
              />

              <Button type="submit" className="w-full" disabled={processing}>
                {processing ? (
                  <>

                    <Loader2 className="animate-spin"/>
                    {__('Please wait')}
                  </>
                ) : (
                  <>
                    {__('Send E-mail')}
                  </>
                )}
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
