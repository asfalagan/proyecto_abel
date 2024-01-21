
const {Router } = require('express');

const {
    post_login
} = require('../controllers/auth');

const router = Router();

router.post('/login',
    post_login);


module.exports = router;