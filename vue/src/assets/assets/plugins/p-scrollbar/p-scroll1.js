$(document).ready(()=>{
  (function($) {
    "use strict";
    $(document).click(()=> {
      if (document.querySelector('.app-sidebar3')) {
        let sidebar = document.querySelector('.app-sidebar3')
        const ps = new PerfectScrollbar(sidebar, {
          useBothWheelAxes: true,
          suppressScrollX: true,
        });
      }
    })
  })($);
})

