import Vue from 'vue'
import App from './App.vue'
import Vuetify from 'vuetify/lib'
import VueRouter from 'vue-router'
import Login from './Login.vue'
import Vuex from 'vuex'
import Home from './views/Home.vue'
import Pessoas from './views/Pessoas.vue'
import P404 from './views/P404.vue'
import './plugins/vuetify'
import 'vuetify/src/stylus/app.styl'
import 'es6-promise/auto'
import VueMask from 'v-mask'
import VeeValidate from 'vee-validate'
import VueMeta from 'vue-meta'
import createPersistedState from 'vuex-persistedstate'


// require('./bootstrap');

Vue.use(VueMeta, {
  // optional pluginOptions
  refreshOnceOnNavigation: true
})
Vue.use(VeeValidate)
Vue.use(VueMask);
Vue.use(Vuex)
Vue.use(VueRouter)
Vue.use(Vuetify)

Vue.config.productionTip = false

const router = new VueRouter({
  mode: 'history',
  routes: [
    {
      path: '/',
      redirect: {
          name: "Login"
      }
    },
    { path: '/login', component: Login, name: 'Login'},
    { path: '/', component: App, name: 'App',
      children: [
        { path: 'Home', component: Home ,name: 'Home' },
        { path: 'Pessoas', component: Pessoas, name: 'Pessoas'},
    ]},
    { path: '*', component: P404, name: '404'}
  ]
})

//const route_paths = ['Home','Contestacoes','parceiros','Pessoas','admins',
                     // 'solicitacao','cadastro','entregas','acerto','tabela','saldo']

const axios = require('axios');


const store = new Vuex.Store({
  plugins: [createPersistedState()],
  state: {
    count: 0,
    token: null,
    user: {
      id: '',
      usuario: '',
      foto_perfil: '',
      desativado: '',
    },
  },
  mutations: {
    increment (state) {
      state.count++
    },
    LOGIN_TOKEN(state, payload) {
      state.token = payload;
      axios.get(sessionStorage.getItem('url')+'/api/usuario-autenticado', {
        headers: {'Authorization': 'Bearer ' + state.token,'Content-type': 'application/json; charset=utf-8'},})
        .then(res => {
          state.logado = true;
          state.user.usuario = res.data.usuario;
          state.user.id = res.data.id;
          state.user.desativado = res.data.desativado;
          state.user.foto_perfil = res.data.foto_perfil;
        });
    },
    SET_DESATIVADO(state,payload){
      state.user.desativado = payload;
    },
    SET_FOTO(state,payload){
      state.user.foto_perfil = payload;
    },
    DESTROY_USER(state){
      state.token = null;
      state.user.usuario = ''
      state.user.id = ''
      state.user.desativado = ''
      state.user.foto_perfil = ''
    },
    GET_USER(state,payload){
      state.user.usuario = payload.usuario;
      state.user.id = payload.id;
      state.user.desativado = payload.desativado;
      state.user.foto_perfil = payload.foto_perfil;
    }
  },
  getters:{
    getDesativado(state){
      return state.user.desativado;
    },
    getUsuario(state){
      return state.user.usuario;
    },
    getFotoPerfil(state){
      return state.user.foto_perfil;
    },
    getToken(state){
      return state.token;
    },
    getId(state){
      return state.user.id;
    },
  }
})

router.beforeEach((to, from, next) => {

  if(to.path === '/' || to.path === '/login'){
    store.commit('DESTROY_USER')
    next();
  }
  else if(to.path !== '/' || to.path != '/login') {
    if(!store.getters.getToken){
      router.push('/');
    }
    else{
      next()
    }
  }
})

new Vue({
  render: h => h(App),
  router, store
}).$mount('#app')
