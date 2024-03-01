var _____WB$wombat$assign$function_____ = function(name) {return (self._wb_wombat && self._wb_wombat.local_init && self._wb_wombat.local_init(name)) || self[name]; };
if (!self.__WB_pmw) { self.__WB_pmw = function(obj) { this.__WB_source = obj; return this; } }
{
  let window = _____WB$wombat$assign$function_____("window");
  let self = _____WB$wombat$assign$function_____("self");
  let document = _____WB$wombat$assign$function_____("document");
  let location = _____WB$wombat$assign$function_____("location");
  let top = _____WB$wombat$assign$function_____("top");
  let parent = _____WB$wombat$assign$function_____("parent");
  let frames = _____WB$wombat$assign$function_____("frames");
  let opener = _____WB$wombat$assign$function_____("opener");

function doPing() {
  $.get("/core/func/api/auth/ping.php", function(response) {
    console.log("Ping complete");
  })
}

$(document).ready(function() {
  $(function () {
    $("[data-toggle='tooltip']").tooltip();
  });
  $('.dropdown').on('show.bs.dropdown', function() {
    $(this).find('.dropdown-menu').first().stop(true, true).slideDown();
  });

  // Add slideUp animation to Bootstrap dropdown when collapsing.
  $('.dropdown').on('hide.bs.dropdown', function() {
    $(this).find('.dropdown-menu').first().stop(true, true).slideUp();
  });
  
  $('#navbarSideButton').click(function() {
    $(".navbar-side").show();
    //$(".navbottomMargin").css("margin-bottom", "0px");
    //$("body").css("position", "relative").css("top", "0px");
    $('.navbarSide').addClass('reveal');
    $(".overlay").fadeIn(500);
    $('html, body').css({
      overflow: 'hidden',
      height: '100%'
    });
  });
  
  $(".navbottomMargin-m").css("margin-bottom", $(".navbar").css("height"));
  
  $('.overlay').on('click', function() {
    //$(".navbottomMargin").css("margin-bottom", "53px");
    //$("#appContainer").css("position", "initial").css("top", "0px");
    $('.navbarSide').removeClass('reveal');
    $('.overlay').fadeOut(500, function () {
      $(".navbar-side").hide();
    })
    $('html, body').css({
      overflow: 'auto',
      height: 'auto'
    });
  });
  
  $(".side-link").on('click', function() {
    $(".overlay").click();
  });
  
  $("#searchUser").click(function() {
    var searchValue = $("#searchValue").val();
    if (searchValue.length != 0) {
      if ($("#searchValue").attr("placeholder") == "Username") {
        window.location = "/users/" + searchValue;
      }else if ($("#searchValue").attr("placeholder") == "Group name") {
        window.location = "/groups/search/" + searchValue;
      }
    }else{
      $("#navSearch").addClass("has-error");
    }
  })
  
  if ($(window).width() < 1200) {
    $("#searchUser").hide();
    $("#searchValue").hide();
    $("#switchSearch").hide();
  }else{
    $("#searchUser").show();
    $("#searchValue").show();
    $("#switchSearch").show();
  }
  
  $(window).on('resize', function() {
    if ($(window).width() < 1200) {
      $("#searchUser").hide();
      $("#searchValue").hide();
      $("#switchSearch").hide();
    }else{
      $("#searchUser").show();
      $("#searchValue").show();
      $("#switchSearch").show();
    }
  });
  
  // Toggle on enter
  $("#searchValue").keyup(function(event) {
    if(event.keyCode == 13) {
      $("#searchUser").click();
    }
  })
  
  $("#switchSearch").click(function() {
    if ($("#searchValue").attr("placeholder") == "Username") {
      $("#searchValue").attr("placeholder", "Group name")
    }else if ($("#searchValue").attr("placeholder") == "Group name") {
      $("#searchValue").attr("placeholder", "Username")
    }
  })
  
  doPing();
  setInterval(function(){
    doPing();
  }, 30000);
});

function showDMCA() {
  $(".gModalContent").html('<h2>DMCA</h2><p>If you find anything that violates, please <a href="mailto:dmca@gtoria.net">send an email</a>. Please remember that Graphictoria is non-profit and also hosted in a country where a DMCA can be ignored.</p><p style="color:grey">Note: If we notice that your message is not coming from the actual sender, we will ignore your email. Do not waste your time sending fake emails because we will find that out. If you are serious about sending legal inqueries, do it from an email we can verify is real.</p><p style="color:grey">We have received a bunch of fake DMCAs up to the point we can never be sure if it is real or fake. If you are sending a real email and are actually the owner of a copyright, use your legal email and use the actual email server so we can verify headers.<br><br>Graphictoria is not willfully wanting to destroy anyone\'s brand or property, we exist because we want to revive older versions of an amazing game and do things not possible on the real platform. We do not sell any memberships, and we will never do so. We give full rights to their respective owners.<br><br>Because we are unable to see who sends an email through CloudFlare abuse (I could impersonate a big company and get away with it, and so can everyone else), we will be ignoring all messages sent through that.</p>');
  $('.globalModal').modal({ show: true});
}

}
/*
     FILE ARCHIVED ON 14:52:27 Jun 01, 2021 AND RETRIEVED FROM THE
     INTERNET ARCHIVE ON 06:50:25 Feb 23, 2024.
     JAVASCRIPT APPENDED BY WAYBACK MACHINE, COPYRIGHT INTERNET ARCHIVE.

     ALL OTHER CONTENT MAY ALSO BE PROTECTED BY COPYRIGHT (17 U.S.C.
     SECTION 108(a)(3)).
*/
/*
playback timings (ms):
  exclusion.robots: 2.481
  exclusion.robots.policy: 0.223
  cdx.remote: 0.111
  esindex: 0.01
  LoadShardBlock: 220.45 (6)
  PetaboxLoader3.datanode: 369.992 (8)
  load_resource: 404.83 (2)
  PetaboxLoader3.resolve: 210.807 (2)
*/