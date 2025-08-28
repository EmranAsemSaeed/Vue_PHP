const locations = ['Taiz', 'Aden', 'Sanaa', 'Makkah', 'Madinah', 'Taif']
const skills = ['Education', 'Cooking', 'First Aid', 'Construction', 'Gardening', 'Technical Support', 'Guidance', 'Childcare']

export default {
  namespaced: true,
  state: () => ({
    events: [],
    newEvent: {
      title: '',
      description: '',
      location: '',
      requiredSkills: [],
      date: '',
      time: '',
      requiredVolunteers: 0,
      status: 'Active'
    },
    eventsFilter: 'all',
    skillsFilter: 'all',
    locations,
    skills
  }),
  mutations: {
    ADD_EVENT(state, event) {
      state.events.push(event)
    },
    UPDATE_EVENT(state, updatedEvent) {
      const index = state.events.findIndex(e => e.id === updatedEvent.id)
      if (index !== -1) {
        state.events.splice(index, 1, updatedEvent)
      }
    },
    DELETE_EVENT(state, id) {
      state.events = state.events.filter(e => e.id !== id)
    },
    RESET_EVENT_FORM(state) {
      state.newEvent = {
        title: '',
        description: '',
        location: '',
        requiredSkills: [],
        date: '',
        time: '',
        requiredVolunteers: 0,
        status: 'Active'
      }
    },
    SET_EVENT_FILTER(state, filter) {
      state.eventsFilter = filter
    },
    SET_SKILLS_FILTER(state, filter) {
      state.skillsFilter = filter
    },
    TOGGLE_EVENT_SKILL(state, skill) {
      const index = state.newEvent.requiredSkills.indexOf(skill)
      if (index > -1) {
        state.newEvent.requiredSkills.splice(index, 1)
      } else {
        state.newEvent.requiredSkills.push(skill)
      }
    }
  },
  actions: {
    addEvent({ state, commit, dispatch }) {
      const event = {
        id: Date.now(),
        ...state.newEvent,
        volunteers: 0,
        requiredVolunteers: Math.floor(Math.random() * 10) + 5
      }
      commit('ADD_EVENT', event)
      commit('RESET_EVENT_FORM')
      
      dispatch('toasts/showToast', {
        message: 'Event added successfully!',
        type: 'success'
      }, { root: true })
    },
    updateEvent({ commit, dispatch }, event) {
      commit('UPDATE_EVENT', event)
      dispatch('toasts/showToast', {
        message: 'Event updated successfully!',
        type: 'success'
      }, { root: true })
    },
    deleteEvent({ commit, dispatch }, id) {
      commit('DELETE_EVENT', id)
      dispatch('toasts/showToast', {
        message: 'Event deleted successfully!',
        type: 'success'
      }, { root: true })
    },
    initDemoData({ commit }) {
      const events = [
        {
          id: 1,
          title: 'Community Garden Planting',
          description: 'Help us plant a community garden in the central area',
          location: 'Taiz',
          date: '2023-10-15',
          time: '09:00',
          requiredSkills: ['Gardening', 'Construction'],
          requiredVolunteers: 15,
          status: 'Active'
        },
        {
          id: 2,
          title: 'Children Reading Program',
          description: 'Volunteer to read to children at the local library',
          location: 'Aden',
          date: '2023-10-22',
          time: '14:00',
          requiredSkills: ['Education', 'Childcare'],
          requiredVolunteers: 8,
          status: 'Active'
        },
        {
          id: 3,
          title: 'Homeless Dinner Service',
          description: 'Prepare and serve dinner at a homeless shelter',
          location: 'Makkah',
          date: '2023-11-05',
          time: '17:00',
          requiredSkills: ['Cooking'],
          requiredVolunteers: 10,
          status: 'Active'
        }
      ]
      
      events.forEach(event => {
        commit('ADD_EVENT', event)
      })
    }
  },
  getters: {
    filteredEvents: (state) => {
      let result = state.events
      
      if (state.eventsFilter !== 'all') {
        result = result.filter(e => e.location === state.eventsFilter)
      }
      
      if (state.skillsFilter !== 'all') {
        result = result.filter(e => e.requiredSkills.includes(state.skillsFilter))
      }
      
      return result
    }
  }
}