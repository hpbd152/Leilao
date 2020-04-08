<template>
  <v-container align-center>
    <v-container fluid>
        <v-layout align-center justify-space-between row wrap>
          <v-flex xs8 >
            <h3 class="headline mb-0">Leilões ativos no momento</h3>
           <v-btn
            color="#ea8b1d" dark
            class="mt-4"
            @click="cria_anuncio"
          >
            Criar Leilão
          </v-btn>
          </v-flex>
          <v-flex xs4>
              <v-text-field
                color="#ea3b2e"
                v-model="busca_search"
                append-icon="search"
                label="Palavras chave"
                single-line
                hide-details
              ></v-text-field>
            </v-flex>

          </v-layout>
          </v-container>
    <br>
    <v-layout>
      <v-layout row wrap>
        <v-flex v-for="anuncio in leiloes_filtro" :key="anuncio.id_leilao" lg3 md4 sm6 xs12>
          <v-card class="ma-2">
            <v-img
              v-if="anuncio.url_foto != null"
              class="white--text"
              height="200px"
              :src="anuncio.url_foto"
            ></v-img><v-img
              v-else
              class="white--text"
              height="200px"
              src="https://www.nicepng.com/png/detail/304-3048415_business-advice-product-icon-png.png"
            ></v-img><v-divider></v-divider>
            <v-card-title>
              <div>
                <h3>{{anuncio.titulo}}</h3>
                <span v-if="anuncio.lance_vencedor">Maior Lance: R$ {{anuncio.lance_vencedor}} </span>
                <span v-else>Nenhum Lance foi dado neste Leilão</span>
              </div>
            </v-card-title>
            <v-card-actions>
              <v-btn flat color="green" @click="compra_leilao(anuncio)">Dar Lance</v-btn>
              <v-btn flat color="orange" @click="seleciona(anuncio)">Ver detalhes</v-btn>
            </v-card-actions>
          </v-card>
        </v-flex>
      </v-layout>
    </v-layout>

    <v-dialog v-model="dialog" max-width="650px" v-if="item_selecionado">
      <v-card>
        <v-toolbar dark color="black">
          <v-toolbar-title>{{item_selecionado.titulo}}</v-toolbar-title>
          <v-spacer></v-spacer>
            <v-btn icon dark @click="dialog = false">
              <v-icon >close</v-icon>
            </v-btn>
          </v-toolbar>
        <v-img
          v-if="item_selecionado.url_foto != null"
          class="white--text"
          height="355px"
          :src="item_selecionado.url_foto"
        ></v-img><v-img
          v-else
          class="white--text"
          height="355px"
          src="https://www.nicepng.com/png/detail/304-3048415_business-advice-product-icon-png.png"
        ></v-img><v-divider></v-divider>
        <v-card-text>
          <v-container grid-list-md>
            <v-layout row wrap>
              <v-flex xs4>
                <v-text-field background-color='white' readonly outline hide-details required="" v-model="computedLance" label="Lance Vencendo"></v-text-field>
              </v-flex>
              <v-flex xs4>
                <v-text-field background-color='white' readonly outline hide-details required="" v-model="item_selecionado.condicao" label="Condição"></v-text-field>
              </v-flex>
              <v-flex xs4>
                <v-text-field background-color='white' readonly outline hide-details required="" v-model="computedDate" label="Data de Fim"></v-text-field>
              </v-flex>
              <v-flex xs12>
                <v-textarea auto-grow background-color='white' readonly outline hide-details required="" v-model="item_selecionado.descricao" label="Descrição do Leilão"></v-textarea>
              </v-flex>
            </v-layout>
          </v-container>
          <v-card-actions>
            <v-btn color="green darken-1" flat @click="compra_leilao(item_selecionado)">Dar lance</v-btn>
            <v-btn color="blue darken-1" flat @click="editar_dialog(item_selecionado)">Editar anúncio</v-btn>
            <v-btn color="red darken-1" flat :loading="lancamento.loading" @click="excluir_leilao(item_selecionado)">Excluír anúncio</v-btn>
          <v-spacer></v-spacer>
        </v-card-actions>
        </v-card-text>
      </v-card>
    </v-dialog>

    <v-dialog v-model="edita_dialog" max-width="500px" v-if="edita_item" persistent>
      <v-card>
        <v-toolbar dark color="#ea8b1d">
          <v-toolbar-title>Editar Leilão</v-toolbar-title>
          <v-spacer></v-spacer>
          <v-btn icon dark @click="edita_dialog = false">
            <v-icon >close</v-icon>
          </v-btn>
        </v-toolbar>
        <v-divider></v-divider>
        <v-card-text>
          <v-container grid-list-md>
            <v-layout row wrap>
              <v-flex xs7>
                <v-text-field outline hide-details background-color='#f7f2f2' required="" v-model="edita_item.titulo" label="Título"></v-text-field>
              </v-flex>
              <v-flex xs5>
                <v-select
                  :items="tipos"
                  label="Condição"
                  v-model="edita_item.condicao"
                  background-color='#f7f2f2'
                  outline
                ></v-select>
              </v-flex>
              <v-flex xs12 style="margin-top: -30px;">
                <v-text-field outline hide-details background-color='#f7f2f2' required="" v-model="edita_item.url_foto" label="URL da imagem"></v-text-field>
              </v-flex>
              <v-flex xs12>
                <v-textarea auto-grow outline hide-details background-color='#f7f2f2' required="" v-model="edita_item.descricao" label="Descrição do Leilão"></v-textarea>
              </v-flex>
            </v-layout>
          </v-container>
          <v-layout row wrap justify-end="">
            <v-btn color="red darken-1" flat @click="edita_dialog = false">Cancelar</v-btn>
            <v-btn color="green darken-1" flat :loading="edita_loading" @click="editar_leilao(edita_item)">Salvar Edição</v-btn>
          </v-layout>
        </v-card-text>
      </v-card>
    </v-dialog>

    <v-dialog
      v-model="lancamento.dialog"
      max-width="400"
    >
      <v-card>
        <v-card-title class="headline green--text">{{lancamento.titulo}}</v-card-title>
        <v-card-text>{{lancamento.message}}</v-card-text>
        <v-layout row wrap v-if="lancamento.input">
        <v-flex xs6 v-if="lancamento.input">
          <v-text-field class='ml-3' background-color='#f7f2f2' outline hide-details required="" v-model="lancamento.lance" label="Seu lance"></v-text-field>
        </v-flex>
        <v-flex xs6 v-if="lancamento.input">
        <v-btn
            large
            color="green darken-1"
            flat="flat"
            :loading="lancamento.loading"
            @click="dar_lance"
          > DAR LANCE
          </v-btn>
        </v-flex>
        </v-layout>
        <v-card-actions>

          <v-spacer></v-spacer>
          <v-btn
            v-if="lancamento.input == false"
            color="green darken-1"
            flat="flat"
            @click="lancamento.dialog = false"
          > Ok
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <v-dialog
        v-model="msg.dialog"
        max-width="400"
      >
      <v-card>
        <v-card-title v-if="msg.error == false" class="headline green--text">{{msg.titulo}}</v-card-title>
        <v-card-title v-if="msg.error == true" class="headline red--text">{{msg.titulo}}</v-card-title>
        <v-card-text>{{msg.message}}</v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn
            color="green darken-1"
            flat="flat"
            @click="msg.dialog = false"
          > Ok
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <v-dialog v-model="cadastrar_anuncio" max-width="800px" persistent>
      <v-card>
        <v-toolbar dark>
          <v-toolbar-title>Cadastrar novo Leilão</v-toolbar-title>
          <v-spacer></v-spacer>
          <v-btn icon dark @click="cadastrar_anuncio = false">
            <v-icon >close</v-icon>
          </v-btn>
        </v-toolbar>
        <v-card-text>
          <v-container grid-list-md>
            <v-layout row wrap>
              <v-flex xs8>
                <v-text-field background-color='#f7f2f2' outline hide-details required="" v-model="cadastro.titulo" label="Título do Leilão"></v-text-field>
              </v-flex>
              <v-flex xs4>
                <v-text-field background-color='#f7f2f2' :disabled="disable" outline hide-details required="" v-model="cadastro.valor_inicial" label="Valor Inicial"></v-text-field>
              </v-flex>
              <v-flex xs12>
                <v-textarea auto-grow background-color='#f7f2f2' outline hide-details required="" v-model="cadastro.descricao" label="Descrição do Leilão"></v-textarea>
              </v-flex>
              <v-flex xs3>
                <v-select
                  :items="tipos"
                  label="Condição"
                  v-model="cadastro.condicao"
                  background-color='#f7f2f2'
                  outline
                ></v-select>
              </v-flex>
              <v-flex xs9>
                <v-text-field background-color='#f7f2f2' outline hide-details required="" v-model="cadastro.url_foto" label="URL da imagem (Opcional)"></v-text-field>
              </v-flex>

            </v-layout>
          </v-container>
          <v-card-actions>
            <v-btn color="green darken-1" flat @click="cadastrar_leilao" :loading="cadastro.loading">Criar Leilão</v-btn>
            <v-btn color="red darken-1" flat @click="cadastrar_anuncio = false">Cancelar</v-btn>
          <v-spacer></v-spacer>
        </v-card-actions>
        </v-card-text>
      </v-card>
    </v-dialog>
  </v-container>
