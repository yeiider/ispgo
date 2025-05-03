import {TodayBox} from "@/Pages/Pos/MainContentPos.tsx";
import {Button} from "@/components/ui/button"
import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogFooter,
  DialogHeader,
  DialogTitle,
} from "@/components/ui/dialog"

import {
  Form,
  FormControl,
  FormDescription,
  FormField,
  FormItem,
  FormLabel,
  FormMessage,
} from "@/components/ui/form"
import {__} from "@/translation.ts";
import {z} from "zod";
import {Input} from "@/components/ui/input.tsx";
import {useForm} from "react-hook-form";
import {useForm as inertiaUseForm} from "@inertiajs/react";
import {zodResolver} from "@hookform/resolvers/zod";
import React, {useState} from "react";
import {Plus, Save} from "lucide-react";

export default function CreateDailyBoxForm({onDailyBoxCreated, boxId}: {
  onDailyBoxCreated: (todayBox: TodayBox) => void;
  boxId: number | undefined,
}) {

  const formSchema = z.object({
    start_amount: z.string().min(2, {
      message: "Star amount must be at least 2 characters.",
    }),
    box_id: z.number().min(1, {
      message: "Box id must be at least 1 characters.",
    }),
  })

  const {data, post, setData} = inertiaUseForm({
    start_amount: '',
    box_id: boxId
  })

  const form = useForm<z.infer<typeof formSchema>>({
    resolver: zodResolver(formSchema),
    defaultValues: data
  })

  function onSubmit(values: z.infer<typeof formSchema>) {
    if (Object.keys(values).length != 0) {
      post('/admin/daily-boxes/create')
    }
  }

  const [open, setOpen] = useState(false);

  const handleInput = (e: React.ChangeEvent<HTMLInputElement>) => {
    const inputName = e.target.name;
    let rawValue = e.target.value.replace(/,/g, '');

    if (!isNaN(Number(rawValue)) && rawValue !== '') {
      const formattedValue = Number(rawValue).toLocaleString();
      setData({
        ...data,
        [inputName]: rawValue,
      });

      e.target.value = formattedValue;
    } else if (rawValue === '') {
      setData({
        ...data,
        [inputName]: '',
      });

      e.target.value = '';
    }
  };

  return (
    <>
      <Button onClick={() => setOpen(true)}>
        <span>{__('Create box')}</span>
        <Plus/>
      </Button>
      <Dialog defaultOpen={open} open={open} onOpenChange={(value) => setOpen(value)}>
        <DialogContent className="sm:max-w-[425px]">
          <DialogHeader>
            <DialogTitle>{__('Create new box for today')}</DialogTitle>
            <DialogDescription>
              {__('Create a new box for today. The box will be created with the amount that you specify.')}
            </DialogDescription>
          </DialogHeader>

          <Form {...form}>
            <form onSubmit={form.handleSubmit(onSubmit)} className="space-y-8">
              <FormField
                control={form.control}
                name="start_amount"
                render={({field}) => (
                  <FormItem>
                    <FormLabel>{__('Start amount')}</FormLabel>
                    <FormControl>
                      <Input type="text" placeholder="10,000" {...field} onInput={handleInput}/>
                    </FormControl>
                    <FormDescription>
                      {__('The amount that will be added to the box when it is created.')}
                    </FormDescription>
                    <FormMessage/>
                  </FormItem>
                )}
              />
              <DialogFooter>
                <Button type="submit">
                  {__('Submit')}
                  <Save />
                </Button>
              </DialogFooter>
            </form>
          </Form>
        </DialogContent>
      </Dialog>
    </>
  )
}
