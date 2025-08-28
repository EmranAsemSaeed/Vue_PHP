export default {
  namespaced: true,
  state: () => ({
    matches: [],
    currentMatch: null
  }),
  mutations: {
    ADD_MATCH(state, match) {
      state.matches.push(match)
    },
    SET_CURRENT_MATCH(state, match) {
      state.currentMatch = match
    },
    CLEAR_CURRENT_MATCH(state) {
      state.currentMatch = null
    },
    SAVE_MATCHES(state) {
      if (!state.currentMatch) return
      
      state.currentMatch.matches.forEach(match => {
        state.matches.push({
          id: Date.now(),
          event: state.currentMatch.event,
          volunteer: match.volunteer,
          matchScore: match.matchScore,
          matchedSkills: match.matchedSkills,
          status: 'Pending'
        })
      })
      
      state.currentMatch = null
    }
  },
  actions: {
    matchVolunteers({ commit, state, rootState, dispatch }, event) {
      dispatch('toasts/showToast', {
        message: 'Matching volunteers...',
        type: 'loading'
      }, { root: true })
      
      setTimeout(() => {
        const volunteers = rootState.volunteers.volunteers
        const eventMatches = []
        
        volunteers.forEach(volunteer => {
          let matchScore = 0
          let reason = ''
          let warning = ''
          
          // Location match
          if (volunteer.location === event.location) {
            matchScore += 40
            reason = 'Location match'
          } else {
            warning = 'Location mismatch'
          }
          
          // Skills match
          const matchedSkills = volunteer.skills.filter(skill =>
            event.requiredSkills.includes(skill)
          )
          
          if (matchedSkills.length > 0) {
            matchScore += 40
            reason = reason ? `${reason}, Skills match` : 'Skills match'
          } else {
            warning = warning ? `${warning}, Skills mismatch` : 'Skills mismatch'
          }
          
          // Availability match
          const eventDay = new Date(event.date).toLocaleDateString('en-US', { weekday: 'long' })
          if (volunteer.availability.includes(eventDay)) {
            matchScore += 20
            reason = reason ? `${reason}, Available on ${eventDay}` : `Available on ${eventDay}`
          } else {
            warning = warning ? `${warning}, Not available on ${eventDay}` : `Not available on ${eventDay}`
          }
          
          if (matchScore >= 60) {
            eventMatches.push({
              volunteer,
              matchScore,
              matchedSkills,
              reason,
              warning
            })
          }
        })
        
        eventMatches.sort((a, b) => b.matchScore - a.matchScore)
        
        commit('SET_CURRENT_MATCH', {
          event,
          matches: eventMatches.slice(0, 3)
        })
        
        dispatch('toasts/showToast', {
          message: 'Matching completed!',
          type: 'success'
        }, { root: true })
      }, 2000)
    },
    saveMatches({ commit, dispatch }) {
      commit('SAVE_MATCHES')
      dispatch('toasts/showToast', {
        message: 'Matches saved successfully!',
        type: 'success'
      }, { root: true })
    },
    initDemoData({ commit }) {
      const matches = [
        {
          id: 1,
          event: {
            id: 1,
            title: 'Community Garden Planting',
            date: '2023-10-15',
            location: 'Taiz'
          },
          volunteer: {
            id: 1,
            name: 'Sarah Mohammed',
            email: 'sara@example.com'
          },
          matchScore: 80,
          matchedSkills: ['Gardening'],
          status: 'Confirmed'
        },
        {
          id: 2,
          event: {
            id: 2,
            title: 'Children Reading Program',
            date: '2023-10-22',
            location: 'Aden'
          },
          volunteer: {
            id: 5,
            name: 'Fatima Ali',
            email: 'fatima@example.com'
          },
          matchScore: 90,
          matchedSkills: ['Education'],
          status: 'Pending'
        }
      ]
      
      matches.forEach(match => {
        commit('ADD_MATCH', match)
      })
    }
  },
  getters: {
    matchedVolunteers: (state) => state.matches.length,
    upcomingEvents: (state, getters, rootState) => {
      const today = new Date()
      return rootState.events.events.filter(e => new Date(e.date) > today).length
    }
  }
}