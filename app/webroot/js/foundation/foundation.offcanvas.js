;(function ($, window, document, undefined) {
  'use strict';

  Foundation.libs.offcanvas = {
    name : 'offcanvas',

    version : '5.0.0',

    settings : {},

    init : function (scope, method, options) {
      this.events();
    },

    events : function () {
      $(this.scope).off('.offcanvas')
        .on('click.fndtn.offcanvas', '.left-off-canvas-toggle', function (e) {
          e.preventDefault();
          if(typeof(remove_all_teams) == "function"){remove_all_teams()};
          $(this).closest('.off-canvas-wrap').toggleClass('move-right');
        })
        .on('click.fndtn.offcanvas', '.exit-off-canvas', function (e) {
          e.preventDefault();
          $(".off-canvas-wrap").removeClass("move-right");
          setTimeout(function(){
			  if(typeof(display_all_teams) == "function"){display_all_teams()};
			}, 100);
          
        })
        .on('click.fndtn.offcanvas', '.right-off-canvas-toggle', function (e) {
          e.preventDefault();
          if(typeof(remove_all_teams) == "function"){remove_all_teams()};
          $(this).closest(".off-canvas-wrap").toggleClass("move-left");
        })
        .on('click.fndtn.offcanvas', '.exit-off-canvas', function (e) {
          e.preventDefault();
          $(".off-canvas-wrap").removeClass("move-left");
          setTimeout(function(){
			  if(typeof(display_all_teams) == "function"){display_all_teams()};
			}, 100);
        });
    },

    reflow : function () {}
  };
}(jQuery, this, this.document));