</template>
<script>
  import { mapGetters } from 'vuex'
  const axios = require('axios');
  export default {
    metaInfo: {
      title: 'Sistema Leilão',
      titleTemplate: 'Página Inicial - %s',
    },
    $_veeValidate: {
      validator: 'new'
    },
    data: vm => ({
      edita_loading: false,
      edita_dialog: false,
      edita_item: null,
      lancamento:{
        loading: false,
        dialog: false,
        message: '',
        titulo: '',
        input: false,
        lance: null
      },
      msg:{
        error: null,
        dialog: false,
        message: '',
        titulo: '',
      },
      lance:{
        vencendo: null,
        id_leilao: null,
        valor: null
      },
      cadastrar_anuncio: false,
      disable: false,
      cadastro:{
        loading: false,
        titulo: null,
        url_foto: null,
        valor_inicial: null,
        descricao: null,
        condicao: null,
      },
      dialog: false,
      item_selecionado: null,
      busca_search: '',
      tipos: ['Novo', 'Usado'],
      leiloes_filtro:[],
      leiloes:[],
    }),
    
    created () {
      if(this.$store.getters.getDesativado == 2){
        this.$router.push('/configuracoes');
      }
      if(this.$store.getters.getDesativado == 1){
        sessionStorage.removeItem('urt');
        this.$router.push('/');
      }

      this.initialize();
    },
    
    computed:{

      computedLance() {
        if(this.item_selecionado.lance_vencedor){
          var money = this.item_selecionado.lance_vencedor;
          if(money.toString().includes(".") == false){
            return "R$" + money + ',00';
          }
          else{
            return "R$" + money.toString().replace(".", ",");
            }
          }
        else return 'R$ 0,00';
      },

      computedDate(){

        if (!this.item_selecionado.data_finalizacao)
          return null
        if(this.item_selecionado.data_finalizacao == null)
          return null

        const [year, month, dayHours] = this.item_selecionado.data_finalizacao.split('-')
        const [day, hours] = dayHours.split(' ')

        return `${day}/${month}/${year} ${hours.substring(0,5)}`
      },
 
      getNome(){
        return this.$store.getters.getNome;
      },
      getToken(){
        return this.$store.getters.getToken;
      },
    },

    watch: {
      busca_search (val) {
        this.leiloes_filtro = this.leiloes.filter(anuncio => this.removeAcento(anuncio.titulo).toLowerCase().includes(this.removeAcento(val).toLowerCase()))
      }
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

      reset(){

        this.edita_dialog = false;
        this.cadastro.loading = false;
        this.cadastro.titulo = null;
        this.cadastro.url_foto = null;
        this.cadastro.valor_inicial = null;
        this.cadastro.descricao = null;
        this.cadastro.condicao = null;
        this.cadastrar_anuncio = null;
        this.dialog = false;
        this.lance.vencendo = null;
        this.lance.id_leilao = null;
        this.lance.valor = null;
        this.lancamento.lance = null;
      },

      initialize(){
        this.reset();
        this.get_leilao();
      },

      get_leilao(){

        axios.get('http://localhost:8000/api/get-leilao', {
        headers: {'Authorization': 'Bearer ' + this.getToken}})
        .then(response => {
          this.leiloes = response.data
          this.leiloes_filtro = this.leiloes;
        })

      },
      cria_anuncio(){
        this.cadastrar_anuncio = true;
      },
      compra_leilao(item){
          this.lancamento.dialog = true;
          this.lancamento.input = true;
          this.lancamento.titulo =item.titulo;
          this.lance.vencendo = item.lance_vencedor;
          this.lance.id_leilao = item.id_leilao;
          if(item.lance_vencedor){
            this.lancamento.message = 'Lance vencendo: R$' + item.lance_vencedor;
          } else{
            this.lancamento.message = 'Seja o primeiro a dar um Lance!';
          }
        
      },

      seleciona(item){

        this.dialog = true;
        this.item_selecionado = item;
        this.item_selecionado.valor_inicial = item.lance_vencedor;

      },

      editar_dialog(item){
        this.edita_dialog = true;
        this.edita_item = item;
      },

      editar_leilao(item){
        this.edita_dialog = true;
        this.edita_item = item;
        this.edita_loading = true;

        axios
          .put('http://localhost:8000/api/editar_leilao', item, {
          headers: {'Authorization': 'Bearer ' + this.getToken}})
          .then(response => {
            this.edita_loading = false;
            this.lancamento.dialog = false
            this.msg.dialog = true;
            this.msg.error = response.data.error;
            this.msg.titulo = response.data.titulo;
            this.msg.message = response.data.message;
            this.initialize();
          })
          .catch(error => {
            this.edita_loading = false;
            this.lancamento.dialog = false
            this.msg.dialog = true;
            this.msg.error = true;
            this.msg.titulo ='ERROR';
            this.msg.message = 'Por favor preencha todos os campos obrigatórios';
          });

      },

      excluir_leilao(item){
        this.lancamento.loading = true;

        axios
          .put('http://localhost:8000/api/excluir_leilao', item, {
          headers: {'Authorization': 'Bearer ' + this.getToken}})
          .then(response => {
            this.lancamento.loading = false;
            this.lancamento.dialog = false
            this.msg.dialog = true;
            this.msg.error = response.data.error;
            this.msg.titulo = response.data.titulo;
            this.msg.message = response.data.message;
            this.initialize();
          })
          .catch(error => {
            this.lancamento.loading = false;
            this.lancamento.dialog = false
            this.msg.dialog = true;
            this.msg.error = true;
            this.msg.titulo ='ERROR';
            this.msg.message = error;
          });

      },

      dar_lance(){
        this.lancamento.loading = true;
        this.lance.valor = this.lancamento.lance;
        if(this.lance.valor > this.lance.vencendo){
          axios
            .post('http://localhost:8000/api/dar_lance', this.lance, {
            headers: {'Authorization': 'Bearer ' + this.getToken}})
            .then(response => {
              this.lancamento.loading = false;
              this.lancamento.dialog = false
              this.msg.dialog = true;
              this.msg.error = response.data.error;
              this.msg.titulo = response.data.titulo;
              this.msg.message = response.data.message;
              this.initialize();
            })
            .catch(error => {
              this.lancamento.loading = false;
              this.lancamento.dialog = false
              this.msg.dialog = true;
              this.msg.error = true;
              this.msg.titulo ='ERROR';
              this.msg.message = 'Por favor preencha todos os campos obrigatórios';
            });
        }
        else{
          this.lancamento.loading = false;
          this.lancamento.dialog = false
          this.msg.dialog = true;
          this.msg.error = true;
          this.msg.titulo ='Lance inválido';
          this.msg.message = 'Seu lance deve ser superior ao lance vencedor de R$' + this.lance.vencendo;
        }
      },

      cadastrar_leilao(){
        this.cadastro.loading = true;
        axios.post(sessionStorage.getItem('url')+'/api/criar-leilao', this.cadastro, {
        headers: {'Authorization': 'Bearer ' + this.getToken}})
        .then(response => {
            this.cadastro.loading = false;
            this.msg.dialog = true;
            this.msg.error = response.data.error;
            this.msg.titulo = response.data.titulo;
            this.msg.message = response.data.message;
            this.initialize();
          })
          .catch(error => {
            this.cadastro.loading = false;
            this.msg.dialog = true;
            this.msg.error = true;
            this.msg.titulo ='ERROR';
            this.msg.message = 'Por favor preencha todos os campos obrigatórios';
          });
      }
    
    }
  }
</script>