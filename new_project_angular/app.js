'use strict';

var express = require('express'),
    path = require('path'),
    favicon = require('serve-favicon'),
    logger = require('morgan'),
    cookieParser = require('cookie-parser'),
    bodyParser = require('body-parser'),
    session = require('express-session'),
    passport = require('passport'),
    routes = require('./routes/routes.js'),
    config = require('./config'),
    mongoose = require('mongoose'),
    app = express();

    mongoose.connect(config.get('mongodb:db'));

    var db = mongoose.connection;
    db.on('error', function () {
        throw new Error('unable to connect to database at ' + config.get('mongodb:db'));
    });
    db.once('open', function callback () {
        console.log('db connect!');
    });

    app.set('views', path.join(__dirname + '/app', 'views'));
    app.set('view engine', 'jade');
    app.use(favicon(__dirname + '/public/favicon.ico'));
    app.use(logger('dev'));
    app.use(bodyParser.json());
    app.use(bodyParser.urlencoded({ extended: false }));
    app.use(cookieParser());
    app.use(session({ secret: config.get('session:secret') }));
    app.use(passport.initialize());
    app.use(passport.session());
    app.use(express.static(path.join(__dirname, 'public')));
    routes(app);
    module.exports = app;
