<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Leilao;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {   
        $user_default = User::create([
            'usuario' => 'Generico',
            'password' => bcrypt("123")
        ]);

        User::create([
            'usuario' => 'Henrique',
            'password' => bcrypt("123")
        ]);

        User::create([
            'usuario' => 'Convidado',
            'password' => bcrypt("123")
        ]);

        $agora = date("Y-m-d H:i:s");

        Leilao::create([
            'titulo' => 'Playstation 4',
            'descricao' => 'Graças à sua interconectividade global, você baixará os melhores jogos de vídeo e navegará na web sem limites. Por sua vez, a possibilidade de competir online com outras pessoas fará que você desfrute de aventuras inesquecíveis com amigos e pessoas de todo o mundo. Este console é considerado o melhor dentro do mercado, pois possui uma resolução de até 4K. Sua conexão wifi lhe permitirá reproduzir músicas, filmes e suas séries favoritas através dos aplicativos descarregáveis.',
            'valor_inicial' => '800',
            'lance_vencedor' => '800',
            'condicao' => 'Novo',
            'url_foto' => 'https://i2.wp.com/www.reviewbox.com.br/wp-content/uploads/2019/06/playstation-4.jpg?resize=1024%2C683&ssl=1',
            'user_id' => $user_default->id,
        ]);

        Leilao::create([
            'titulo' => 'Pintura Cavalo Branco',
            'descricao' => 'Sobre o Artista: Valdomiro Manuel Bizarria Brasileiro, solteiro, Pernambuco nascido em 28/08/85, auto didatico. Realiza trabalhos sob encomenda e já realizou exposições de seus trabalhos em diversos shoppings, onde podemos destacar o shopping internacional de Guarulhos. Em seus trabalhos realiza trabalhos de rostos humanos, paissagens, animais, casas, etc. Para contato com o artista, ligue no fone (31) 2693-0000.',
            'valor_inicial' => '450',
            'lance_vencedor' => '450',
            'condicao' => 'Novo',
            'url_foto' => 'https://www.leiloeirosp.com.br/leiloes/lotes/imagens/547f6b50f19fd.jpg',
            'user_id' => $user_default->id,
        ]);

        Leilao::create([
            'titulo' => 'Fusca Cinza',
            'descricao' => 'Carro em excelente estado! Chama no zap (37) 99452-8574',
            'valor_inicial' => '15000',
            'lance_vencedor' => '15000',
            'condicao' => 'Usado',
            'url_foto' => 'https://img.olx.com.br/images/57/576918085515696.jpg',
            'user_id' => $user_default->id,
        ]);

        Leilao::create([
            'titulo' => 'Iphone 5s',
            'descricao' => 'Celular em excelente estado, acompanha nota fiscal',
            'valor_inicial' => '800',
            'lance_vencedor' => '800',
            'condicao' => 'Usado',
            'url_foto' => 'https://img.olx.com.br/images/18/188026010834208.jpg',
            'user_id' => $user_default->id,
        ]);

        Leilao::create([
            'titulo' => 'Phantasy Star 1',
            'descricao' => 'Edição de colecionador',
            'valor_inicial' => '12000',
            'lance_vencedor' => '12000',
            'condicao' => 'Usado',
            'url_foto' => 'https://ucegamers.com.br/siteuceg/wp-content/uploads/2017/05/boxarten.jpg',
            'user_id' => $user_default->id,
        ]);

        Leilao::create([
            'titulo' => 'Cachorro de pelúcia',
            'descricao' => 'Cachorro bem macio, ideal para abraçar.',
            'valor_inicial' => '1500',
            'lance_vencedor' => '1500',
            'condicao' => 'Usado',
            'url_foto' => 'https://s2.glbimg.com/slaVZgTF5Nz8RWqGrHRJf0H1PMQ=/0x0:800x450/984x0/smart/filters:strip_icc()/i.s3.glbimg.com/v1/AUTH_59edd422c0c84a879bd37670ae4f538a/internal_photos/bs/2019/U/e/NTegqdSe6SoBAoQDjKZA/cachorro.jpg',
            'user_id' => $user_default->id,
        ]);


    }
}

