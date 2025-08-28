const locations = ['Taiz', 'Aden', 'Sanaa', 'Makkah', 'Madinah', 'Taif']
const skills = ['Education', 'Cooking', 'First Aid', 'Construction', 'Gardening', 'Technical Support', 'Guidance', 'Childcare']
const days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday']

export default {
  namespaced: true,
  state: () => ({
    volunteers: [],
    newVolunteer: {
      name: '',
      email: '',
      location: '',
      skills: [],
      availability: []
    },
    errors: {
      name: '',
      email: '',
      skills: '',
      availability: ''
    },
    volunteersFilter: 'all',
    skillsFilter: 'all',
    pagination: {
      currentPage: 1,
      itemsPerPage: 5
    },
    locations,
    skills,
    days
  }),
  mutations: {
    ADD_VOLUNTEER(state, volunteer) {
      state.volunteers.push(volunteer)
    },
    RESET_VOLUNTEER_FORM(state) {
      state.newVolunteer = {
        name: '',
        email: '',
        location: '',
        skills: [],
        availability: []
      }
    },
    SET_VOLUNTEER_FILTER(state, filter) {
      state.volunteersFilter = filter
    },
    SET_SKILLS_FILTER(state, filter) {
      state.skillsFilter = filter
    },
    SET_PAGINATION_PAGE(state, page) {
      state.pagination.currentPage = page
    },
    TOGGLE_SKILL(state, skill) {
      const index = state.newVolunteer.skills.indexOf(skill)
      if (index > -1) {
        state.newVolunteer.skills.splice(index, 1)
      } else {
        state.newVolunteer.skills.push(skill)
      }
    },
    TOGGLE_AVAILABILITY(state, day) {
      const index = state.newVolunteer.availability.indexOf(day)
      if (index > -1) {
        state.newVolunteer.availability.splice(index, 1)
      } else {
        state.newVolunteer.availability.push(day)
      }
    },
    SET_ERROR(state, { field, message }) {
      state.errors[field] = message
    },
    CLEAR_ERRORS(state) {
      Object.keys(state.errors).forEach(key => {
        state.errors[key] = ''
      })
    }
  },
  actions: {
    registerVolunteer({ state, commit, dispatch }) {
      commit('CLEAR_ERRORS')
      
      let isValid = true
      if (!state.newVolunteer.name) {
        commit('SET_ERROR', { field: 'name', message: 'Name is required' })
        isValid = false
      }
      
      if (!state.newVolunteer.email) {
        commit('SET_ERROR', { field: 'email', message: 'Email is required' })
        isValid = false
      } else if (!/\S+@\S+\.\S+/.test(state.newVolunteer.email)) {
        commit('SET_ERROR', { field: 'email', message: 'Invalid email format' })
        isValid = false
      }
      
      if (state.newVolunteer.skills.length === 0) {
        commit('SET_ERROR', { field: 'skills', message: 'At least one skill is required' })
        isValid = false
      }
      
      if (state.newVolunteer.availability.length === 0) {
        commit('SET_ERROR', { field: 'availability', message: 'At least one availability day is required' })
        isValid = false
      }
      
      if (!isValid) {
        dispatch('toasts/showToast', { 
          message: 'Please correct the form errors', 
          type: 'error' 
        }, { root: true })
        return
      }
      
      dispatch('toasts/showToast', { 
        message: 'Registering volunteer...', 
        type: 'loading' 
      }, { root: true })
      
      setTimeout(() => {
        const volunteer = {
          id: Date.now(),
          ...state.newVolunteer
        }
        commit('ADD_VOLUNTEER', volunteer)
        commit('RESET_VOLUNTEER_FORM')
        
        dispatch('toasts/showToast', { 
          message: 'Volunteer registered successfully!', 
          type: 'success' 
        }, { root: true })
      }, 1500)
    },
    initDemoData({ commit }) {
      const volunteers = [
        {
          id: 1,
          name: 'Sarah Mohammed',
          email: 'sara@example.com',
          location: 'Taiz',
          skills: ['Education', 'Childcare'],
          availability: ['Saturday', 'Sunday']
        },
        {
          id: 2,
          name: 'Ahmed Khalid',
          email: 'ahmed@example.com',
          location: 'Aden',
          skills: ['Technical Support', 'First Aid'],
          availability: ['Monday', 'Wednesday', 'Friday']
        },
        {
          id: 3,
          name: 'Nora Abdullah',
          email: 'noura@example.com',
          location: 'sanaa',
          skills: ['Cooking', 'Gardening'],
          availability: ['Saturday']
        },
        {
          id: 4,
          name: 'Abdulrahman Saeed',
          email: 'abdurahman@example.com',
          location: 'Makkah',
          skills: ['Construction', 'First Aid'],
          availability: ['Sunday']
        },
        {
          id: 5,
          name: 'Fatima Ali',
          email: 'fatima@example.com',
          location: 'Taiz',
          skills: ['Education', 'Guidance'],
          availability: ['Tuesday', 'Thursday']
        }
      ]
      
      volunteers.forEach(volunteer => {
        commit('ADD_VOLUNTEER', volunteer)
      })
    }
  },
  getters: {
    filteredVolunteers: (state) => {
      let result = state.volunteers
      
      if (state.volunteersFilter !== 'all') {
        result = result.filter(v => v.location === state.volunteersFilter)
      }
      
      if (state.skillsFilter !== 'all') {
        result = result.filter(v => v.skills.includes(state.skillsFilter))
      }
      
      return result
    },
    paginatedVolunteers: (state, getters) => {
      const startIndex = (state.pagination.currentPage - 1) * state.pagination.itemsPerPage
      const endIndex = state.pagination.currentPage * state.pagination.itemsPerPage
      return getters.filteredVolunteers.slice(startIndex, endIndex)
    },
    totalPages: (state, getters) => {
      return Math.ceil(getters.filteredVolunteers.length / state.pagination.itemsPerPage)
    }
  }
}