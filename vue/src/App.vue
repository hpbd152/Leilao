<template>
  <v-app>
    <v-toolbar height="70px" app dark color="#ea8b1d" v-if="checa_page($route.name)">
      <v-toolbar-side-icon @click.stop="drawer = !drawer"><v-icon medium class="">menu</v-icon></v-toolbar-side-icon>
      <span class="" @click="index" style="cursor:pointer; ">
          <img src="./assets/logoVerde.png" style="width: 150px; margin-top:8px; margin-left:10px;">
      </span>
      <v-spacer></v-spacer>

      <v-btn icon @click="logout()">
        <v-icon>exit_to_app</v-icon>
      </v-btn>
  </v-toolbar>

    <v-navigation-drawer
      class="fixed-top"
      style="width: 255px;"
      v-model="drawer"
      dark
      temporary
      v-if="checa_page($route.name)"
    >
      <v-list class="pa-1">
        <v-list-tile avatar>
          <v-list-tile-avatar>
            <v-avatar
            size="45"
            color="grey lighten-4"

          >
            <v-img :src="getUrlImg()" ></v-img>

          </v-avatar>
          </v-list-tile-avatar>

          <v-list-tile-content>
            <v-list-tile-title v-if="checa_page($route.name)">{{getUsuario}}</v-list-tile-title>
          </v-list-tile-content>
        </v-list-tile>
      </v-list>
      <v-divider></v-divider>

      <v-list>

        <!-- EXIBINDO ITENS MENU SEM SUBMENU -->
        <div v-for="item in items"
          :key="item.title"
        >
          <v-list-tile
            v-if="temPermissao(item)"
            @click="$router.push(item.path)"
          >
            <v-list-tile-action >
                <v-icon>{{ item.icon }}</v-icon>
            </v-list-tile-action>
            <v-list-tile-title v-text="item.title"></v-list-tile-title>
          </v-list-tile>
        </div>  
      </v-list>

    </v-navigation-drawer>

    <v-content :class="getPadding" >
      <router-view/>
    </v-content>
  </v-app>
</template>

<script>
const axios = require('axios');
export default {
  name: 'App',
  components: {
  },
  data () {
    return {
      drawer: null,
      items: [
        { title: 'Leilões', icon: 'gavel', path: '/Home'},
        { title: 'Pessoas', icon: 'people', path: '/Pessoas'}
      ],
    }
  },


  computed: {
    getDesativado(){
      return this.$store.getters.getDesativado;
    },
    getUsuario(){
      return this.$store.getters.getUsuario;
    },
    getFotoPerfil(){
      return this.$store.getters.getFotoPerfil;
    },
    getId(){
        return this.$store.getters.getId;
    },
    getToken(){
      return this.$store.getters.getToken;
    },
    getPadding(){
      if(this.$route.name != 'Login')
        return 'pt-5';
      else
        return '';
    }
  },
  mounted () {
  },
  created () {
    this.url = 'http://127.0.0.1:8000';
    this.qnt = null;
    sessionStorage.setItem('url', this.url)

    if(this.getToken){
      axios.get(sessionStorage.getItem('url')+'/api/usuario-autenticado', {
        headers: {'Authorization': 'Bearer ' + this.getToken}})
        .then(response => {
          if(response.data.status == 'Token is Expired'){
            this.$store.commit('DESTROY_USER')
            this.$router.push('/');
          }
          this.$store.commit('GET_USER', response.data)
          if(response.data.desativado == 1){
            this.$router.push('/');
          }
          this.initialize();
        });
    } else {
      this.logout
    }

  },

  methods: {
    checa_page(page){
      if(page!='404' && page!='Login'){
        return true;
      }
      else
        return false;
    },
    initialize(){

    },
    logout () {
      this.$store.commit('DESTROY_USER')
      this.$router.push('/');
    },

    index(){
      this.$router.push('/Home');
    },

    temPermissao(item) {
      return true;
    },
    getUrlImg () {
      return require('@/assets/default1.jpg')
    },
  }
}
</script>

<style>
  .fixed-top{
    position: fixed;
    top: 0px;
  }

.badge {
  position: absolute;
  top: -12px;
  right: -2px;
  height: 20px;
  width: 20px;
  padding: 0px 6px;
  border-radius: 50%;
  background: red;
  color: white;
}
</style>

