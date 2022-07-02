# Creditares Teste

Instruções de instalação:

1. git clone git@github.com:CassianoD2/creditares-test.git
2. composer install
3. npm install && npm run dev
4. php artisan migrate
5. Não esquecer de configurar o env para ter acesso ao PostgresSQL, exemplo abaixo:
```dotenv
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=creditares_teste
DB_USERNAME=postgres
DB_PASSWORD=postgres
```

Após a configuração para rodar o crawler, ele pode ser executado via artisan de duas maneiras:

1. php artisan schedule:run
2. php artisan copagril:crawler

Após a execução ele irá gravar os dados das duas unidades disponíveis no momento:
- Unidade Parana
- Unidade Mato Grosso do Sul

A visualização dos dados está disponível diretamente na home (/):

Culturas: Soja, Milho e Trigo - Todas podendo visualizar seus respectivos valores e podendo consultar diferentes periodos.

