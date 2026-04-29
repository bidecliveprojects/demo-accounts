$(document).ready(function(){
    
$(".Navclose").click(function(){
    $(".sidenavnr").toggleClass("Navactive");
    $("body").toggleClass("full_with");
   
  });



$(".tmenu-list").hover(function () {
      $(this).toggleClass("active");
    });
$(".settingListSb").click(function () {
      $(this).toggleClass("active");
    });

$(".settingListSb-subItem").click(function () {
      $(this).toggleClass("active");
     
    });

$(".dd").click(function () {
      $(this).find('.collapsee').slideToggle();

    });
$(".sm-bx").click(function () {
       $(".sidenavnr").removeClass("Navactive");
    $("body").removeClass("full_with");

    });


 var $myGroup = $('#myGroup');
$myGroup.on('show.bs.collapse','.collapse', function() {
    $myGroup.find('.collapse.in').collapse('hide');
});



// end
});

/**
 * Mobile / tablet: sliding sidebar + FAB so main content is not squeezed by fixed 270px offset.
 */
(function () {
    function isMobileNav() {
        return window.matchMedia && window.matchMedia('(max-width: 991px)').matches;
    }

    function ensureSidebarUi() {
        if (!isMobileNav()) {
            $('body').removeClass('sidebar-mobile-open');
            return;
        }
        if ($('#app-sidebar-backdrop').length) return;
        $('body').append(
            '<div id="app-sidebar-backdrop" class="app-sidebar-backdrop" aria-hidden="true"></div>'
        );
        var $fab = $(
            '<button type="button" class="app-sidebar-fab btn btn-primary visible-xs visible-sm" aria-label="Open navigation menu">' +
                '<i class="fa fa-bars"></i></button>'
        );
        $('body').append($fab);
        $fab.on('click', function (e) {
            e.preventDefault();
            $('body').addClass('sidebar-mobile-open');
        });
        $('#app-sidebar-backdrop').on('click', function () {
            $('body').removeClass('sidebar-mobile-open');
        });
    }

    $(document).ready(function () {
        ensureSidebarUi();
        $(window).on('resize', function () {
            ensureSidebarUi();
        });
        $(document).on('click', '.Navclose', function () {
            $('body').removeClass('sidebar-mobile-open');
        });
        /* Close drawer after real navigation */
        $(document).on('click', '.sidenavnr a[href]', function () {
            var href = $(this).attr('href');
            if (href && href !== '#' && href.charAt(0) !== '#') {
                $('body').removeClass('sidebar-mobile-open');
            }
        });
    });
})();

