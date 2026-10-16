## Como Executar o Projeto

### Pré-requisitos

- Docker e Docker Compose instalados.

### Passo a Passo

1. **Clonar o repositório:**

   ```bash
   git clone https://github.com/DevDanielCh/magazord-backend-test.git
   cd magazord-backend-test
   ```

2. **Subir os containers (tudo configurado automaticamente):**

   ```bash
   docker compose up -d --build --wait
   ```

3. **Acessar a aplicação:**

   Abra o navegador em `http://localhost:8080`.

## Executando os Testes

```bash
docker exec -it app vendor/bin/phpunit
```

## Decisões

### `entrypoint.sh`

Criei esse arquivo pra concentrar a inicialização do projeto num comando só
(`docker compose up -d --build`), deixando a validação simples e reproduzível.

### Busca por nome de pessoas

Fiz a busca usando um índice de fulltext para assim trazer resultados mais consistentes com a busca.
