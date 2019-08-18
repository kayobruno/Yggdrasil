# Zeus

Estrutura base para API em Laravel

## Pré-requisitos
Para execução, é necessário [PHP](http://php.net/) 7.2.0 ou superior e [Composer](https://getcomposer.org/), para instalação das dependências.

## Instalação

Instalar as dependências com o seguinte comando:

```bash
composer install
```

Execute o comando abaixo para criar o arquivo de configuração das variáveis ​​de ambiente.
```bash
cp .env.example .env
```

Execute o comando abaixo para criar o esquema do banco de dados
```bash
php artisan migrate
```

Execute o comando abaixo para criar o "secret key" do JWT
```bash
php artisan jwt:secret
```
