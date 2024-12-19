export default function CashierInfo({cashierName}: {
  cashierName: string;
}) {
  return (
    <div>
      <p className="text-gray-600 font-bold text-end uppercase">{cashierName}</p>
    </div>
  )
}
