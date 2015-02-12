'use strict';

var mongoose = require('mongoose'),
    models = require('../models/userLinksModel'),
    UserLinks = mongoose.model('userLink');

var userLinks = function(){};
/**
 * Show all Url's
 * @param res
 */
userLinks.prototype.getLinks = function(res){
    UserLinks.find(function (err, links) {
        if (err) return next(err);
        res.send(links);
    });
}
/**
 * Add Url's
 */
userLinks.prototype.add = function(res){
    var user = {user_id: this.user_id};
    if (!this.link.shortUrl || !this.link.longUrl) {
        res.send(400);
        return;
    }
    var userLink = new UserLinks(this.link);
    userLink.save(function(err, linkInst) {
        if (err) {
            res.send(400);
            return;
        }
        UserLinks.find(user, function(err, linksCollection) {
            if (err) {
                res.send(400);
                return;
            }
            console.log(linksCollection);
            res.send(linksCollection);
        });
    })
};
/**
 * Get Url's
 * @param req
 * @param res
 */
userLinks.prototype.get = function(res){
    var user = {user_id: this.user_id};
    UserLinks.find(user, function (err, links) {
        if (err) return next(err);
        res.send(links);
    });
};
/**
 * Update Urls
 */
userLinks.prototype.update = function(res){
    var user = {user_id: this.user_id};
    UserLinks.findById(this.link_id, function(err, link) {
        if (err) {
            console.error(err);
            res.send(500);
            return
        }
        if (link) {
            link.set("checked", !link.get("checked"));
            link.save(function(err, link) {
                if (err) {
                    console.error(err);
                    res.send(500);
                    return
                }
                UserLinks.find(user, function (err, links) {
                    if (err) return next(err);
                    res.send(links);
                });
            });
        }
        else {
            UserLinks.find(user, function (err, links) {
                if (err) return next(err);
                res.send(links);
            });
        }
    });
};
/**
 * Delete Url's
 * @param req
 * @param res
 */
userLinks.prototype.delete = function(res){
    var user = {user_id: this.user_id};
    UserLinks.findByIdAndRemove(this.link_id, function(err, link) {
        if (err) {
            res.send(500);
            return
        }
        if (link) {
            link.set("checked", !link.get("checked"));
            link.save(function(err, link) {
                if (err) {
                    res.send(500);
                    return
                }
                UserLinks.find(user, function (err, links) {
                    if (err) return next(err);
                    res.send(links);
                });
            });
        }
        else {
            UserLinks.find(user, function (err, links) {
                if (err) return next(err);
                res.send(links);
            });
        }
    });
}

module.exports = userLinks;
