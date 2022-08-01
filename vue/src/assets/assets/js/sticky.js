// $(document).ready(function(){
  function all(){
    var stickyElement = $(".sticky"),
      stickyClass = "sticky-pin",
      stickyWrapperElement = $(".sticky-wrapper"),
      stickyWrapperClass = "is-sticky",
      stickyPos = '72', //Distance from the top of the window.
      SupportstickyPos = '01', //Distance from the top of the window.
      stickyHeight;

    //Create a negative margin to prevent content 'jumps':
    if ( $('.jumps-prevent') ) {
      $('.jumps-prevent').remove();
    }
    stickyElement.after('<div class="jumps-prevent"></div>');
    function jumpsPrevent() {
      stickyHeight = stickyElement.innerHeight();
      stickyElement.css({"margin-bottom":"-" + stickyHeight + "px"});
      stickyElement.next().css({"padding-top": + stickyHeight + "px"});
    }
    jumpsPrevent(); //Run.


    //Function trigger:
    $(window).resize(function(){
      jumpsPrevent();

    });

    //Sticker function:
    function stickerFn() {
      var winTop = $(this).scrollTop();
      //Check element position:
      winTop >= stickyPos ?
        stickyElement.addClass(stickyClass):
        stickyElement.removeClass(stickyClass) //Boolean class switcher.
      winTop >= SupportstickyPos ?
        stickyWrapperElement.addClass(stickyWrapperClass):
        stickyWrapperElement.removeClass(stickyWrapperClass) //Boolean class switcher.
    }
    stickerFn(); //Run.

    //Function trigger:
    $(window).scroll(function(){
      stickerFn();
    });
  }
  // all()
  /* setTimeout(all,1500) */
  /* window.onload = ()=>{
    setInterval(all, 50);
  } */
/*   function checkURLchange(){
    if(window.location.href != oldURL){
      all();
      oldURL = window.location.href;
    }
  }
  var oldURL = window.location.href;
  setInterval(checkURLchange, 50);
 */
// });

export default all
