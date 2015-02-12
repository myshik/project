var express = require('express'),
    router = express.Router(),
    mongoose = require('mongoose'),
    User = mongoose.model('user'),
    Links = mongoose.model('Links'),
    passport = require('passport'),
    LocalStrategy  = require('passport-local').Strategy,
    AuthFacebookStrategy = require('passport-facebook').Strategy,
    AuthVKStrategy = require('passport-vkontakte').Strategy,
    config = require('../../config/config'),
    crypto = require('crypto');
module.exports = function (app, config) {
    app.use('/', router);
};
/**
 *  Верификация пользователя
 */
passport.use(new LocalStrategy({
    usernameField: 'email',
    passwordField: 'password'
}, function(username, password, done){
    var hash = crypto.createHash('md5');
        hash.update(password);
    var password = hash.digest('hex');
    User.findOne({ email : username},function(err,user){
        return err
            ? done(err)
            : user
            ? password === user.password
            ? done(null, user)
            : done(null, false, { message: 'Incorrect password.' })
            : done(null, false, { message: 'Incorrect username.' });
    });
}));

passport.use('facebook', new AuthFacebookStrategy({
        clientID: config.auth.fb.app_id,
        clientSecret: config.auth.fb.secret,
        callbackURL: config.app.url + "/auth/fb/callback",
    },
    function (accessToken, refreshToken, profile, done) {
        process.nextTick(function() {
            User.findOne({ 'email' : profile.id }, function(err, user) {
                if (err)
                    return done(err);
                if (user) {
                    return done(null, user);
                } else {
                    var newUser = new User();
                    newUser.username  = profile.displayName;
                    newUser.gender  = profile.gender;
                    newUser.email = profile.id;
                    newUser.password = '';
                    newUser.save(function(err) {
                        if (err)
                            throw err;
                        return done(null, newUser);
                    });
                }
            });
        });
    }
));

passport.use('vk', new AuthVKStrategy({
        clientID: config.auth.vk.app_id,
        clientSecret: config.auth.vk.secret,
        callbackURL: config.app.url + "/auth/vk/callback"
    },
    function (accessToken, refreshToken, profile, done) {
        process.nextTick(function() {
            User.findOne({ 'email' : profile.id }, function(err, user) {
                if (err)
                    return done(err);
                if (user) {
                    return done(null, user);
                } else {
                    var newUser = new User();
                    newUser.username  = profile.displayName;
                    newUser.gender  = profile.gender;
                    newUser.email = profile.id;
                    newUser.password = '';
                    newUser.save(function(err) {
                        if (err)
                            throw err;
                        return done(null, newUser);
                    });
                }

            });
        });
    }
));

passport.serializeUser(function (user, done) {
    done(null, JSON.stringify(user));
});


passport.deserializeUser(function (data, done) {
    try {
        done(null, JSON.parse(data));
    } catch (e) {
        done(err)
    }
});
/**
 *  Стартовая страница
 */
router.get('/', function (req, res, next) {
    console.log(req.query.mistake);
    req.isAuthenticated()
        ? res.redirect('/private')
        : res.render('./all_user/auth',{mistake : req.query.mistake});
});
/**
 *  Выводим список всех пользователей
 */
router.get('/getuser', function (req, res, next) {
    User.find(function (err, users) {
        if (err) return next(err);
        res.send(users);
    });
});
/**
 *  Выводим список всех ссылок
 */
router.get('/getlinks', function (req, res, next) {
    Links.find(function (err, links) {
        if (err) return next(err);
        res.send(links);
    });
});

/**
 *  Получить страницу регистрации
 */
router.get('/all_links', function (req, res, next) {
    res.render('./all_user/users', {});
});
router.get('/edit_profile', function (req, res, next) {
    res.render('./private_user/edit_profile', {});
});

/**
 *  Аутефикация пользователя
 */
router.post('/login', function (req, res, next)  {
    passport.authenticate('local',
        function(err, user, info) {
            return err
                ? next(err)
                : user
                ? req.logIn(user, function(err) {
                return err
                    ? next(err)
                    : res.redirect('/private');
            })
                : res.redirect('/?mistake='+info.message);
        }
    )(req, res, next);
});

router.get('/auth/fb',
    passport.authenticate('facebook', {
        scope: 'read_stream'
    })
);

router.get('/auth/fb/callback',
    passport.authenticate('facebook', {
        successRedirect: '/private',
        failureRedirect: '/' })
);

router.get('/auth/vk',
    passport.authenticate('vk', {
        scope: ['friends']
    }),
    function (req, res) {});

router.get('/auth/vk/callback',
    passport.authenticate('vk', {
        failureRedirect: '/'
    }),
    function (req, res) {
        res.redirect('/private');
    });
/**
 *  Регистрация пользователя
 */
router.post('/registration', function (req, res, next) {
    var hash = crypto.createHash('md5');
        hash.update(req.body.password);
    var password = hash.digest('hex');
    var user = new User({ username: req.body.email, gender: '', email: req.body.email, password: password});
    user.save(function(err) {
        return err
            ? next(err)
            : req.logIn(user, function(err) {
            return err
                ? next(err)
                : res.redirect('/private');
        });
    });
});
/**
 * Разлогирование пользователя
 */
router.get('/logout', function (req, res) {
    req.logout();
    res.redirect('/');
});
router.get('*', function (req, res, next) {
    req.isAuthenticated()
        ? res.redirect('/private')
        : res.render('./all_user/auth');
});