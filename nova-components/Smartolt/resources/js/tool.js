import Tool from './pages/Tool'
import OnuManager from './components/OnuManager'

Nova.booting((app, store) => {
  Nova.inertia('Smartolt', Tool)
})
