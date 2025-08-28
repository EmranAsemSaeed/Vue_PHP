export default {
  namespaced: true,
  state: () => ({
    activities: []
  }),
  mutations: {
    ADD_ACTIVITY(state, activity) {
      state.activities.unshift(activity)
    }
  },
  actions: {
    initDemoData({ commit }) {
      const activities = [
        {
          id: 1,
          title: 'New volunteer registered',
          description: 'Sarah Mohammed joined the platform',
          time: '2 hours ago',
          icon: 'fas fa-user-plus',
          iconBg: 'bg-blue-500'
        },
        {
          id: 2,
          title: 'Event created',
          description: 'Homeless Dinner Service added',
          time: 'Yesterday',
          icon: 'fas fa-calendar-plus',
          iconBg: 'bg-green-500'
        },
        {
          id: 3,
          title: 'Matching completed',
          description: 'Fatima matched with Children Reading Program',
          time: '2 days ago',
          icon: 'fas fa-handshake',
          iconBg: 'bg-purple-500'
        }
      ]
      
      activities.forEach(activity => {
        commit('ADD_ACTIVITY', activity)
      })
    }
  }
}