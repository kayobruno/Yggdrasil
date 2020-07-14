# Yggdrasil

Yggdrasil na mitoligia nordica é uma árvore colossal, que é o eixo do mundo. Então nada mais justo do que nomear este projeto como o eixo para qualquer API utilizando Laravel.


## Pré-requisitos
Para que o nosso projeto funcione, é necessário [PHP](http://php.net/) 7.2.0 ou superior e [Composer](https://getcomposer.org/), para instalação das dependências.

## Instalação

Execute o comando abaixo para instalar as dependências com o seguinte comando:

```bash
composer install
```

Execute o comando abaixo para criar o arquivo de configuração das variáveis ​​de ambiente.
```bash
cp .env.example .env
```

Certifique-se que os dados informados no `.env` estão configurados corretamente. 
Execute o comando abaixo para criar o esquema do banco e inserir dados iniciais.
```bash
php artisan migrate --seed
```

Execute o comando abaixo para criar o "secret key" do JWT
```bash
php artisan jwt:secret
```

## O que podemos encontrar nesta estrutura?

### Autenticação com JWT
- Login
- Login com Facebook e Google
- Recuperação de senha
- Atualização de Token

### Tipos de usuários e permissões
- Foi utilizado a biblioteca [Entrust](<https://github.com/Zizaco/entrust>) para criar os tipos de usuário e permissoẽs.
Esta biblioteca é excelente e tem muita funcionalidade bacana, então sinta-se à vontade para personalizar ainda mais seus tipos de usuário/permissões.

### Localização
- Foi criado um Middleware para tratar as traduções em qualquer rota, basta informar o parâmetro `X-localization` no `Header` com o idioma que deseja utilizar.

### Documentação com Swagger
- Todas as rotas de usuário foram documentadas utilizando `annotations` do Swagger. Então isso deve servir de base para documentar suas rotas.
