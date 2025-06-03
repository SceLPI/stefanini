## 🇧🇷 PADRÕES E ARQUITETURAS UTILIZADAS

- MVC
- Service-Repository, validações de formulário não fica dentro do Service, utilizamos o padrão laravel de FormRequest, todas as outras regras de negócio estão em seus services.
- Injeção de dependencia.
- SMART e Clean Code
- Object Calisthenics
- SOLID
- Utilizei entidades para caso seja necessária mudança no banco de dados, a única mudança vai ser na própria entidade e não será necessário alterar em nenhum outro lugar.
- Foi utilizado enumerations para evitar números mágicos

## 🇺🇸 PATTERNS AND PROJECT ARCHTETURE USED

- MVC
- Service-Repository used, form validations was not inside Services, it was inside FormRequests, all other busness rules like ownership are inside Services.
- Dependency Injection.
- SMART Clean Code.
- Object Calisthenics.
- SOLID.
- I've used Entities due to if necessary to change DB, the only change will be on Entity not being necessary to change on nowhere more.
- Using enumerations to avoid Magic Numbers

## 🇧🇷 TESTES

- Todos os testes foram realizados utilizando o pest (default laravel 12 test tool)
- Pode ser checado a cobertura dos testes utilizando `php artisan test --coverage` é necessário habilitar o `xdebug.mode = coverage` no php.ini (habilitado por padrão no container docker) e você deve ter a extensão `xdebug` instalada também (também habilitado por padrão no container docker)
- Feito 2 tipos de teste, Integração e Unitário
- Para rodar os testes utilize `php artisan test`

## 🇺🇸 TESTS

- All tests are made using pest (default laravel 12 test tool)
- You can check code coverage 100% by using `php artisan test --coverage` its necessary to enable `xdebug.mode = coverage` in php.ini (enabled by default on docker container) and you should have `xdebug` extension installed also (enabled by default on docker container)
- Made 2 kind of tests, Integration and Unity
- To run testes just use `php artisan test`

## 🇧🇷 BANCO DE DADOS

- Por padrão o banco de dados utilizado foi o MySQL 5.4, mas o sistema foi testado com o 8.0, MariaDB 12 e PostgreSQL (Neste ultimo alterando somente os atributos do tipo booleano).
- Tabelas de status foram populados dentro da propria migração por questão de sefurança para não ser rodada a migração mais de uma vez.

## 🇺🇸 DATABASE

- By default database used was MySQL 5.3, but it was tested with 8.0 and MariaDB 12 version also and PostgreSQL.
- Statouses tables are populated inside its own migration to run only once for safety purposes.

## 🇧🇷 LOGIN

- Login foi criado utilizando o Sanctum
- Adicionei o recovery de senha como funcionalidade extra

## 🇺🇸 LOGIN

- Login was made using Sanctum
- I've added new codes to recovery (one extra feature)

## 🇧🇷 TABELAS DO PROJETO

- Como solicitado, a tabela Projects não foi definida no teste, eu fiz as suas definições para utilizar algumas funcionalidades como chaves estrageiras e FromRequests com dependencias.
- Adicionado em sua estrutura soft deletion e seus status.

## 🇺🇸 PROJECT TABLE

- As requested, Project table was not defined, i make its definition to use some features like foreign keys and some iterations on APP
- Added on his structure the current project status, manager and soft deletes.

## 🇧🇷 CACHE

- Utilizando o padrão Cache Aside
- Adicionei cache no redis para a tabela de projetos.
- É possivel checar a performance fazer requisições massivas.
- Conexão com o redis utilizando `predis`

## 🇺🇸 CACHE

- Using Cache Aside Pattern
- I've added cache on redis to users and project.
- You can check performance just making massive requests.
- Redis connection using `predis`

## 🇧🇷 DOCUMENTAÇÃO 

- Postman Collection
- Postman Docs
- Toda a documentação foi feita utilizando postman docs, todas as requisições foram mapeadas no postman também. Você pode verificar a documentação swagger no link a seguir. `https://`

## 🇺🇸 DOCUMENTATION 

- Postman Collection
- Postman Docs
- All documentation was made with postman docs, all requests and edpoints are mapped on postman also. You can check swagger doc by Postman on this link. `https://`

## 🇧🇷 PRONTO PARA INTERNACIONALIZAÇÃO

- Todo o projeto foi feito utilizando i8n padrão do laravel.

## 🇺🇸 I8N READY

- All project are made using i8n laravel's stadands.

## 🇧🇷 INICIANDO O PROJETO

- Configure suas env vars copiando `.env.example` para `.env` 
- Você deve ter o docker instalado em sua máquina, então basta rodar `docker-compose up --build`
- Execute o comando `docker exec -it stefanini_php bash -c "php artisan migrate --force && php artisan optimize:clear && php artisan route:clear && php artisan cache:clear"`
- Para rodar os testes execute `docker exec -it stefanini_php bash -c "php artisan test --coverage"` (TESTES LIMPAM BANCO E CACHE, NÃO RODE EM PRODUÇÃO)

## 🇺🇸 STARTING PROJECT

- Configure your env vars copying `.env.example` to `.env` 
- You should have docker installed on your machine, and just run `docker-compose up --build`
- Execute the command `docker exec -it stefanini_php bash -c "php artisan migrate --force && php artisan optimize:clear && php artisan route:clear && php artisan cache:clear"`
- To run tests execute `docker exec -it stefanini_php bash -c "php artisan test --coverage"` (TESTS CLEANS DATABASE AND CACHE, DO NOT RUN IN PRODUCTION)

## ROADMAP
- LARAVEL PROJECT
    - Sanctum Auth ✅
    - Auth Register ✅
    - Auth Login ✅
    - Project Create ✅
    - Project Update ✅
    - Project Delete ✅
    - Project Get ✅
    - Project List ✅
    - Integration Tests ✅
    - Unit Tests ✅
    - Weather Api ✅
    - Redis (Cache Aside Pattern) ✅

- FLUTTER PROJECT
    - Login Screen ✅
    - Register Screen ✅
    - Provider ✅
    - Weather API consume ✅

- DOCKER
    - Docker File - Laravel+PHP ✅
    - Docker Compose - Laravel+MySQL+Redis etc. ✅

- MONITORING
    - Sentry Back ✅
    - Sentry Front ✅

- DEPLOY
    - Vercel ✅
    - CI - Github Actions 
    - CD - Auto Deploy

- LAYOUT
    - Design Material ✅
    - Beauty Layout ✅
