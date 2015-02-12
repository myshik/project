var express = require('express'),
    router = express.Router(),
    mongoose = require('mongoose'),
    Links = mongoose.model('Links'),
    Profile = mongoose.model('Profile');

module.exports = function (app) {
  app.use('/', router);
};

//router.post('/registration', function(req, res, next){
//    console.log(req.params);
//    var user = {
//        email: req.body.email,
//        password: req.body.password
//    };
//    if (!user.email || !user.password) {
//        res.send(400);
//        return;
//    }
//
//    var newUser = new User(user);
//    newUser.save(function(err, userInst) {
//        if (err) {
//            console.error(err);
//            res.send(400);
//            return;
//        }
//        res.redirect('/');
//    })
//});



router.get('/index', getPage, function (req, res, next) {
  //Links.find(function (err, links) {
  //  if (err) return next(err);
    res.render('index', {
      title: 'Welcome to task manager!'
    });
  //});
});

// описываем соглашениена роуты
// 1. работа со ссылками:
//    добавление,
//    удаленние,
//    изменение состояния
router.get("/index/", getPage, function(req, res, next) {
  var perPage = 5;
  var selectedPage = 1;

  Links.find(function (err, links) {
    if (err) return next(err);
    res.render('tabs', {
      links: links.slice((selectedPage-1)*perPage, selectedPage*perPage),
      pageCount: links.length/perPage,
      selectedPage: selectedPage,
      state: "all"
    });
  });
});

router.post("/links/", getPage, function(req, res, next) {
  var perPage = 5;
  var selectedPage = req.pagination.page;
  var state = req.body.state;
  var query = {};
  switch (state) {
    case "checked": query["checked"] = true; break;
    case "unchecked": query["checked"] = false; break;
    default : state = "all";
  }

  var link = {
    shortUrl: req.body.shortUrl,
    longUrl: req.body.longUrl
  };
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

      res.render("tabs", {
        links: linksCollection.slice((selectedPage-1)*perPage, selectedPage*perPage),
        selectedPage: selectedPage,
        pageCount: linksCollection.length/perPage,
        state: state
      });
    });
  })
});

router.put("/links/", getPage, function(req, res, next) {
  var perPage = 5;
  var selectedPage = req.body.page;

    console.log(req.body);

  var id = mongoose.Types.ObjectId(req.body.id);

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

        Links.find(function (err, links) {
          if (err) return next(err);
          res.render('tabs', {
            links: links.slice((selectedPage-1)*perPage, selectedPage*perPage),
            pageCount: links.length/perPage,
            selectedPage: selectedPage,
            state: "all"
          });
        });
      });
    }
    else {
      Links.find(function (err, links) {
        if (err) return next(err);
        res.render('tabs', {
          links: links.slice((selectedPage - 1) * perPage, selectedPage * perPage),
          pageCount: links.length / perPage,
          selectedPage: selectedPage,
          state: "all"
        });
      });
    }
  });
});

router.delete("/links/", getPage, function(req, res, next) {
  var perPage = 5;
  var selectedPage = 1;

  var id = req.body.id;
    console.log(id);
    //удаляем ссылки получаем id из тела запроса потом ищем по базе и удаляем
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

                Links.find(function (err, links) {
                    if (err) return next(err);
                    res.render('tabs', {
                        links: links.slice((selectedPage-1)*perPage, selectedPage*perPage),
                        pageCount: links.length/perPage,
                        selectedPage: selectedPage,
                        state: "all"
                    });
                });
            });
        }
        else {
            Links.find(function (err, links) {
                if (err) return next(err);
                res.render('tabs', {
                    links: links.slice((selectedPage - 1) * perPage, selectedPage * perPage),
                    pageCount: links.length / perPage,
                    selectedPage: selectedPage,
                    state: "all"
                });
            });
        }
    });
});

// 2. работа со страницами:
//    получение всех,
//    отмеченных,
//    не отмченных,
//    переход на страницу - через GET параметр /all?page=2

router.get("/links/:state", getPage, function(req, res, next) {
  /* переключение вкладок */
  var state = req.params.state;
  var query = {};
  switch (state) {
    case "checked": query["checked"] = true; break;
    case "unchecked": query["checked"] = false; break;
    default : state = "all";
  }

  var selectedPage = 1;
  var perPage = 5;

  Links.find(query, function (err, links) {
    if (err) {
      console.error(err);
      res.send(400);
      return;
    }
    res.render('tabs', {
      links: links.slice((selectedPage - 1) * perPage, selectedPage * perPage),
      pageCount: links.length / perPage,
      selectedPage: selectedPage,
      state: state
    });
  });
});

// 3. Манипуляция:
//    удаление всех,
//    удаление отмеченных
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
