export default {
  namespaced: true,
  state: () => ({
    user: {
      name: 'Emran Asem',
      role: 'user' // 'user' or 'admin'
    }
  }),
  mutations: {
    SET_USER_ROLE(state, role) {
      state.user.role = role
    }
  }
}