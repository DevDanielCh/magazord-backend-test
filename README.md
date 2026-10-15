## 🚀 Como Executar o Projeto

### Pré-requisitos

* [Docker](https://www.docker.com/) e [Docker Compose](https://docs.docker.com/compose/) instalados.

### Passo a Passo

1. **Clonar o repositório:**

   ```bash
   git clone https://github.com/DevDanielCh/magazord-backend-test.git
   cd magazord-backend-test
   ```

2. **Subir os containers (Tudo é configurado automaticamente):**

   ```bash
   docker compose up -d --build
   ```

3. **Acessar a aplicação:**

   Abra seu navegador em: `http://localhost:8080`

## 🧪 Executando os Testes Unitários

Para rodar a suíte de testes unitários desenvolvida com PHPUnit:

```bash
docker exec -it app vendor/bin/phpunit
```
---

## 🗺 Algumas explicações das decisões


### Arquivo `entrypoint.sh`

Criei esse arquivo para concentrar toda a inicialização do projeto em **um único comando** (`docker compose up -d --build`), tornando a validação simples e reproduzível.
