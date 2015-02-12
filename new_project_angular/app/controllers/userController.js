'use strict';

var mongoose = require('mongoose'),
    modelUser = require('../models/userModel.js'),
    User = mongoose.model('User');

var getUser = function(res){
    User.find(function (err, users) {
        if (err) return next(err);
        res.send(users);
    });
}

module.exports.getUser = getUser;
