var express = require('express'),
    router = express.Router(),
    mongoose = require('mongoose'),
    User = mongoose.model('user');
var passport       = require('passport');
var LocalStrategy  = require('passport-local').Strategy;

module.exports = function (app) {
    app.use('/', router);
};

passport.use(new LocalStrategy({
    usernameField: 'email',
    passwordField: 'password'
}, function(username, password, done){
    User.findOne({ username : username},function(err,user){
        return err
            ? done(err)
            : user
            ? password === user.password
            ? done(null, user)
            : done(null, false, { message: 'Incorrect password.' })
            : done(null, false, { message: 'Incorrect username.' });
    });
}));


passport.serializeUser(function(user, done) {
    done(null, user.id);
});


passport.deserializeUser(function(id, done) {
    User.findById(id, function(err,user){
        err
            ? done(err)
            : done(null,user);
    });
});


router.get('/', function (req, res, next) {
    //Links.find(function (err, links) {
    //  if (err) return next(err);
    console.log(req.isAuthenticated());
    req.isAuthenticated()
        ? res.redirect('/index')
        : res.render('auth', {
            title: 'Welcome to task manager!'
        });
    //});
});
router.get('/registration', function (req, res, next) {
    //Links.find(function (err, links) {
    //  if (err) return next(err);
    res.render('registration', {
        title: 'Welcome to task manager!'
    });
    //});
});

router.post('/login', function (req, res, next)  {
    passport.authenticate('local',
        function(err, user, info) {
            return err
                ? next(err)
                : user
                ? req.logIn(user, function(err) {
                return err
                    ? next(err)
                    : res.redirect('/index');
            })
                : res.redirect('/');
        }
    )(req, res, next);
});

router.post('/registernewuser', function (req, res, next) {
    var user = new User({ username: req.body.email, password: req.body.password});
    user.save(function(err) {
        return err
            ? next(err)
            : req.logIn(user, function(err) {
            return err
                ? next(err)
                : res.redirect('/index');
        });
    });

});

router.get('/logout', function (req, res, next) {
    req.logout();
    res.redirect('/');
});