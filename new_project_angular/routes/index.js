'use strict';
var express = require('express'),
    router = express.Router(),
    config = require('../config'),
    passport = require('passport'),
    mongoose = require('mongoose'),
    models = require('../app/models/userModel.js'),
    UserCtrl = require('../app/controllers/userController'),
    User = mongoose.model('User'),
    LocalStrategy  = require('passport-local').Strategy,
    AuthFacebookStrategy = require('passport-facebook').Strategy,
    AuthVKStrategy = require('passport-vkontakte').Strategy,
    crypto = require('crypto');

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
//passport.use('facebook', new AuthFacebookStrategy({
//        clientID: config.get('auth:fb:app_id'),
//        //clientSecret: config.get('auth:fb:secret'),
//        clientSecret: '0',
//        callbackURL: config.get('app:url') + "/auth/fb/callback"
//    },
//    function (accessToken, refreshToken, profile, done) {
//        process.nextTick(function() {
//            User.findOne({ 'email' : profile.id }, function(err, user) {
//                if (err)
//                    return done(err);
//                if (user) {
//                    return done(null, user);
//                } else {
//                    var newUser = new User();
//                    newUser.username  = profile.displayName;
//                    newUser.gender  = profile.gender;
//                    newUser.email = profile.id;
//                    newUser.password = '';
//                    newUser.save(function(err) {
//                        if (err)
//                            throw err;
//                        return done(null, newUser);
//                    });
//                }
//            });
//        });
//    }
//));

passport.use('vk', new AuthVKStrategy({
        clientID: config.get('auth:vk:app_id'),
        clientSecret: config.get('auth:vk:secret'),
        callbackURL: config.get('app:url') + "/auth/vk/callback"
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
 * Auth for local user
 */
router.get('/', function(req, res) {
    req.isAuthenticated()
        ? res.redirect('/cabinet')
        : res.render('./promo/index/index',{mistake : req.query.mistake});
    })
    .post('/', function(req, res, next){
        passport.authenticate('local',
            function(err, user, info) {
                return err
                    ? next(err)
                    : user
                    ? req.logIn(user, function(err) {
                    return err
                        ? next(err)
                        : res.redirect('/cabinet');
                })
                    : res.redirect('/?mistake='+info.message);
            }
        )(req, res, next);
    });
/**
 * registration vk and facebook
 */
router.get('/auth/fb',
    passport.authenticate('facebook', {
        scope: 'read_stream'
    }))
    .get('/auth/fb/callback',
    passport.authenticate('facebook', {
        successRedirect: '/cabinet',
        failureRedirect: '/' }));
router.get('/auth/vk',
    passport.authenticate('vk', {
        scope: ['friends']
    }))
    .get('/auth/vk/callback',
    passport.authenticate('vk', {
        failureRedirect: '/'
    }),
    function (req, res) {
        res.redirect('/cabinet');
    });
/**
 * Registration User local
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
                : res.redirect('/cabinet');
        });
    });
});
/**
 * Logout User
 */
router.get('/logout', function(req, res) {
    req.logout();
    res.redirect('/');
});
/**
 *  Выводим список всех пользователей
 */
router.get('/get_user', function (req, res, next) {
    UserCtrl.getUser(res);
});

module.exports = router;