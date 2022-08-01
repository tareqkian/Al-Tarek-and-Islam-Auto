$.fn.extend({
    treed: function (o) {
        var openedClass = 'si si-minus';
        var closedClass = 'si si-plus';
        if (typeof o != 'undefined'){
            if (typeof o.openedClass != 'undefined'){
            openedClass = o.openedClass;
            }
            if (typeof o.closedClass != 'undefined'){
            closedClass = o.closedClass;
            }
        };
        //initialize each of the top levels
        var tree = $(this);
        tree.addClass("tree");
        tree.find('li').has("ol").each(function () {
            var branch = $(this); //li with children ol
            branch.prepend("<i class='si " + closedClass + "'></i>");
            branch.addClass('branch');
            branch.on('click', function (e) {
                if (this == e.target) {
                    var icon = $(this).children('i:first');
                    icon.toggleClass(openedClass + " " + closedClass);
                    $(this).children().children().toggle();
                }
                e.stopPropagation();
            })
        });
        tree.find('.branch i').each(function () {
            $(this).on('click', function (e) {
                $(this).closest('li').click();
                e.preventDefault();
                e.stopPropagation();
            });
        });
        //fire event from the dynamically added icon
        tree.find('.branch .indicator').each(function(){
            $(this).on('click', function () {
                $(this).closest('li').click();
                e.stopPropagation();
            });
        });
        //fire event to open branch if the li contains an anchor instead of text
        tree.find('.branch>a').each(function () {
            $(this).on('click', function (e) {
                $(this).closest('li').click();
                e.preventDefault();
                e.stopPropagation();
            });
        });
        //fire event to open branch if the li contains a button instead of text
        tree.find('.branch>button').each(function () {
            $(this).on('click', function (e) {
                $(this).closest('li').click();
                e.preventDefault();
                e.stopPropagation();
            });
        });
    },
    unTreed: function(o){
        var openedClass = 'si si-minus';
        var closedClass = 'si si-plus';
        if (typeof o != 'undefined'){
            if (typeof o.openedClass != 'undefined'){
            openedClass = o.openedClass;
            }
            if (typeof o.closedClass != 'undefined'){
            closedClass = o.closedClass;
            }
        };
        //initialize each of the top levels
        var tree = $(this);
        tree.removeClass("tree")

        tree.find('li').has("ol").each(function () {
            var branch = $(this); //li with children ol
            branch.find('i.si').remove();
            branch.removeClass('branch');
        });
    }
});

//Initialization of treeviews

$('#treeview1').treed();

$('#treeview2').treed();

$('#treeview3').treed();

$('#tree1').treed();

$('#tree2').treed({openedClass:'si si-folder-alt', closedClass:'si si-folder'});

