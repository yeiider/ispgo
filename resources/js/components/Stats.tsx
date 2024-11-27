export default function Stats() {
  return (

    <div className="max-w-[85rem] px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto">
      {/* Grid */}
      <div className="grid gap-6 grid-cols-2 sm:gap-12 lg:grid-cols-3 lg:gap-8">
        {/* Stats */}
        <div>
          <h4 className="text-lg sm:text-xl font-semibold text-gray-800 dark:text-neutral-200">Accuracy rate</h4>
          <p className="mt-2 sm:mt-3 text-4xl sm:text-6xl font-bold text-[#0ea5e9]">99.95%</p>
          <p className="mt-1 text-gray-500 dark:text-neutral-500">in fulfilling orders</p>
        </div>
        {/* End Stats */}

        {/* Stats */}
        <div>
          <h4 className="text-lg sm:text-xl font-semibold text-gray-800 dark:text-neutral-200">Startup businesses</h4>
          <p className="mt-2 sm:mt-3 text-4xl sm:text-6xl font-bold text-[#0ea5e9]">2,000+</p>
          <p className="mt-1 text-gray-500 dark:text-neutral-500">partner with Preline</p>
        </div>
        {/* End Stats */}

        {/* Stats */}
        <div>
          <h4 className="text-lg sm:text-xl font-semibold text-gray-800 dark:text-neutral-200">Happy customer</h4>
          <p className="mt-2 sm:mt-3 text-4xl sm:text-6xl font-bold text-[#0ea5e9]">85%</p>
          <p className="mt-1 text-gray-500 dark:text-neutral-500">this year alone</p>
        </div>
        {/* End Stats */}
      </div>
      {/* End Grid */}
    </div>

  )
}
