// export default {
//   namespaced: true,
//   state: () => ({
//     darkMode: false
//   }),
//   mutations: {
//     TOGGLE_DARK_MODE(state) {
//       state.darkMode = !state.darkMode
//       localStorage.setItem('darkMode', state.darkMode)
//       document.documentElement.classList.toggle('dark', state.darkMode)
//     },
//     SET_DARK_MODE(state, value) {
//       state.darkMode = value
//     }
//   },
//   actions: {
//     initTheme({ commit }) {
//       const savedDarkMode = localStorage.getItem('darkMode') === 'true'
//       commit('SET_DARK_MODE', savedDarkMode)
//       document.documentElement.classList.toggle('dark', savedDarkMode)
//     }
//   }
// }


export default {
  namespaced: true,
  state: () => ({
    darkMode: false
  }),
  mutations: {
    TOGGLE_DARK_MODE(state) {
      state.darkMode = !state.darkMode
      localStorage.setItem('darkMode', state.darkMode)
      document.documentElement.classList.toggle('dark', state.darkMode)
    },
    SET_DARK_MODE(state, value) {
      state.darkMode = value
      localStorage.setItem('darkMode', value)
      document.documentElement.classList.toggle('dark', value)
    }
  },
  actions: {
    initTheme({ commit }) {
      const savedDarkMode = localStorage.getItem('darkMode') === 'true'
      commit('SET_DARK_MODE', savedDarkMode)
    }
  }
}
