var express = require('express'),
    router = express.Router(),
    mongoose = require('mongoose'),
    Links = mongoose.model('Links');

module.exports = function (app) {
  app.use('/', router);
};

/**
 *  Получить данные конкретного пользователя
 */

router.get('/private', getPage, function (req, res, next) {
    if(req.isAuthenticated()){
        res.render('./private_user/index',{})
    }else{
        res.redirect('/');
    }
});

router.get('/mylinks', getPage, function (req, res, next) {
    res.render('./private_user/mylinks',{})
});

/**
 *
 */
router.get("/links", getPage, function(req, res, next) {
  var user = {user_id: req.user._id};
  Links.find(user, function (err, links) {
    if (err) return next(err);
      console.log(links);
    res.send(links);
  });
});

/**
 *
 */
router.post("/links/", getPage, function(req, res, next) {
  var query = {};

  query["user_id"] = req.user._id;

  var link = {
    shortUrl: req.body.params.shortUrl,
    longUrl: req.body.params.longUrl,
    user_id: req.user._id
  };
    console.log(link);
  if (!link.shortUrl || !link.longUrl) {
    res.send(400);
    return;
  }

  var newLink = new Links(link);

  newLink.save(function(err, linkInst) {
    if (err) {
      console.error(err);
      res.send(400);
      return;
    }
    Links.find(query, function(err, linksCollection) {
      if (err) {
        console.error(err);
        res.send(400);
        return;
      }
        res.send(linksCollection);
    });
  })
});

/**
 *
 */
router.put("/links", getPage, function(req, res, next) {
var id = mongoose.Types.ObjectId(req.body.params.id),
    user = {user_id: req.user._id};

    Links.findById(id, function(err, link) {
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
        Links.find(user, function (err, links) {
          if (err) return next(err);
          res.send(links);
        });
      });
    }
    else {
      Links.find(user, function (err, links) {
        if (err) return next(err);
        res.send(links);
      });
    }
    });
});

/**
 *  Удаление все ссылок
 */
router.delete("/links/", getPage, function(req, res, next) {
var id = req.query.id,
    user = {user_id: req.user._id};
    Links.findByIdAndRemove(id, function(err, link) {
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
                Links.find(user, function (err, links) {
                    if (err) return next(err);
                    res.send(links);
                });
            });
        }
        else {
            Links.find(user, function (err, links) {
                if (err) return next(err);
                res.send(links);
            });
        }
    });
});

/**
 *
 * @param req
 * @param res
 * @param next
 * @returns {*}
 */
function getPage(req, res, next) {
  var page = req.query.page || req.body.page || req.params.page || 1;
      page = parseInt(page, 10);
  if (isNaN(page) == true) {
    page = 1;
  }
  req.pagination = {
    page: page
  };
  return next();
}
