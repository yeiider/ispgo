import Tool from './pages/Tool'
import Pool from "./pages/Pool";
Nova.booting((app, store) => {
  Nova.inertia('Mikrotik', Tool)
  Nova.inertia('Pool', Pool)
})
