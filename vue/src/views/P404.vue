<template>
	<div id="notfound">
		<div class="notfound">
			<div class="notfound-404">
				<h1 class="text-xs-center black--text">404</h1>
			</div>
			<h2>Página não encontrada.</h2>
			<v-btn color="#00a65a" dark @click="$router.push('/Home')">
				Voltar para o início
			</v-btn>
		</div>
	</div>
</template>
<script>
export default {
  metaInfo: {
      title: 'Sistema Leilão',
      titleTemplate: '404 - %s',
    },
    $_veeValidate: {
      validator: 'new'
    },
	name:'P404'
}
</script>

<style>
* {
  -webkit-box-sizing: border-box;
          box-sizing: border-box;
}

body {
  padding: 0;
  margin: 0;
}

#notfound {
  position: relative;
  height: 100vh;
}

#notfound .notfound {
  position: absolute;
  left: 50%;
  top: 50%;
  -webkit-transform: translate(-50%, -50%);
      -ms-transform: translate(-50%, -50%);
          transform: translate(-50%, -50%);
}

.notfound {
  max-width: 410px;
  width: 100%;
  text-align: center;
}

.notfound .notfound-404 {
  height: 180px;
  position: relative;
  z-index: -1;
}

.notfound .notfound-404 h1 {
  font-family: 'Montserrat', sans-serif;
  font-size: 130px;
  margin: 0px;
  font-weight: 900;
  position: absolute;
  left: 50%;
  -webkit-transform: translateX(-50%);
      -ms-transform: translateX(-50%);
          transform: translateX(-50%);
  background-size: cover;
  background-position: center;
}


.notfound h2 {
  font-family: 'Montserrat', sans-serif;
  color: #000;
  font-size: 24px;
  font-weight: 400;
  margin-top: 0;
  margin-bottom: 20px;
}

</style>