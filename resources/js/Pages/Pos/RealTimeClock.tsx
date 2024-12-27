import {useEffect, useState} from "react";

export default function RealTimeClock() {
  const [currentTime, setCurrentTime] = useState<string>();
  const [currentDate, setCurrentDate] = useState<string>();

  useEffect(() => {
    const updateTime = () => {
      const now = new Date();
      setCurrentTime(now.toLocaleTimeString());
      setCurrentDate(now.toLocaleDateString());
    };

    const intervalId = setInterval(updateTime, 1000);

    return () => {
      clearInterval(intervalId);
    };

  }, [])

  return (
    <div className="text-end">
      <div className="text-xl font-bold">{currentTime}</div>
      <div className="text-md">{currentDate}</div>
    </div>
  )
}
