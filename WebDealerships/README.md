# WebDealerships

## Descrição do Projeto
O WebDealerships é uma aplicação web para gerenciamento de anúncios de veículos. O sistema permite que os usuários criem, visualizem e gerenciem anúncios de forma simples e intuitiva.

## Estrutura do Projeto
A arquitetura do projeto segue o padrão MVC (Model-View-Controller), organizando o código em três camadas principais:

- **Models**: Contém a lógica de dados e interações com o banco de dados.
- **Views**: Contém os arquivos HTML que definem a interface do usuário.
- **Controllers**: Contém a lógica de controle que manipula as requisições e respostas.

## Estrutura de Pastas
```
WebDealerships
├── controllers          # Controladores da aplicação
│   ├── anuncioController.js
├── models               # Modelos de dados
│   ├── anuncioModel.js
├── views                # Vistas da aplicação
│   ├── criarAnuncio.html
│   ├── mainInterna.html
│   ├── listagem.html
│   ├── index.html
├── public               # Arquivos públicos (CSS, JS)
│   ├── css
│   │   └── styles.css
│   ├── js
│       └── scripts.js
├── routes               # Definição de rotas
│   └── anuncioRoutes.js
├── app.js               # Ponto de entrada da aplicação
├── package.json         # Configuração do npm
└── README.md            # Documentação do projeto
```

## Instalação
1. Clone o repositório:
   ```
   git clone <URL do repositório>
   ```
2. Navegue até o diretório do projeto:
   ```
   cd WebDealerships
   ```
3. Instale as dependências:
   ```
   npm install
   ```

## Uso
Para iniciar a aplicação, execute o seguinte comando:
```
node app.js
```
A aplicação estará disponível em `http://localhost:3000`.

## Contribuição
Contribuições são bem-vindas! Sinta-se à vontade para abrir issues ou pull requests.

## Licença
Este projeto está licenciado sob a MIT License. Veja o arquivo LICENSE para mais detalhes.