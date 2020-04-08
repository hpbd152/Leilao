<template>
  <v-container>
    
     <v-container fluid>
      <v-layout row wrap>
        <v-flex xs12 sm4 md3>
          <h3 class="headline">Pessoas</h3>
        </v-flex>
        <v-flex xs12 sm5 md6>
          <v-text-field
            color="#00a65a"
            v-model="search"
            append-icon="search"
            label="Pesquisar"
            single-line
            hide-details
          ></v-text-field>
        </v-flex>
        
      </v-layout>
    </v-container>


    <v-data-table
      :headers="headers"
      :items="dados_filtrados"
      :rows-per-page-items="table_items_page"
    >
      <template v-slot:items="props">
        <td class="text-xs-center">#{{ props.item.id }}</td>
        <td class="text-xs-center">{{ props.item.usuario }}</td>
        <td class="text-xs-center" v-if="props.item.desativado == 0"> Ativo</td>
        <td class="text-xs-center" v-else>Desativado</td>
        <td class="text-xs-center">{{props.item.created_at }}</td>
        <td class="text-xs-center">
          <v-icon
            class="mr-2"
            @click="editItem(props.item)"
          >
            edit
          </v-icon>
        </td>
      </template>
      <template v-slot:no-data>
        <v-btn color="primary" @click="initialize">Recarregar</v-btn>
        <v-progress-circular
              v-if="loading == false"
              :size="40"
              color="primary"
              indeterminate
            ></v-progress-circular>
      </template>
    </v-data-table>

    <v-dialog v-model="dialog" max-width="550px" persistent>
        <v-card>
          <v-toolbar color="#ea8b1d" dark>
            <v-toolbar-title>Editar Usuário</v-toolbar-title>
            <v-spacer></v-spacer>
            <v-btn icon dark @click="close">
              <v-icon >close</v-icon>
            </v-btn>
          </v-toolbar>
          <v-card-text>
            <v-container grid-list-md>
              <v-layout row wrap>
                <v-flex xs12 sm7>
                  <v-text-field 
                    background-color='#bfbfbf' 
                    v-validate="'required'"
                    :error-messages="errors.collect('obrigatorio')"
                    data-vv-name="obrigatorio"
                    outline required="" 
                    hide-details
                    v-model="editedItem.usuario" 
                    label="Usuário">
                  </v-text-field>
                </v-flex>
                <v-flex xs12 sm5>
                  <v-select
                    :items="tipos"
                    label="Situação"
                    v-model="editedItem.situacao"
                    background-color='#bfbfbf'
                    outline
                    v-validate="'required'"
                    :error-messages="errors.collect('obrigatorio2')"
                    data-vv-name="obrigatorio2"
                    orequired="" 
                    hide-details
                  ></v-select>
                </v-flex>
              </v-layout>
            </v-container>
          </v-card-text>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn color="green darken-1"  flat @click="save" :loading="carregando">Salvar Alterações</v-btn>
          </v-card-actions>
        </v-card>


      </v-dialog>
       <v-dialog
        v-model="message_box"
        max-width="400"
      >
        <v-card>
          <v-card-title class="headline red--text" v-if="mensagem.error">{{mensagem.titulo}}</v-card-title>
          <v-card-title class="headline green--text" v-else>{{mensagem.titulo}}</v-card-title>
          <v-card-text>{{mensagem.message}}</v-card-text>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn
              v-if="mensagem.error"
              color="red darken-1"
              flat="flat"
              @click="message_box = false;"
            > Ok
            </v-btn>
            <v-btn
              v-else
              color="green darken-1"
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
      titleTemplate: 'Pessoas - %s',
    },
    $_veeValidate: {
      validator: 'new'
    },
    name:'usuario',
    data: () => ({
      dialog: false,
      tipos: ['Ativo', 'Desativado'],
      table_items_page: [25,50,100,{"text":"$vuetify.dataIterator.rowsPerPageAll","value":-1}],
      search: '',
      headers: [
        { text: 'Id', align: 'center'},
        { text: 'Usuário', align: 'center'},
        { text: 'Situação', align: 'center' },
        { text: 'Datad de criação', align: 'center', sortable: false },
        { text: 'Ações', align: 'center', sortable: false }
      ],
      usuarios: [],
      dados_filtrados: [],
      editedIndex: -1,
      tipo_confirmacao: null,
      box_confirmacao: false,
      editedItem: {
        id: null,
        usuario: '',
        desativado: '',
        situacao: null
      },
      carregando_desat: false,
      carregando: false,
      message_box: false,
      mensagem: {
        error: null,
        message: '',
        titulo: ''
      },
      loading: false,
    }),

    created () {

      if(this.$store.getters.getDesativado == 1){
        sessionStorage.removeItem('urt');
        this.$router.push('/');
      }

      this.initialize();
    },

    computed: {
      getLogado(){
        return this.$store.getters.getLogado;
      },
      getDesativado(){
        return this.$store.getters.getDesativado;
      },
      getusuario(){
        return this.$store.getters.getusuario;
      },
      getTipo(){
        return this.$store.getters.getTipo;
      },
      getToken(){
        return this.$store.getters.getToken;
      },
    },

    watch: {
      search (val) {
        this.filtragem();
      },
    },

    methods: {
      removeAcento (text)
      {       
          text = text.toLowerCase();                                                         
          text = text.replace(new RegExp('[ÁÀÂÃ]','gi'), 'a');
          text = text.replace(new RegExp('[ÉÈÊ]','gi'), 'e');
          text = text.replace(new RegExp('[ÍÌÎ]','gi'), 'i');
          text = text.replace(new RegExp('[ÓÒÔÕ]','gi'), 'o');
          text = text.replace(new RegExp('[ÚÙÛ]','gi'), 'u');
          text = text.replace(new RegExp('[Ç]','gi'), 'c');
          return text;                 
      },
      initialize () {
        this.$validator.reset();
        this.dialog = false;
        this.box_confirmacao = false;
        this.carregando = false;
        this.carregando_desat = false;
          
        axios.get(sessionStorage.getItem('url')+ '/api/get-users', {
          headers: {'Authorization': 'Bearer ' + this.getToken},
        })
          .then(response => {
            if(response.data.status == 'Token is Expired'){
              this.$store.commit('DESTROY_USER')
              this.$router.push('/');
            }
            this.dados_filtrados = response.data;
            this.usuarios = response.data
            this.filtragem();
            this.loading = true
          })
          
      },

      editItem (item) {
        this.editedIndex = this.usuarios.indexOf(item)
        this.editedItem = Object.assign({}, item)
        console.log(this.editedItem)
        if(this.editedItem.desativado == 0)
          this.editedItem.situacao = "Ativo";
        else
          this.editedItem.situacao = "Desativado";
        
        this.dialog = true
      },

      close () {
        this.$validator.reset()
        this.carregando = false;
        this.box_confirmacao = false;
        this.carregando_desat = false;
        this.dialog = false;
        this.editedIndex = -1
      },

      filtragem () {
        
        this.dados_filtrados = this.usuarios;
        
        if(this.search)
          this.dados_filtrados = this.dados_filtrados.filter(biker => this.removeAcento(biker.usuario).toLowerCase().includes(this.removeAcento(this.search).toLowerCase()))
      
      },
      desabilita(desativado){
        if(desativado == 1)
          return true;
        else
          return false;
      },

      save () {
        this.carregando = true;
        this.$validator.validateAll().then( result => {

          if(result == false){
            this.message_box = true
            this.mensagem.error = false
            this.mensagem.message  = "Por favor preencha todos os campos"
            this.mensagem.titulo = "ERROR"
            this.carregando = false;
          }
          else{

            axios.put(sessionStorage.getItem('url') + '/api/edita-usuario', this.editedItem, {
              headers: {'Authorization': 'Bearer ' + this.getToken},
            })
              .then(response => {
                if(response.data.status == 'Token is Expired'){
                  this.$store.commit('DESTROY_USER')
                  this.$router.push('/');
                }
                this.message_box = true;
                this.mensagem = response.data
                this.$validator.reset()
                this.carregando = false;
                this.initialize();
                
              })
              .catch(error => {
                this.mensagem.error = false
                this.mensagem.message = error
                this.mensagem.titulo = "ERROR"
                this.message_box = true
                this.carregando = false;
                this.$validator.reset()
              });
        
          }
        })
      },
    }
  }
</script>