import Tool from './pages/Tool'
import OltLists from './pages/OltList'
import OltDetail from './pages/OltDetail'

Nova.booting((app, store) => {
  Nova.inertia('Smartolt', Tool)
  Nova.inertia('OltLists', OltLists)
  Nova.inertia('OltDetail', OltDetail)
})
