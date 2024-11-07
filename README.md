
# Backend para Sistema de Cadastro de Empresas e Sócios

Este repositório contém o backend de um sistema de cadastro de empresas e sócios desenvolvido com PHP, Composer e Symfony. O backend foi estruturado para permitir a criação, leitura, atualização e exclusão (CRUD) de empresas e sócios, além de implementar autenticação JWT para garantir a segurança da aplicação.

## Tecnologias Utilizadas

- **PHP**: Linguagem de programação utilizada no desenvolvimento do backend.
- **Symfony**: Framework PHP utilizado para construção da aplicação.
- **Composer**: Gerenciador de dependências PHP utilizado para instalação das bibliotecas.
- **JWT (JSON Web Token)**: Implementado para autenticação dos usuários.

## Instalação

### Requisitos
Antes de executar a aplicação, certifique-se de ter os seguintes pré-requisitos instalados:

- PHP >= 8.0
- Composer
- Symfony CLI (opcional, mas recomendado para facilitar o desenvolvimento)
- Banco de dados (PostgreSQL)

### Passos para execução

1. **Clone o repositório**

   Clone o repositório para o seu ambiente local:

   ```bash
   git clone https://gitlab.com/hakai_17/vox-back.git
   cd vox-back
   ```

2. **Instale as dependências do Composer**

   Instale as dependências do projeto usando o Composer:

   ```bash
   composer install
   ```

3. **Configuração do banco de dados**

   Antes de executar a aplicação, configure a conexão com o banco de dados no arquivo `.env` ou `.env.local`:

   - **Banco de dados**: Configure a variável `DATABASE_URL` para refletir as credenciais do seu banco de dados:

     ```dotenv
     DATABASE_URL="postgresql://usuario:senha@localhost/nome_do_banco"
     ```

4. **Criação do banco de dados**

   Após configurar o banco de dados, crie as tabelas necessárias:

   ```bash
   php bin/console doctrine:database:create
   php bin/console doctrine:schema:update --force
   ```

5. **Configurando o JWT**

   O sistema utiliza JWT para autenticação. Para configurar o JWT, é necessário:

   - Gerar uma chave secreta para o JWT. Você pode fazer isso com o comando:

     ```bash
     php bin/console lexik:jwt:generate-keypair
     ```

   Isso irá gerar a chave pública e privada necessárias para assinar e verificar os tokens JWT.

6. **Executando o servidor Symfony**

   Agora você pode iniciar o servidor embutido do Symfony (ou usar um servidor de sua preferência):

   ```bash
   symfony server:start
   ```

   Ou, se você não tiver a Symfony CLI, pode usar o servidor PHP:

   ```bash
   php -S 127.0.0.1:8000 -t public
   ```

   A aplicação estará disponível em `http://127.0.0.1:8000`.

## Endpoints

### 1. **Empresas**

- **GET /empresa**: Lista todas as empresas.
- **POST /empresa/new**: Cria uma nova empresa.
- **GET /empresa/{id}**: Exibe os detalhes de uma empresa.
- **PUT /empresa/{id}/edit**: Atualiza os dados de uma empresa.
- **DELETE /empresa/{id}**: Deleta uma empresa.

### 2. **Sócios**

- **GET /socio**: Lista todos os sócios.
- **POST /socio/new**: Cria um novo sócio.
- **GET /socio/{id}**: Exibe os detalhes de um sócio.
- **PUT /socio/{id}/edit**: Atualiza os dados de um sócio.
- **DELETE /socio/{id}**: Deleta um sócio.

### 3. **Autenticação (JWT)**

- **POST /login**: Recebe as credenciais de login (usuário e senha) e retorna um token JWT para autenticação.

## Como funciona a autenticação JWT

O sistema utiliza JWT (JSON Web Tokens) para autenticação. O fluxo de autenticação funciona da seguinte maneira:

1. O usuário envia as credenciais (usuário e senha) para o endpoint `/login`.
2. O sistema valida as credenciais e, se válidas, retorna um token JWT.
3. Para acessar os endpoints protegidos (como criar, editar ou excluir empresas e sócios), o usuário deve enviar o token JWT no cabeçalho da requisição.

Exemplo de cabeçalho de autenticação:

```
Authorization: Bearer SEU_TOKEN_JWT
```

### Como obter um token JWT

Para obter um token JWT, envie uma requisição `POST` para o endpoint `/login` com as credenciais do usuário:

```json
{
  "username": "usuario",
  "password": "senha"
}
```

Se as credenciais forem válidas, o token JWT será retornado e deverá ser usado nas requisições subsequentes.

## Descrição do Projeto

Este backend foi desenvolvido para um sistema de cadastro de empresas e sócios. As funcionalidades principais incluem:

- **Cadastro de empresas**: Permite registrar empresas com informações como nome, CNPJ e data de criação.
- **Cadastro de sócios**: Permite associar sócios a empresas, armazenando informações como nome, CPF e a empresa relacionada.
- **Autenticação via JWT**: Implementação de autenticação baseada em tokens JWT para garantir que apenas usuários autenticados possam realizar operações sensíveis.

O código foi projetado com as boas práticas do Symfony, incluindo a implementação de rotas RESTful, controle de acesso via JWT e uso de Doctrine ORM para interação com o banco de dados.
