const {Router } = require('express');

const {
    post_register,
    post_check_username,
    get_user_by_username,
    get_all_users,
    get_user_by_id,
    delete_user_by_username
} = require('../controllers/user');

const router = Router();

router.post('/register', post_register);
router.post('/check', post_check_username);
router.get ('/username/:username', get_user_by_username);
router.get('/id/:id', get_user_by_id);
router.get('/all', get_all_users);
// router.delete('/username/:username', delete_user_by_username)

module.exports = router;