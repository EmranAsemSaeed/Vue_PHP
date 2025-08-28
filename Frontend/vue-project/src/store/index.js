import { createStore } from 'vuex'
import theme from './modules/theme'
import volunteers from './modules/volunteers'
import events from './modules/events'
import matches from './modules/matches'
import activities from './modules/activities'
import toasts from './modules/toasts'
import user from './modules/user'

export default createStore({
  modules: {
    theme,
    volunteers,
    events,
    matches,
    activities,
    toasts,
    user
  }
})