import {Button} from "@/components/ui/button"
import {
  Card,
  CardContent,
  CardDescription,
  CardHeader,
  CardTitle,
} from "@/components/ui/card"
import {Input} from "@/components/ui/input"
import {Link} from "@inertiajs/react";
import {useForm as inertiaUseForm} from "@inertiajs/react";
import {z} from "zod"
import {useForm} from "react-hook-form"
import {zodResolver} from "@hookform/resolvers/zod";
import {
  Form,
  FormControl,
  FormField,
  FormItem,
  FormLabel,
  FormMessage
} from "@/components/ui/form.tsx";
import React, {useEffect, useState} from "react";
import {toast} from "sonner";
import {__} from "@/translation.ts"
import {Loader2} from "lucide-react";
import {Eye, EyeOff} from "lucide-react";

const formSchema = z.object({
  email_address: z.string().email({
    message: "The email must be a valid email address."
  }),
  password: z.string().min(3),
})

export default function Login() {
  const {data, post, errors, setData, processing} = inertiaUseForm({
    email_address: "",
    password: "",
  })

  const form = useForm<z.infer<typeof formSchema>>({
    resolver: zodResolver(formSchema),
    defaultValues: data,
  })

  const onSubmit = () => {
    post('login');
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
      form.setError("password", {
        type: "manual",
        message: "",
      });
      form.setError("email_address", {
        type: "manual",
        message: "",
      });
    }
  }, [errors]);

  const handleInput = (e: React.ChangeEvent<HTMLInputElement>) => {
    setData({
      ...data,
      [e.target.name]: e.target.value
    })
  }

  const [isVisible, setIsVisible] = useState<boolean>(false);

  const toggleVisibility = () => setIsVisible((prevState) => !prevState);

  return (
    <div className="flex h-screen w-full items-center justify-center px-4">
      <Card className="mx-auto max-w-sm">
        <CardHeader>
          <CardTitle className="text-2xl">{__('Sign In')}</CardTitle>
          <CardDescription>
            {__('Enter your email below to login to your account')}
          </CardDescription>
        </CardHeader>
        <CardContent className="w-full">
          <Form {...form}>
            <form onSubmit={form.handleSubmit(onSubmit)} className="space-y-5">
              <FormField
                control={form.control}
                name="email_address"
                render={({field}) => (
                  <FormItem>
                    <FormLabel htmlFor="email_address">{__('Email')}</FormLabel>
                    <FormControl>
                      <Input className="" type="email" placeholder="m@example.com" {...field} onInput={handleInput}/>
                    </FormControl>
                    <FormMessage/>
                  </FormItem>
                )}
              />

              <FormField
                control={form.control}
                name="password"
                render={({field}) => (
                  <FormItem >
                    <div className="flex items-center">
                      <FormLabel htmlFor="password">{__('Password')}</FormLabel>
                      <Link href="password/reset" className="ml-auto inline-block text-sm underline">
                        {__('Forgot your password?')}
                      </Link>
                    </div>
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

              <Button type="submit" className="w-full" disabled={processing}>
                {!processing ? (
                  <>
                    {__('Login')}
                  </>
                ) : (
                  <>
                    <Loader2 className="animate-spin"/>
                    {__('Please wait')}
                  </>
                )}
              </Button>
            </form>
          </Form>

          <div className="mt-4 text-center text-sm">
            {__("Don't have an account?")} {" "}
            <Link href="/customer/register" className="underline">
              {__('Sign Up')}
            </Link>
          </div>
        </CardContent>
      </Card>
    </div>
  )
}
