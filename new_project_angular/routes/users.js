'use strict';
var express = require('express'),
    router = express.Router(),
    UserLinks = require('../app/controllers/userLinksController.js'),
    userLinks = new UserLinks();

router.get('/', function(req, res) {
    res.render('./cabinet/index/index')
    });
router.get('/all_links_view', function(req, res){
        res.render('./cabinet/index/all_links')
    })
    .get('/links_view', function(req, res){
        res.render('./cabinet/index/links')
    })
    .get('/get_links', function(req, res){
        userLinks.getLinks(res);
    })
    .get('/edit_profile', function (req, res, next) {
    res.render('./private_user/edit_profile', {});
});
router.get('/links', function(req, res){
    userLinks.user_id = req.user._id;
    userLinks.get(res);
    })
    .post('/links', function(req, res) {
      userLinks.link={
          shortUrl: req.body.params.shortUrl,
          longUrl: req.body.params.longUrl,
          user_id: req.user._id
      }
      userLinks.add(res);
    })
    .put('/links', function(req, res){
      userLinks.link_id = req.body.params.id;
        userLinks.update(res)
    })
    .delete('/links', function(req, res){
        userLinks.link_id = req.query.id;
        userLinks.delete(res);
    });

module.exports = router;
