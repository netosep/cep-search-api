<h1 align="center">
  cep-search-api 📍
</h1>

<div align="center">
  <p>Simples API para consulta de ceps aninhados utilizando o <a href="https://viacep.com.br/"><i>ViaCEP</i></a></p>
  <p>Desenvolvida com <a href="https://laravel.com">Laravel 11</a></p>
</div>

## :nazar_amulet: Instalação

### Passos de Instalação

1. Clone o repositório para a sua máquina local:

    ```bash
    git clone https://github.com/netosep/cep-search-api.git && cd cep-search-api
    ```

2. Instale as dependencias e gere a key da aplicação executando o comando:

    ```bash
    composer install
    ```

3. Suba a aplicação localmente
    ```bash
    php artisan serve
    ```

## :dizzy: Acesso

#### Após de executar os passos de instalação, a consulta estará disponivel no endpoint em: `/api/search/local/{ceps}`

Exemplo: acessando [`http://localhost:8000/api/search/local/01001000,17560-246`](http://localhost:8000/api/search/local/01001000,17560-246)

O retorno esperado deve ser:

```json
[
    {
        "cep": "17560246",
        "label": "Avenida Paulista, Vera Cruz",
        "logradouro": "Avenida Paulista",
        "complemento": "de 1600/1601 a 1698/1699",
        "bairro": "CECAP",
        "localidade": "Vera Cruz",
        "uf": "SP",
        "ibge": "3556602",
        "gia": "7134",
        "ddd": "14",
        "siafi": "7235"
    },
    {
        "cep": "01001000",
        "label": "Praça da Sé, São Paulo",
        "logradouro": "Praça da Sé",
        "complemento": "lado ímpar",
        "bairro": "Sé",
        "localidade": "São Paulo",
        "uf": "SP",
        "ibge": "3550308",
        "gia": "1004",
        "ddd": "11",
        "siafi": "7107"
    }
]
```

## :dart: Testes

Para rodar os testes, use o comando abaixo:

```bash
php artisan test
```

## :file_folder: Estrutura do Projeto

-   `app/` - Contém os arquivos principais da aplicação.
-   `routes/` - Contém as definições de rotas da aplicação.
-   `tests/` - Contém os testes automatizados.

#

<p align="center">
  <i>Developed with 🖤 by <a href="https://github.com/netosep">Neto Sepulveda</a></i>
</p>
