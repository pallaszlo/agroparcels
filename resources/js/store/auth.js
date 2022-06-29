import axios from 'axios'
export default {
    namespaced: true,
    name: "auth",
    state: {
        authenticated: false,
        loading: false,
        user: null,
        role: null,
    },
    mutations: {
        setAuthentication(state, status) {
            state.authenticated = status;
        },
        setLoader(state, status) {
            state.loading = status;
        },
        setUser(state, userData) {
            state.user = userData;
            state.role = userData.roles[0];
            localStorage.setItem('user', JSON.stringify(userData));
            localStorage.setItem('role', userData.roles[0]);
            axios.defaults.headers.common.Authorization = `Bearer ${userData.access_token}`;
        },
        setRole(state, status) {
            state.role = status;
        },
        clearUserData() {
            localStorage.removeItem('user');
            localStorage.removeItem('role');
            //location.reload();
        }
    },
    actions: {
        /*
        login ({ commit }, credentials) {
          return axios
            .post('/login', credentials)
            .then(({ data }) => {
              commit('setUserData', data)
            })
        },
        */

        logout({ commit }) {
            commit('clearUserData');
            console.log('logout');
        }
    },
    getters: {
        isLogged: state => !!state.user
    }
}
