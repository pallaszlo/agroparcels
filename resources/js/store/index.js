import Vue from 'vue'
import Vuex from 'vuex'
import snackbar from './snackbar'
import auth from './auth'

Vue.use(Vuex);


const store = new Vuex.Store(
    {
        modules: {
            snackbar,
            auth
          }
    }
);


export default store;