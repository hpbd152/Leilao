<template>
  <v-container class="login" fluid fill-height >
    <v-layout align-center justify-space-around>
      <v-flex xs11 sm7 md5 lg4 xl3 >
        <v-card class="elevation-12">
          <v-toolbar extended dark color="#ea8b1d">
            <v-img src="https://i.imgur.com/ayQWWiT.png" max-width="450" min-width="200" style="margin-top: 65px" aspect-ratio="3.5" contain></v-img>
          </v-toolbar>
          <v-card-text>
            <v-form>
              <v-text-field 
                color="#ea8b1d" 
                v-model="user.usuario" 
                prepend-icon="person" 
                name="login" 
                label="Usuário" 
                placeholder="Insira seu usuário aqui"
                type="text"
                autocomplete="username" 
                :rules="usuarioRules"></v-text-field>
              <v-text-field
                color="#ea8b1d"
                v-model="user.password"
                prepend-icon="lock" 
                name="Senha" 
                label="Senha" 
                placeholder="Insira sua senha aqui"
                id="password" 
                min="5"
                :append-icon="senha_visivel ? 'visibility' : 'visibility_off'"
                @click:append = "() => (senha_visivel = !senha_visivel)"
                :type="senha_visivel ? 'text' : 'password'"
                :rules="passwordRules"
                required
                autocomplete="current-password"
                @keyup.enter="submit()"
                ></v-text-field>
            </v-form>
          </v-card-text>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn color="#ea8b1d" dark @click="submit()" :loading="carregando">Login</v-btn>
          </v-card-actions>
        </v-card>
      </v-flex>
    </v-layout>
    <v-dialog
      v-model="message_box"
      max-width="400"
    >
      <v-card>
        <v-card-title class="headline red--text" >{{titulo}}</v-card-title>
        <v-card-text>{{mensagem}}</v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn
            color="red darken-1"
            flat="flat"
            @click="message_box = false;"
          > Ok
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-container>
</template>

<script>
  const axios = require('axios');
  export default {
      metaInfo: {
        title: 'Sistema Leilão',
        titleTemplate: 'Login - %s',
      },
      $_veeValidate: {
        validator: 'new'
      },
      data () {
        return {
          mensagem: '',
          titulo: '',
          message_box: false,
          carregando: false,
          user: {
            usuario: '',
            password: ''
          },
          senha_visivel: false,
          password: '',
          passwordRules: [
            (v) => !!v || 'Senha é obrigatória',
          ],
          usuarioRules: [
            (v) => !!v || 'Usuário é obrigatório'
          ],
        }
      },
      created(){

      },
      methods: {
        submit () {
          this.carregando = true;
          axios.post(sessionStorage.getItem('url')+'/api/login', this.user )
          .then(response => {
            this.$store.commit('LOGIN_TOKEN', response.data)
            /*if(res.data.desativado == 0)
              this.$router.push('/Home');
            else if(res.data.desativado == 2) 
              this.$router.push('/configuracoes');*/
            this.$router.push('/Home');

          })
          .catch(error => {
            this.titulo = 'ERROR'
            this.message_box = true;
            this.mensagem = error;
            this.carregando = false;
            if(this.mensagem == 'Error: Request failed with status code 400')
              this.mensagem = "Usuário ou senha incorreta"
            else if(this.mensagem == 'Error: Request failed with status code 500'){
              this.titulo = 'ERROR 500'
              this.mensagem = "Acesso negado ao banco de dados (.env)"
            }
          });
      },
        clear () {
          this.$refs.form.reset()
        }
      }
    }
 </script>
 <style>
    .login {
      background-color: #dbdbdb;
      background-size: cover;
      overflow:hidden;
    }

    .fundoLogo {
      background-size: contain;
      overflow:hidden;
      margin: auto;
    }

    .logo {
      background-image: url("./assets/logoVerde.png");
      background-size: contain;
      overflow:hidden;
      margin: auto;
      max-width: 90%;
      padding: auto;
      align-self: center;
    }

    .loginOverlay {
    background:rgba(33,150,243,0.3);
    }

    .photoCredit{
    position: absolute;
    bottom: 15px;
    right: 15px;
    }
  .dialog {
    z-index: inherit
  }
</style>
