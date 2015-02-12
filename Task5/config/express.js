var express = require('express'),
    glob = require('glob'),
    favicon = require('serve-favicon'),
    logger = require('morgan'),
    cookieParser = require('cookie-parser'),
    bodyParser = require('body-parser'),
    compress = require('compression'),
    methodOverride = require('method-override'),
    session = require('express-session'),
    passport = require('passport');

module.exports = function(app, config) {
  app.set('views', config.root + '/app/views');
  app.set('view engine', 'jade');
  // app.use(favicon(config.root + '/public/img/favicon.ico'));
  app.use(logger('dev'));
  app.use(bodyParser.json());
  app.use(bodyParser.urlencoded({extended: true}));
  app.use(cookieParser());
  app.use(session({ secret: '1234567890' }));
  app.use(passport.initialize());
  app.use(passport.session());
  app.use(compress());
  app.use(express.static(config.root + '/public'));
  app.use(methodOverride());
  var controllers = glob.sync(config.root + '/app/controllers/*.js');
  controllers.forEach(function (controller) {
    require(controller)(app, config);
  });
  app.use(function (req, res, next) {
    var err = new Error('Not Found');
    err.status = 404;
    next(err);
  });
  if(app.get('env') === 'development'){
    app.use(function (err, req, res, next) {
      res.status(err.status || 500);
      res.render('./error/error', {
        message: err.message,
        error: err,
        title: 'error'
      });
    });
  }
  app.use(function (err, req, res, next) {
    res.status(err.status || 500);
    res.render('./error/error', {
      message: err.message,
      error: {},
      title: 'error'
    });
  });
};