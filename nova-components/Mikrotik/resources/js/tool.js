import Tool from './pages/Tool'
import Pool from "./pages/Pool";
import Ipv6PoolComponent from "./pages/Ipv6";
import DHCPPoolComponent from "./pages/DHCP.vue"

Nova.booting((app, store) => {
    Nova.inertia('Mikrotik', Tool)
    Nova.inertia('Pool', Pool)
    Nova.inertia('Ipv6PoolComponent', Ipv6PoolComponent)
    Nova.inertia('DHCPPoolComponent', DHCPPoolComponent)
})
