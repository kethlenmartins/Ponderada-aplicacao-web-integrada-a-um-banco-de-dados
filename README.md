# Sistema de Gestão de Funcionários e Projetos

## 1. Contexto da Atividade
Este projeto foi desenvolvido como entrega da atividade ponderada "Elaboração de aplicação web integrada a um banco de dados". 

Mais especificamente, busquei criar a primeira base para um projeto maior utilizando os recursos do tutorial. Na empresa em que trabalho, temos uma gestão e organização de documentos um pouco confusa, utilizando muitas plataformas diferentes. Assim, pensei em criar a v1 de um novo sistema utilizando essa ponderada.
O objetivo principal dessa primeira versão é permitir o cadastro e visualização de **funcionários** e **projetos**, mantendo os dados em um banco PostgreSQL hospedado no Amazon RDS.  

Funcionalidades incluídas:
- Cadastro de funcionários (nome, endereço, time e estado)
- Cadastro de projetos (nome, empresa parceira, datas, responsáveis, status, valor e descrição)
- Listagem em tabela de todos os funcionários e projetos cadastrados

---

## 2. Implementação na AWS
A aplicação foi implantada utilizando os seguintes recursos da AWS:

- **EC2**: servidor Apache com PHP para rodar a aplicação web.
- **RDS (PostgreSQL)**: banco de dados para armazenamento das tabelas `EMPLOYEES` e `PROJECTS`.

O arquivo PHP (`SamplePage.php`) contém toda a lógica de conexão, criação das tabelas e inserção de dados.  
A página web inclui formulários para cadastro de funcionários e projetos e exibe tabelas com os registros atualizados em tempo real.

---

## 3. Vídeo de Demonstração
Abaixo, é possível visualizar o vídeo de demonstração em que é explicado o que aconteceu durante o deploy das instâncias e o projeto funcionando em tempo real.

> [Inserir link do vídeo aqui]
