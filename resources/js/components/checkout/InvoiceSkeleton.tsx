
import { Skeleton } from "@/components/ui/skeleton";

export default function InvoiceSkeleton ()  {
  return (
    <div className="relative flex flex-col bg-white shadow-lg rounded-xl pointer-events-auto dark:bg-neutral-800">
      {/* Encabezado */}
      <div className="relative overflow-hidden min-h-32 bg-gray-900 text-center rounded-t-xl dark:bg-neutral-950">
        <figure className="absolute inset-x-0 bottom-0 -mb-px">
          <svg
            preserveAspectRatio="none"
            xmlns="http://www.w3.org/2000/svg"
            x="0px"
            y="0px"
            viewBox="0 0 1920 100.1"
          >
            <path
              fill="currentColor"
              className="fill-white dark:fill-neutral-800"
              d="M0,0c0,0,934.4,93.4,1920,0v100.1H0L0,0z"
            ></path>
          </svg>
        </figure>
      </div>

      {/* Icono */}
      <div className="relative z-10 -mt-12">
        <span className="mx-auto flex justify-center items-center size-[62px] rounded-full border border-gray-200 bg-white text-gray-700 shadow-sm dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-400">
          <Skeleton className="w-6 h-6 rounded-full" />
        </span>
      </div>

      {/* Contenido */}
      <div className="p-4 sm:p-7 overflow-y-auto">
        {/* Título e información */}
        <div className="text-center">
          <h3 className="text-lg font-semibold text-gray-800 dark:text-neutral-200">
            <Skeleton className="h-6 w-1/2 mx-auto" />
          </h3>
          <div className="text-sm text-gray-500 dark:text-neutral-500">
            <Skeleton className="h-4 w-1/4 mx-auto mt-2" />
          </div>
        </div>

        {/* Resumen de detalles */}
        <div className="mt-5 sm:mt-10 grid grid-cols-2 sm:grid-cols-3 gap-5">
          <div>
            <span className="block text-xs uppercase text-gray-500 dark:text-neutral-500">
              <Skeleton className="h-3 w-16" />
            </span>
            <span className="block text-sm font-medium text-gray-800 dark:text-neutral-200">
              <Skeleton className="h-4 w-10" />
            </span>
          </div>
          <div>
            <span className="block text-xs uppercase text-gray-500 dark:text-neutral-500">
              <Skeleton className="h-3 w-20" />
            </span>
            <span className="block text-sm font-medium text-gray-800 dark:text-neutral-200">
              <Skeleton className="h-4 w-10" />
            </span>
          </div>
          <div>
            <span className="block text-xs uppercase text-gray-500 dark:text-neutral-500">
              <Skeleton className="h-3 w-24" />
            </span>
            <div className="flex items-center gap-x-2">
              <Skeleton className="h-4 w-20" />
            </div>
          </div>
        </div>

        {/* Tabla de Resumen */}
        <div className="mt-5 sm:mt-10">
          <h4 className="text-xs font-semibold uppercase text-gray-800 dark:text-neutral-200">
            <Skeleton className="h-4 w-20" />
          </h4>
          <ul className="mt-3 flex flex-col">
            <li className="inline-flex items-center gap-x-2 py-3 px-4 text-sm border dark:border-neutral-700">
              <div className="flex items-center justify-between w-full">
                <Skeleton className="h-4 w-1/3" />
                <Skeleton className="h-4 w-10" />
              </div>
            </li>
            {/* Puedes añadir más filas aquí */}
          </ul>
        </div>

        {/* Botones */}
        <div className="mt-5 flex justify-end gap-x-2">
          <Skeleton className="h-10 w-24 rounded-md" />
          <Skeleton className="h-10 w-16 rounded-md" />
        </div>

        {/* Pie de página */}
        <div className="mt-5 sm:mt-10">
          <div className="text-sm text-gray-500 dark:text-neutral-500">
            <Skeleton className="h-4 w-3/4 mx-auto" />
          </div>
        </div>
      </div>
    </div>
  );
};


