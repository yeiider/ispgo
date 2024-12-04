import {__} from "@/translation.ts";
import {CircleAlert, CircleCheck, Clock5} from "lucide-react";

interface Props {
  status: string | undefined;
}

export default function RenderInvoiceStatus({status}: Props) {
  const size = 16;
  switch (status) {
    case 'unpaid':
      return (
        <div className="flex items-center max-w-fit gap-1 px-4 py-2 rounded-full bg-yellow-50 text-yellow-500">
          <Clock5 size={size} className=""/>
          <span className=" ">{__(status)}</span>
        </div>
      );
    case 'paid':
      return (
        <div className="flex items-center max-w-fit gap-1 px-4 py-2 rounded-full bg-green-50 text-green-500">
          <CircleCheck size={size}/>
          <span className=" ">{__(status)}</span>
        </div>
      );
    case 'canceled':
      return (
        <div className="flex items-center max-w-fit gap-1 px-4 py-2 rounded-full bg-red-50 text-red-500">
          <CircleAlert size={size}/>
          <span className="">{__(status)}</span>
        </div>
      );
    case 'overdue':
      return <span className="flex items-center max-w-fit gap-1 px-4 py-2 rounded-full bg-red-50 text-red-500">{__(status)}</span>;
  }
}
