import appsAndPages from './apps-and-pages'
import charts from './charts'
import dashboard from './dashboard'
import type { VerticalNavItems } from '@layouts/types'

export default [...dashboard, ...appsAndPages, ...charts] as VerticalNavItems
