/**
 * Created by pc3 on 14.01.15.
 */
var express = require('express'),
    router = express.Router(),
    mongoose = require('mongoose'),
    User = mongoose.model('user'),
    crypto = require('crypto');

module.exports = function (app) {
    app.use('/', router);
};

/**
 *
 */
router.get('/edit_user', function (req, res, next) {
    var user = mongoose.Types.ObjectId(req.user._id);
    console.log(user);
    User.findById(user, function (err, users) {
        if (err) return next(err);
        res.send(users);
    });
});

/**
 *
 */
router.put("/edit_user", function(req, res, next) {
    var id = mongoose.Types.ObjectId(req.body.params.id);
    User.findById(id, function(err, user) {
        if (err) {
            console.error(err);
            res.send(500);
            return
        }
        if (user) {
            if(user.password != req.body.params.password){
                var hash = crypto.createHash('md5');
                hash.update( req.body.params.password);
                var password = hash.digest('hex');
            }else{
                var password = user.password;
            }
            user.set({"username": req.body.params.username,  "password": password});
            user.save(function(err, user) {
                if (err) {
                    console.error(err);
                    res.send(500);
                    return
                }
                User.findById(id, function (err, users) {
                    if (err) return next(err);
                    res.send(users);
                });
            });
        }
        else {
            User.findById(id, function (err, users) {
                if (err) return next(err);
                res.send(users);
            });
        }
    });
});
