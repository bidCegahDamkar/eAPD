$(document).ready((function(){"use strict";$("#sidebar-toggle").on("click",(function(){$("body").hasClass("sidebar-hidden")?localStorage.setItem("menu-closed","false"):localStorage.setItem("menu-closed","true"),$("body").toggleClass("sidebar-hidden")})),"false"===localStorage.getItem("menu-closed")?$("body").hasClass("sidebar-hidden")&&$("body").removeClass("sidebar-hidden"):$("body").hasClass("sidebar-hidden")||$("body").addClass("sidebar-hidden"),feather.replace(),function(){if(!$(".page-sidebar").length)return;var e=$(".accordion-menu li:not(.open) ul"),o=$(".accordion-menu li.active-page > a");e.hide();const a=document.querySelector(".page-sidebar"),n=new PerfectScrollbar(a);$(".accordion-menu li a").on("click",(function(e){var o=$(this).next("ul"),a=$(this).parent("li"),t=$(".accordion-menu > li.open");if(o.length)return a.hasClass("open")?(o.slideUp(200),a.removeClass("open"),n.update()):(t.length&&($(".accordion-menu > li.open > ul").slideUp(200),t.removeClass("open"),n.update()),o.slideDown(200),a.addClass("open"),n.update()),!1})),$(".active-page > ul").length&&o.click()}(),function(){$('[data-bs-toggle="popover"]').popover(),$('[data-bs-toggle="tooltip"]').tooltip();var e=document.querySelectorAll(".needs-validation");Array.prototype.slice.call(e).forEach((function(e){e.addEventListener("submit",(function(o){e.checkValidity()||(o.preventDefault(),o.stopPropagation()),e.classList.add("was-validated")}),!1)}))}()})),$(window).on("load",(function(){setTimeout((function(){$("body").addClass("no-loader")}),1e3)}));