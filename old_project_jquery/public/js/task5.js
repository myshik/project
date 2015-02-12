$(document).ready(function () {

    var app = new App();
    //app.render();

    $('#input').click(function (event) {
        event.preventDefault();

        var link = app.getLink();
        app.cutLink(link);
    });

    $('.tab-content').on("click", ".links .cb", function (e) {
        var id = $(e.target).data("id");
        app.toggleChecked(id);

    });

    $('.but_rem_sel').click(function () {

        app.removeSelected();
    });
    $('.but_rem_all').click(function () {
        app.removeAll();
    });
    //$('#checked').click(function (event) {
    //
    //    app.state = "checked";
    //    app.render(1);
    //});
    //$('#unchecked').click(function () {
    //
    //    app.state = 'unchecked';
    //    app.render(1);
    //});
    //$('.but_all').click(function () {
    //    app.state='all';
    //    app.render(1);
    //});
    $(".nav-tabs").on("click", "a", function() {
        var state = $(this).attr("href").slice(1);
        app.state = state;
    });
    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        app.render(1);
    })

    $('.tab-content').on("click", ".pagination a", function (e) {
        var pageNumber = $(e.target).data("page");
        app.currentPage = pageNumber;
        app.render(pageNumber);
    })
});


function App () {
    this.index = 0;
    this.links = [];
    this.state = "all";
    this.currentPage = 1;
}

App.prototype.getLink = function () {
    var link = $('#name').val();
    return link;
};

App.prototype.cutLink = function (link) {
    var self = this;
    $.ajax({
        url:"http://api.bit.ly/v3/shorten",
        data:{
            longUrl: link,
            apiKey:'R_a504087bf6004b44a4c9026f77e65aa5',
            login:'reexar'
        },
        dataType:"jsonp",
        success: function(res) {
            self.addLink(res.data.url);
        },
        error: function() {
            self.addLink("error");
        },
        complete: function() {
            console.log("complete", arguments);
        }
    })
};

App.prototype.addLink = function (link) {
    var self = this;
    var linkObj = {
        id: self.index,
        link: link,
        checked: false
    };
    this.links.push(linkObj);
    this.render(this.currentPage);
    this.index++;
};

App.prototype.render = function (page) {
    var self = this;
    var links = this.links;
    $('.list_goes_here').html('');
    $('.pagin_goes_here').html('');

    switch (this.state) {
        case "checked":
            links = _.filter(links, function (link, i) {
                return link.checked;
            });
            break;
        case 'unchecked':
            links = _.filter(links, function (link, i) {
                return !link.checked
            });
            break;
    }

    //if (links.length > 5) {
    var pageCount = Math.ceil(links.length/5);
    var paging_template = _.template($("#paging_template").text());
    $('.pagin_goes_here').html(paging_template({
        selectedPage: page,
        pageCount: pageCount
    }));

    var links_template = _.template($("#links_template").text());
    if (!page) {
        $(".tab-pane.active .list_goes_here").html(links_template({links: self.links}));

    } else {
        var start = (page-1)*5;
        var end = start + 5;
        var pageLinks = links.slice(start,end);
        $(".tab-pane.active .list_goes_here").html(links_template({links: pageLinks}));
    }
};




App.prototype.toggleChecked = function (id) {
    var links = this.links;
    _.each(links, function (link) {
        if (link.id == id) {
            link.checked = !link.checked;
        }
    });
    this.render(this.currentPage);
};

App.prototype.removeSelected = function () {
    var links = this.links;
    this.links = _.filter(links, function (item) {
        return item.checked != true
    });
    this.render(1);
};

App.prototype.removeAll = function () {
    this.links = [];
    this.render(1);
};