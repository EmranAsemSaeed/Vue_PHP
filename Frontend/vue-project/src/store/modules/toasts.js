export default {
  namespaced: true,
  state: () => ({
    toasts: []
  }),
  mutations: {
    ADD_TOAST(state, toast) {
      state.toasts.push({
        ...toast,
        id: Date.now()
      })
    },
    REMOVE_TOAST(state, id) {
      state.toasts = state.toasts.filter(toast => toast.id !== id)
    }
  },
  actions: {
    showToast({ commit }, toast) {
      commit('ADD_TOAST', toast)
      
      setTimeout(() => {
        commit('REMOVE_TOAST', toast.id)
      }, 3000)
    }
  }
}