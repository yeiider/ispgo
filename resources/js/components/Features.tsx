export default function Features() {
  return (

    <div className="max-w-[85rem] px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto">
      {/* Grid */}
      <div className="md:grid md:grid-cols-2 md:items-center md:gap-12 xl:gap-32">
        <div>
          <img className="rounded-xl"
               src="https://images.unsplash.com/photo-1648737963503-1a26da876aca?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=900&h=900&q=80"
               alt="Features Image"/>
        </div>
        {/* End Col */}

        <div className="mt-5 sm:mt-10 lg:mt-0">
          <div className="space-y-6 sm:space-y-8">
            {/* Title */}
            <div className="space-y-2 md:space-y-4">
              <h2 className="font-bold text-3xl lg:text-4xl text-gray-800 dark:text-neutral-200">
                We tackle the challenges start-ups face
              </h2>
              <p className="text-gray-500 dark:text-neutral-500">
                Besides working with start-up enterprises as a partner for digitalization, we have built enterprise
                products for common pain points that we have encountered in various products and projects.
              </p>
            </div>
            {/* End Title */}

            {/* List */}
            <ul className="space-y-2 sm:space-y-4">
              <li className="flex gap-x-3">
            <span
              className="mt-0.5 size-5 flex justify-center items-center rounded-full bg-blue-50 text-blue-600 dark:bg-blue-800/30 dark:text-blue-500">
              <svg className="shrink-0 size-3.5" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                   viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                   stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
            </span>
                <div className="grow">
              <span className="text-sm sm:text-base text-gray-500 dark:text-neutral-500">
                <span className="font-bold">Easy & fast</span> designing
              </span>
                </div>
              </li>

              <li className="flex gap-x-3">
            <span
              className="mt-0.5 size-5 flex justify-center items-center rounded-full bg-blue-50 text-blue-600 dark:bg-blue-800/30 dark:text-blue-500">
              <svg className="shrink-0 size-3.5" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                   viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                   stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
            </span>
                <div className="grow">
              <span className="text-sm sm:text-base text-gray-500 dark:text-neutral-500">
                Powerful <span className="font-bold">features</span>
              </span>
                </div>
              </li>

              <li className="flex gap-x-3">
            <span
              className="mt-0.5 size-5 flex justify-center items-center rounded-full bg-blue-50 text-blue-600 dark:bg-blue-800/30 dark:text-blue-500">
              <svg className="shrink-0 size-3.5" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                   viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                   stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
            </span>
                <div className="grow">
              <span className="text-sm sm:text-base text-gray-500 dark:text-neutral-500">
                User Experience Design
              </span>
                </div>
              </li>
            </ul>
            {/* End List */}
          </div>
        </div>
        {/* End Col */}
      </div>
      {/* End Grid */}
    </div>
  )
}
