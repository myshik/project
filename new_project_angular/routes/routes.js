'use strict';

var index = require('./index'),
    users = require('./users');

var route = function (app){
    app.use('/', index);
    app.use('/cabinet', users);
    app.use(function(req, res, next) {
        var err = new Error('Not Found');
        err.status = 404;
        next(err);
    });
    if (app.get('env') === 'development') {
        app.use(function(err, req, res, next) {
            res.status(err.status || 500);
            res.render('error/error', {
                message: err.message,
                error: err
            });
        });
    };
    app.use(function(err, req, res, next) {
        res.status(err.status || 500);
        res.render('error/error', {
            message: err.message,
            error: {}
        });
    });
}
module.exports = route;
