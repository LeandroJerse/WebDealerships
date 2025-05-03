const express = require('express');
const router = express.Router();
const anuncioController = require('../controllers/anuncioController');

// Rota para criar um novo anúncio
router.post('/anuncios', anuncioController.criarAnuncio);

// Rota para listar todos os anúncios
router.get('/anuncios', anuncioController.listarAnuncios);

// Rota para editar um anúncio específico
router.put('/anuncios/:id', anuncioController.editarAnuncio);

// Rota para excluir um anúncio específico
router.delete('/anuncios/:id', anuncioController.excluirAnuncio);

// Rota para obter um anúncio específico
router.get('/anuncios/:id', anuncioController.obterAnuncio);

module.exports = router;