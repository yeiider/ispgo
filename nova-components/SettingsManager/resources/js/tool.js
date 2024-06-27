import Tool from './pages/Tool'
import '../css/tailwind.css';

Nova.booting((app, store) => {
  Nova.inertia('SettingsManager',Tool)
})
