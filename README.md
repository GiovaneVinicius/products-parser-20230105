# Backend Challenge 20230105

Este é um desafio proposto pela Coodesh.

<br><br>

## Sobre o Desafio

Neste desafio, vamos trabalhar no desenvolvimento de uma REST API para acessar os dados do Open Food Facts, um banco de dados aberto que contém informações nutricionais de uma ampla variedade de produtos alimentícios.

<br><br>

## Sobre o Projeto

Para este projeto, foi utilizado o framework Laravel na sua versão 10 em conjunto com a linguagem de programação PHP (^8.1). 

<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

<br><br>

## Requisitos Obrigatórios

### Requisito 1
Criar um banco de dados MongoDB usando Atlas: https://www.mongodb.com/cloud/atlas ou algum Banco de Dados SQL se não sentir confortável com NoSQL;
* Decisão: Optei por utilizar o banco de dados MySQL para este projeto devido à sua prevalência e familiaridade na comunidade de desenvolvimento. Todas as instruções necessárias para configurar o banco de dados e acessar o PHPMyAdmin serão detalhadas na documentação fornecida no README do projeto.

### Requisito 2
Criar uma REST API com as melhores práticas de desenvolvimento, Design Patterns, SOLID e DDD;
* Decisão: Para atender aos requisitos de criar uma REST API com as melhores práticas de desenvolvimento, Design Patterns, SOLID e DDD, escolhi utilizar o framework Laravel. O Laravel é reconhecido por sua elegância, eficiência e robustez no desenvolvimento de aplicações web, incluindo APIs. Ele oferece uma ampla gama de recursos que facilitam a implementação de boas práticas de desenvolvimento, como:

    * Padrões de Design e SOLID: O Laravel segue muitos padrões de design e princípios SOLID, o que facilita a manutenção e extensibilidade do código.

    * Arquitetura DDD (Domain-Driven Design): O framework utilizado permite a criação de uma arquitetura de software baseada em domínio, separando claramente as camadas de aplicação, domínio e infraestrutura. Isso ajuda a manter um código mais organizado, facilitando a compreensão e a manutenção da aplicação.

    * Facilidade de Desenvolvimento: Com o Laravel, é possível desenvolver rapidamente uma API RESTful, graças a recursos como Eloquent ORM, sistema de roteamento simples e expressivo, middleware para tratamento de requisições HTTP e integração com bibliotecas populares de autenticação e autorização, como JWT e OAuth.

### Requisito 3
Integrar a API com o banco de dados criado para persistir os dados
* Decisão: No desenvolvimento deste projeto, configurei o banco de dados MySQL e é necessário criar a base de dados correspondente para sua aplicação.

    * Para iniciar, você precisará adicionar as informações de conexão com o banco de dados no arquivo .env do Laravel. Certifique-se de incluir o nome do banco de dados, usuário e senha corretos para estabelecer a conexão.

    * Após configurar o arquivo .env, execute o comando abaixo para criar as tabelas do banco de dados:
        ```
        php artisan migrate
        ```

    * Este comando criará todas as tabelas definidas em suas migrações.

### Requisito 4
 Sistema de atualização automática de dados da Open Food Facts.
* Decisão: Com esta funcionalidade, nossa API agora inclui um sistema automatizado para atualização dos dados do Open Food Facts, garantindo que os usuários tenham acesso às informações mais recentes sobre os produtos.

Utilizando o recurso de agendamento de tarefas do Laravel, foi implementado um sistema de execução agendada que baixa os arquivos da API do Open Food Facts diariamente às 00:00 no fuso horário de São Paulo (America/Sao_Paulo). O horário e fuso horário podem ser facilmente ajustados no arquivo app/Console/Kernel.php.

Para testar a atualização automática, basta executar o comando `php artisan schedule:work` e o sistema iniciará o processo de forma automática.

### Requisito 5
 Implementação de CRUD e endpoints com paginação na REST API
* Decisão: Nossa REST API agora oferece uma variedade de endpoints completos, permitindo aos usuários realizar operações CRUD para criar, ler, atualizar e excluir produtos com facilidade. Além disso, implementamos funcionalidades para garantir um desempenho otimizado da API, evitando sobrecargas no sistema.

Endpoints disponíveis:

- GET /: Retorna informações cruciais, como detalhes da API, horário da última execução do CRON, tempo online e uso de memória.
- PUT /products/:code: Atualiza as informações de um produto existente com base no código fornecido.
- DELETE /products/:code: Remove permanentemente um produto da base de dados com base no código fornecido.
- GET /products/:code: Retorna informações sobre um produto específico com base no código fornecido.
- GET /products: Lista todos os produtos disponíveis na base de dados.

Explore nossa documentação recentemente atualizada para aprender como utilizar essa funcionalidade de forma completa e eficaz:
[Documentação API - Giovane Vinicius | Open Food Facts](https://open-food-facts.firo.com.br/api/documentation)

### Requisito 6 (Extra)
Configurar um sistema de alerta se tem algum falho durante o Sync dos produtos;
* Decisão: Implementação de um sistema de alerta para lidar com possíveis falhas. Agora, se ocorrerem falhas durante a importação, elas serão registradas no arquivo de logs do Laravel, localizado em storage/logs/laravel.log. 

Além disso, para garantir uma maior confiabilidade e segurança no processo de importação, é possível verificar o status da última importação na tabela APIStatus do banco de dados. Essas atualizações visam proporcionar uma experiência mais estável e tranquila durante o processo de importação de produtos.

### Requisito 7 (Extra)
Descrever a documentação da API utilizando o conceito de Open API 3.0;
* Decisão: A documentação da nossa API foi elaborada seguindo o padrão OpenAPI 3.0, com a ajuda da ferramenta Swagger. O Swagger oferece uma maneira padronizada e interativa de descrever e documentar APIs. Ao adotar o Swagger, conseguimos diversos benefícios, como padronização, interatividade, facilidade de atualização e facilidade de colaboração entre equipes de desenvolvimento. Isso resulta em uma documentação completa, precisa e sempre atualizada, proporcionando uma experiência positiva para os desenvolvedores que utilizam nossa API.

Explore nossa documentação recentemente atualizada para aprender como utilizar essa funcionalidade de forma completa e eficaz:
[Documentação API - Giovane Vinicius | Open Food Facts](https://open-food-facts.firo.com.br/api/documentation)

Passo a passo do uso da API:

1. Solicitando API_Key para utilizar nas requisições posteriores da API:

<img src="https://open-food-facts.firo.com.br/off_1.png" alt="API_Key">

2. Adicionando API_Key ao método "Authorize" para ficar gravado o token nas requisições futuras:

<img src="https://open-food-facts.firo.com.br/off_2.png" alt="Authorize">

3. Realizando requisição para um Endpoint da API com API_Key salvo no cabeçalho da solicitação:

<img src="https://open-food-facts.firo.com.br/off_3.png" alt="Using">


### Requisito 8 (Extra)
Escrever Unit Tests para os endpoints da API;
* Decisão: Para garantir a excelência e a confiabilidade da nossa API, é essencial realizar testes unitários em todos os endpoints do CRUD. 

Ao executar os testes usando o comando "php artisan test", estaremos automatizando a verificação do correto funcionamento dos endpoints e a conformidade das respostas fornecidas pela API.

Além disso, é importante destacar que a execução dos testes faz parte de uma abordagem de desenvolvimento conhecida como TDD (Desenvolvimento Orientado por Testes), na qual escrevemos os testes antes de implementar o código, assegurando que o software funcione corretamente desde o início do processo de desenvolvimento.

Por último, ao testar todos os endpoints da nossa API, estaremos garantindo a qualidade do produto, aumentando a confiança dos usuários e minimizando a ocorrência de erros e problemas durante a utilização da aplicação.

### Requisito 9 (Extra)
Escrever um esquema de segurança utilizando API KEY nos endpoints.
* Decisão: Para acessar os endpoints da nossa API, os usuários precisam obter uma chave de API válida. Isso pode ser feito através do endpoint /token, que é responsável por gerar e fornecer uma chave de API aos usuários autorizados.

Instruções para Obtenção da Chave de API:

- Acesso ao Endpoint /token:
    - Os usuários autorizados podem enviar uma solicitação para o endpoint /token da nossa API.
    - Este endpoint processará a solicitação e, se validada, retornará uma chave de API única para o usuário.

- Inclusão da Chave de API nas Solicitações:
    - Após receber a chave de API do endpoint /token, os usuários devem incluí-la no cabeçalho da solicitação ao acessar outros endpoints da API.
    - A chave de API deve ser fornecida no cabeçalho X-API-Key.

Ao seguir essas instruções, os usuários podem obter e utilizar uma chave de API válida para acessar os endpoints da nossa API de forma segura e autorizada.

## Execução 

- Para começar, clone este repositório remoto em seu equipamento usando o seguinte comando:

```
git clone https://github.com/GiovaneVinicius/products-parser-20230105.git
```

- Em seguida, navegue até a pasta do projeto usando o seguinte comando:

```
cd products-parser-20230105
```

- Abra seu editor de código e execute a pasta do projeto nele com o seguinte comando:

```
code .
```

- Renomeie o arquivo .env.example para .env e introduza suas variáveis de ambiente, incluindo as informações de conexão com o banco de dados.

- Para instalar as dependências do Composer, utilize o seguinte comando:

```
composer install
```

- Execute o seguinte comando para gerar uma chave de API, que será adicionada automaticamente ao arquivo .env:

```
php artisan key:generate
```

- Antes de iniciar a utilização da API, certifique-se de criar o banco de dados e de executar as migrações para criar as tabelas necessárias. Você pode fazer isso com os seguintes comandos:

```
php artisan migrate
```

Agora você está pronto para utilizar todos os recursos da nossa API. Certifique-se de que o servidor web esteja em execução para acessar os endpoints da API normalmente.

Este README.md inclui todas as instruções necessárias para executar o projeto.
