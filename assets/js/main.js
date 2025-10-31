/*=== Javascript function indexing hear===========

1.counterUp ----------(Its use for counting number)
2.stickyHeader -------(header class sticky)
3.wowActive ----------( Waw js plugins activation)
4.swiperJs -----------(All swiper in this website hear)
5.salActive ----------(Sal animation for card and all text)
6.textChanger --------(Text flip for banner section)
7.timeLine -----------(History Time line)
8.datePicker ---------(On click date calender)
9.timePicker ---------(On click time picker)
10.timeLineStory -----(History page time line)
11.vedioActivation----(Vedio activation)
12.searchOption ------(search open)
13.cartBarshow -------(Cart sode bar)
14.sideMenu ----------(Open side menu for desktop)
15.Back to top -------(back to top)
16.filterPrice -------(Price filtering)

==================================================*/

(function (R$) {
    'use strict';
    var rtsJs = {
        m: function (e) {
            rtsJs.d();
            rtsJs.methods();
        },
        d: function (e) {
            this._window = R$(window),
            this._document = R$(document),
            this._body = R$('body'),
            this._html = R$('html')
        },
        methods: function (e) {
            rtsJs.preloader();
            rtsJs.stickyHeader();
            rtsJs.backToTopInit();
            rtsJs.swiperActivation();
            rtsJs.cartNumberIncDec();
            rtsJs.countDown();
            // rtsJs.zoonImage();
            rtsJs.modalpopupCard();
            rtsJs.filter();
            rtsJs.counterUp();
            rtsJs.niceSelect();
            rtsJs.stickySidebar();
            rtsJs.sideMenu();
            rtsJs.searchOption();
            rtsJs.menuCurrentLink();
            rtsJs.modalOver();
            rtsJs.darklightSwitcher();
        },

        preloader: function(e){
          R$(window).on('load', function () {
            R$("#rts__preloader").delay(0).fadeOut(1000);
          })
          R$(window).on('load', function () {
            R$("#weiboo-load").delay(0).fadeOut(1000);
          })
        },
        
        // sticky Header Activation
        stickyHeader: function (e) {
          R$(window).scroll(function () {
              if (R$(this).scrollTop() > 150) {
                  R$('.header--sticky').addClass('sticky')
              } else {
                  R$('.header--sticky').removeClass('sticky')
              }
          })
        },

        // backto Top Area Start
        backToTopInit: function () {
          R$(document).ready(function(){
          "use strict";
      
          var progressPath = document.querySelector('.progress-wrap path');
          var pathLength = progressPath.getTotalLength();
          progressPath.style.transition = progressPath.style.WebkitTransition = 'none';
          progressPath.style.strokeDasharray = pathLength + ' ' + pathLength;
          progressPath.style.strokeDashoffset = pathLength;
          progressPath.getBoundingClientRect();
          progressPath.style.transition = progressPath.style.WebkitTransition = 'stroke-dashoffset 10ms linear';		
          var updateProgress = function () {
            var scroll = R$(window).scrollTop();
            var height = R$(document).height() - R$(window).height();
            var progress = pathLength - (scroll * pathLength / height);
            progressPath.style.strokeDashoffset = progress;
          }
          updateProgress();
          R$(window).scroll(updateProgress);	
          var offset = 150;
          var duration = 500;
          jQuery(window).on('scroll', function() {
            if (jQuery(this).scrollTop() > offset) {
              jQuery('.progress-wrap').addClass('active-progress');
            } else {
              jQuery('.progress-wrap').removeClass('active-progress');
            }
          });				
          jQuery('.progress-wrap').on('click', function(event) {
            event.preventDefault();
            jQuery('html, body').animate({scrollTop: 0}, duration);
            return false;
          })
          
          
        });
  
        },


        swiperActivation: function(){
          R$(document).ready(function(){
            let defaults = {
              spaceBetween: 30,
              slidesPerView: 2
            };
            // call init function
            initSwipers(defaults);
            
            function initSwipers(defaults = {}, selector = ".swiper-data") {
              let swipers = document.querySelectorAll(selector);
              swipers.forEach((swiper) => {
                // get options
                let optionsData = swiper.dataset.swiper
                  ? JSON.parse(swiper.dataset.swiper)
                  : {};
                // combine defaults and custom options
                let options = {
                  ...defaults,
                  ...optionsData
                };
            
                //console.log(options);
                // init
                new Swiper(swiper, options);
              });
            }
            
          })

          R$(document).ready(function () {

            var sliderThumbnail = new Swiper(".slider-thumbnail", {
              spaceBetween: 20,
              slidesPerView: 3,
              freeMode: true,
              watchSlidesVisibility: true,
              watchSlidesProgress: true,
              breakpoints: {
                991: {
                  spaceBetween: 30,
                },
                320: {
                  spaceBetween: 15,
                }
              },
            });
    
            var swiper = new Swiper(".swiper-container-h12", {
              thumbs: {
                swiper: sliderThumbnail,
              },
            });
    
          });

        },


        cartNumberIncDec: function(){
          R$(document).ready(function(){
            
            R$(function () {
              R$(".button").on("click", function () {
                var R$button = R$(this);
                var R$parent = R$button.parents('.quantity-edit');
                var oldValue = R$parent.find('.input').val();
          
                if (R$button.text() == "+") {
                  var newVal = parseFloat(oldValue) + 1;
                } else {
                  // Don't allow decrementing below zero
                  if (oldValue > 1) {
                    var newVal = parseFloat(oldValue) - 1;
                  } else {
                    newVal = 1;
                  }
                }
                R$parent.find('a.add-to-cart').attr('data-quantity', newVal);
                R$parent.find('.input').val(newVal);
              });
            });
          });

          R$(".coupon-click").on('click', function (){
            R$(this).parents('.coupon-input-area-1').find(".coupon-input-area").toggleClass('show');
          });

          R$('.close-c1').on('click', function () {
            R$('.close-c1'),R$(this).parents( '.cart-item-1' ).addClass('deactive');
          });
        
        },

        countDown: function(){
          R$(function() {
            countDown.init();
            updateCountdowns();
            setInterval(updateCountdowns, 1000);
          
            function updateCountdowns() {
              countDown.validElements.forEach((element, i) => {
                countDown.changeTime(element, countDown.endDate[i], i);
              });
            }
          });
          
          const countDown = {
            endDate: [],
            validElements: [],
            display: [],
            initialHeight: undefined,
            initialInnerDivMarginTop: undefined,
            originalBorderTopStyle: undefined,
          
            init: function() {
              R$('.countDown').each(function() {
                const regex_match = R$(this).text().match(/([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4}) ([0-9]{2}):([0-9]{2}):([0-9]{2})/);
                if (!regex_match) return;
          
                const end = new Date(regex_match[3], regex_match[2] - 1, regex_match[1], regex_match[4], regex_match[5], regex_match[6]);
          
                if (end > new Date()) {
                  countDown.validElements.push(R$(this));
                  countDown.endDate.push(end);
                  countDown.changeTime(R$(this), end, countDown.validElements.length - 1);
                  R$(this).html(countDown.display.next.map(item => `<div class='container'><div class='a'><div>R${item}</div></div></div>`).join(''));
                } else {
                  // Display your message when the countdown expires
                  R$(this).html("<p class='end'>Sorry, your session has expired.</p>");
                }
              });
            },
          
            reset: function(element) {
              // This function appears to be incomplete, as it currently doesn't do anything.
            },
          
            changeTime: function(element, endTime) {
              if (!endTime) return;
          
              const today = new Date();
              if (today.getTime() <= endTime.getTime()) {
                countDown.display = {
                  'last': this.calcTime(endTime.getTime() - today.getTime() + 1000),
                  'next': this.calcTime(endTime.getTime() - today.getTime())
                };
                countDown.display.next = countDown.display.next.map(item => item.toString().padStart(2, '0'));
                countDown.display.last = countDown.display.last.map(item => item.toString().padStart(2, '0'));
          
                element.find('div.container div.a div').each((index, div) => {
                  R$(div).text(countDown.display.last[index]);
                });
          
                this.reset(element.find('div.container'));
              } else {
                element.html("<p class='end'>Sorry, your session has expired.</p>");
              }
            },
          
            calcTime: function(milliseconds) {
              const secondsTotal = Math.floor(milliseconds / 1000);
              const days = Math.floor(secondsTotal / 86400);
              const hours = Math.floor((secondsTotal % 86400) / 3600);
              const minutes = Math.floor((secondsTotal % 3600) / 60);
              const seconds = secondsTotal % 60;
              return [days, hours, minutes, seconds];
            }
          };
          
        },


        zoonImage: function(){
          R$(document).ready(function(){
            function imageZoom(imgID, resultID) {
              var img, lens, result, cx, cy;
              img = document.getElementById(imgID);
              result = document.getElementById(resultID);
              /*create lens:*/
              lens = document.createElement("DIV");
              lens.setAttribute("class", "img-zoom-lens");
              /*insert lens:*/
              img.parentElement.insertBefore(lens, img);
              /*calculate the ratio between result DIV and lens:*/
              cx = result.offsetWidth / lens.offsetWidth;
              cy = result.offsetHeight / lens.offsetHeight;
              /*set background properties for the result DIV:*/
              result.style.backgroundImage = "url('" + img.src + "')";
              result.style.backgroundSize = (img.width * cx) + "px " + (img.height * cy) + "px";
              /*execute a function when someone moves the cursor over the image, or the lens:*/
              lens.addEventListener("mousemove", moveLens);
              img.addEventListener("mousemove", moveLens);
              /*and also for touch screens:*/
              lens.addEventListener("touchmove", moveLens);
              img.addEventListener("touchmove", moveLens);
              function moveLens(e) {
                var pos, x, y;
                /*prevent any other actions that may occur when moving over the image:*/
                e.preventDefault();
                /*get the cursor's x and y positions:*/
                pos = getCursorPos(e);
                /*calculate the position of the lens:*/
                x = pos.x - (lens.offsetWidth / 2);
                y = pos.y - (lens.offsetHeight / 2);
                /*prevent the lens from being positioned outside the image:*/
                if (x > img.width - lens.offsetWidth) {x = img.width - lens.offsetWidth;}
                if (x < 0) {x = 0;}
                if (y > img.height - lens.offsetHeight) {y = img.height - lens.offsetHeight;}
                if (y < 0) {y = 0;}
                /*set the position of the lens:*/
                lens.style.left = x + "px";
                lens.style.top = y + "px";
                /*display what the lens "sees":*/
                result.style.backgroundPosition = "-" + (x * cx) + "px -" + (y * cy) + "px";
              }
              function getCursorPos(e) {
                var a, x = 0, y = 0;
                e = e || window.event;
                /*get the x and y positions of the image:*/
                a = img.getBoundingClientRect();
                /*calculate the cursor's x and y coordinates, relative to the image:*/
                x = e.pageX - a.left;
                y = e.pageY - a.top;
                /*consider any page scrolling:*/
                x = x - window.pageXOffset;
                y = y - window.pageYOffset;
                return {x : x, y : y};
              }
            }
  
            imageZoom("myimage", "myresult");


          });
        },


        modalpopupCard: function(){
            // Newsletter popup
              R$(document).ready(function () {
                function showpanel() {
                  R$(".anywere-home").addClass("bgshow");
                  R$(".rts-newsletter-popup").addClass("popup");
                }
                setTimeout(showpanel, 4000)
              });

              R$(".anywere-home").on('click', function () {
                R$(".rts-newsletter-popup").removeClass("popup")
                R$(".anywere-home").removeClass("bgshow")
              });
              R$(".newsletter-close-btn").on('click', function () {
                R$(".rts-newsletter-popup").removeClass("popup")
                R$(".anywere-home").removeClass("bgshow")
              });

              // Product details popup
              R$(".product-details-popup-btn").on('click', function () {
                R$(".product-details-popup-wrapper").addClass("popup")
                R$("#anywhere-home").addClass("bgshow");
              });
              R$(".product-bottom-action .view-btn").on('click', function () {
                R$(".product-details-popup-wrapper").addClass("popup");
                R$("#anywhere-home").addClass("bgshow");
              });
              R$(".product-details-popup-wrapper .cart-edit").on('click', function () {
                R$(".product-details-popup-wrapper").addClass("popup");
                R$("#anywhere-home").addClass("bgshow");
              });

              R$(".product-details-close-btn").on('click', function () {
                R$(".product-details-popup-wrapper").removeClass("popup");
                R$("#anywhere-home").removeClass("bgshow");
              });

              R$(".message-show-action").on('click', function () {
                R$(".successfully-addedin-wishlist").show(500);
                R$("#anywhere-home").addClass("bgshow");
              });

              R$("#anywhere-home").on('click', function () {
                R$(".successfully-addedin-wishlist").hide(0);
                R$("#anywhere-home").removeClass("bgshow");
              });

              R$("#anywhere-home").on('click', function () {
                R$(".product-details-popup-wrapper").removeClass("popup");
                R$("#anywhere-home").removeClass("bgshow");
              });



              // anywhere home

              R$(document).ready(function () {
                function showpanel() {
                  R$(".anywere-home").addClass("bgshow");
                  R$(".rts-newsletter-popup").addClass("popup");
                }
                setTimeout(showpanel, 4000)
              });
            
              R$(".anywere-home").on('click', function () {
                R$(".rts-newsletter-popup").removeClass("popup");
                R$(".anywere-home").removeClass("bgshow");
              });
              R$(".newsletter-close-btn").on('click', function () {
                R$(".rts-newsletter-popup").removeClass("popup")
                R$(".anywere-home").removeClass("bgshow")
              });
            
              // Product details popup
              R$(".product-details-popup-btn").on('click', function () {
                R$(".product-details-popup-wrapper").addClass("popup")
                R$(".anywere").addClass("bgshow");
              });
              R$(".product-bottom-action .view-btn").on('click', function () {
                R$(".product-details-popup-wrapper").addClass("popup");
                R$(".anywere").addClass("bgshow");
              });
              R$(".product-details-popup-wrapper .cart-edit").on('click', function () {
                R$(".product-details-popup-wrapper").addClass("popup");
                R$(".anywere-home").addClass("bgshow");
              });
            
              R$(".product-details-close-btn").on('click', function () {
                R$(".product-details-popup-wrapper").removeClass("popup");
                R$(".anywere").removeClass("bgshow");
              });
              R$(".anywere").on('click', function () {
                R$(".product-details-popup-wrapper").removeClass("popup");
                R$(".anywere").removeClass("bgshow");
              });


              R$('.section-activation').on('click', function () {
                R$('.section-activation'),R$(this).parents( '.item-parent' ).addClass('deactive');
              });
              
        },

        filter: function(){
            // Filter item
          R$(document).on('click', '.filter-btn', function () {
            var show = R$(this).data('show');
            R$(show).removeClass("hide").siblings().addClass("hide");
          });

          R$(document).on('click', '.filter-btn', function () {
            R$(this).addClass('active').siblings().removeClass('active');
          })
          
        },

        counterUp: function () {
          R$('.counter').counterUp({
              delay: 10,
              time: 2000
          });
          R$('.counter').addClass('animated fadeInDownBig');
          R$('h3').addClass('animated fadeIn');

        },

        niceSelect : function(){
          (function(R$) {

            R$.fn.niceSelect = function(method) {
              
              // Methods
              if (typeof method == 'string') {      
                if (method == 'update') {
                  this.each(function() {
                    var R$select = R$(this);
                    var R$dropdown = R$(this).next('.nice-select');
                    var open = R$dropdown.hasClass('open');
                    
                    if (R$dropdown.length) {
                      R$dropdown.remove();
                      create_nice_select(R$select);
                      
                      if (open) {
                        R$select.next().trigger('click');
                      }
                    }
                  });
                } else if (method == 'destroy') {
                  this.each(function() {
                    var R$select = R$(this);
                    var R$dropdown = R$(this).next('.nice-select');
                    
                    if (R$dropdown.length) {
                      R$dropdown.remove();
                      R$select.css('display', '');
                    }
                  });
                  if (R$('.nice-select').length == 0) {
                    R$(document).off('.nice_select');
                  }
                } else {
                  console.log('Method "' + method + '" does not exist.')
                }
                return this;
              }
                
              // Hide native select
              this.hide();
              
              // Create custom markup
              this.each(function() {
                var R$select = R$(this);
                
                if (!R$select.next().hasClass('nice-select')) {
                  create_nice_select(R$select);
                }
              });
              
              function create_nice_select(R$select) {
                R$select.after(R$('<div></div>')
                  .addClass('nice-select')
                  .addClass(R$select.attr('class') || '')
                  .addClass(R$select.attr('disabled') ? 'disabled' : '')
                  .attr('tabindex', R$select.attr('disabled') ? null : '0')
                  .html('<span class="current"></span><ul class="list"></ul>')
                );
                  
                var R$dropdown = R$select.next();
                var R$options = R$select.find('option');
                var R$selected = R$select.find('option:selected');
                
                R$dropdown.find('.current').html(R$selected.data('display') || R$selected.text());
                
                R$options.each(function(i) {
                  var R$option = R$(this);
                  var display = R$option.data('display');

                  R$dropdown.find('ul').append(R$('<li></li>')
                    .attr('data-value', R$option.val())
                    .attr('data-display', (display || null))
                    .addClass('option' +
                      (R$option.is(':selected') ? ' selected' : '') +
                      (R$option.is(':disabled') ? ' disabled' : ''))
                    .html(R$option.text())
                  );
                });
              }
              
              /* Event listeners */
              
              // Unbind existing events in case that the plugin has been initialized before
              R$(document).off('.nice_select');
              
              // Open/close
              R$(document).on('click.nice_select', '.nice-select', function(event) {
                var R$dropdown = R$(this);
                
                R$('.nice-select').not(R$dropdown).removeClass('open');
                R$dropdown.toggleClass('open');
                
                if (R$dropdown.hasClass('open')) {
                  R$dropdown.find('.option');  
                  R$dropdown.find('.focus').removeClass('focus');
                  R$dropdown.find('.selected').addClass('focus');
                } else {
                  R$dropdown.focus();
                }
              });
              
              // Close when clicking outside
              R$(document).on('click.nice_select', function(event) {
                if (R$(event.target).closest('.nice-select').length === 0) {
                  R$('.nice-select').removeClass('open').find('.option');  
                }
              });
              
              // Option click
              R$(document).on('click.nice_select', '.nice-select .option:not(.disabled)', function(event) {
                var R$option = R$(this);
                var R$dropdown = R$option.closest('.nice-select');
                
                R$dropdown.find('.selected').removeClass('selected');
                R$option.addClass('selected');
                
                var text = R$option.data('display') || R$option.text();
                R$dropdown.find('.current').text(text);
                
                R$dropdown.prev('select').val(R$option.data('value')).trigger('change');
              });

              // Keyboard events
              R$(document).on('keydown.nice_select', '.nice-select', function(event) {    
                var R$dropdown = R$(this);
                var R$focused_option = R$(R$dropdown.find('.focus') || R$dropdown.find('.list .option.selected'));
                
                // Space or Enter
                if (event.keyCode == 32 || event.keyCode == 13) {
                  if (R$dropdown.hasClass('open')) {
                    R$focused_option.trigger('click');
                  } else {
                    R$dropdown.trigger('click');
                  }
                  return false;
                // Down
                } else if (event.keyCode == 40) {
                  if (!R$dropdown.hasClass('open')) {
                    R$dropdown.trigger('click');
                  } else {
                    var R$next = R$focused_option.nextAll('.option:not(.disabled)').first();
                    if (R$next.length > 0) {
                      R$dropdown.find('.focus').removeClass('focus');
                      R$next.addClass('focus');
                    }
                  }
                  return false;
                // Up
                } else if (event.keyCode == 38) {
                  if (!R$dropdown.hasClass('open')) {
                    R$dropdown.trigger('click');
                  } else {
                    var R$prev = R$focused_option.prevAll('.option:not(.disabled)').first();
                    if (R$prev.length > 0) {
                      R$dropdown.find('.focus').removeClass('focus');
                      R$prev.addClass('focus');
                    }
                  }
                  return false;
                // Esc
                } else if (event.keyCode == 27) {
                  if (R$dropdown.hasClass('open')) {
                    R$dropdown.trigger('click');
                  }
                // Tab
                } else if (event.keyCode == 9) {
                  if (R$dropdown.hasClass('open')) {
                    return false;
                  }
                }
              });

              // Detect CSS pointer-events support, for IE <= 10. From Modernizr.
              var style = document.createElement('a').style;
              style.cssText = 'pointer-events:auto';
              if (style.pointerEvents !== 'auto') {
                R$('html').addClass('no-csspointerevents');
              }
              
              return this;

            };

          }(jQuery));

          /* Initialize */

          R$(document).ready(function() {
            R$('select').not('#bairro').niceSelect();
          });
        },

        stickySidebar: function () {
          if (typeof R$.fn.theiaStickySidebar !== "undefined") {
            R$(".rts-sticky-column-item").theiaStickySidebar({
              additionalMarginTop: 130,
            });
          }
        },

        sideMenu:function(){
          R$(document).on('click', '.menu-btn', function () {
            R$("#side-bar").addClass("show");
            R$("#anywhere-home").addClass("bgshow");
          });
          R$(document).on('click', '.close-icon-menu', function () {
            R$("#side-bar").removeClass("show");
            R$("#anywhere-home").removeClass("bgshow");
          });
          R$(document).on('click', '#anywhere-home', function () {
            R$("#side-bar").removeClass("show");
            R$("#anywhere-home").removeClass("bgshow");
          });
          R$(document).on('click', '.onepage .mainmenu li a', function () {
            R$("#side-bar").removeClass("show");
            R$("#anywhere-home").removeClass("bgshow");
          });
          R$('#mobile-menu-active').metisMenu();
          R$('#category-active-four').metisMenu();
          R$('#category-active-menu').metisMenu();
          R$('.category-active-menu-sidebar').metisMenu();
        },

        // search popup
        searchOption: function () {
        R$(document).on('click', '#search', function () {
          R$(".search-input-area").addClass("show");
          R$("#anywhere-home").addClass("bgshow");
        });
        R$(document).on('click', '#close', function () {
          R$(".search-input-area").removeClass("show");
          R$("#anywhere-home").removeClass("bgshow");
        });
        R$(document).on('click', '#anywhere-home', function () {
          R$(".search-input-area").removeClass("show");
          R$("#anywhere-home").removeClass("bgshow");
        });
        },

        menuCurrentLink: function () {
          var currentPage = location.pathname.split("/"),
          current = currentPage[currentPage.length-1];
          R$('.parent-nav li a').each(function(){
              var R$this = R$(this);
              if(R$this.attr('href') === current){
                  R$this.addClass('active');
                  R$this.parents('.has-dropdown').addClass('menu-item-open')
              }
          });
        },

        
        modalOver: function(){
          R$(document).ready(function () {
            // Declare a variable to keep track of the modal state
            var modalShown = false;
            
            // Function to show the modal after a delay
            function showModal() {
              if (!modalShown) {
                setTimeout(function () {
                  R$("#myModal-1").modal('show');
                  modalShown = true; // Set the flag to true when the modal is shown
                }, 2000);
              }
            }
          
            // Show the modal when the document is ready
            showModal();
          
            // Set the flag to false when the modal is closed
            R$('#myModal-1').on('hidden.bs.modal', function () {
              modalShown = false;
            });
          });
        
        },


        darklightSwitcher: function(){
          let html = document.documentElement;
          let rtsTheme = localStorage.theme;
          let rtsThemeLayout = localStorage.layout;
          let rtsThemeNavbar = localStorage.navbar;

          let darkMode = rtsTheme === "dark";
          let rtlLayout = rtsThemeLayout === "rtl";
          let topMenu = rtsThemeNavbar === "top";

          // Theme Mode Toggle 
          if (rtsTheme) {
              html.setAttribute("data-theme", rtsTheme);

              if (rtsTheme === "dark") {
                  localStorage.theme === "dark"
                  R$(".rts-customizer__btn--light").removeClass("active");
                  R$(".rts-customizer__btn--dark").addClass("active");
                  
              } else {
                  localStorage.theme === "light"
              }
          }

          // Theme Layout Toggle
          if (rtsThemeLayout) {

              html.setAttribute("dir", rtsThemeLayout);

              if (rtsThemeLayout === "rtl") {
                  localStorage.themeLayout === "rtl"
                  R$(".rts-customizer__btn--ltr").removeClass("active");
                  R$(".rts-customizer__btn--rtl").addClass("active");
              } else {
                  localStorage.themeLayout === "ltr"
              }
          }

            // RTL Layout
          function rtlTheme(e) {
              let rtsThemeLayout = "rtl";
              localStorage.layout = rtsThemeLayout;
              document.documentElement.setAttribute("dir", rtsThemeLayout);

              rtlLayout = true;
          }

          // LTR Layout
          function ltrTheme(e) {
              let rtsThemeLayout = "ltr";
              localStorage.layout =  rtsThemeLayout;
              document.documentElement.setAttribute("dir", rtsThemeLayout);

              rtlLayout = false;
          }

          // LTR Layout Add
          R$(".rts-customizer__btn--ltr").click(function() {
              R$(".rts-customizer__btn--rtl").removeClass("active");
              R$(".rts-customizer__btn--ltr").addClass("active");

              ltrTheme();

              if(R$("body").hasClass("layout-rtl")) {
                R$("body").removeClass("layout-rtl");
              }
              R$('html').attr('dir', 'ltr');
              R$("body").addClass("layout-ltr");
          })

          // RTL Layout Add
          R$(".rts-customizer__btn--rtl").click(function() {
              R$(".rts-customizer__btn--ltr").removeClass("active");
              R$(".rts-customizer__btn--rtl").addClass("active");
              
              rtlTheme();
          })
          R$('.button-setting-rtl-ltr').click(function(){
            R$('.rts-ltr-rtl-setting-button-area').toggleClass("active");
          })

        },

        
    }

    rtsJs.m();
  })(jQuery, window) 



  function zoom(e) {
    var zoomer = e.currentTarget;
    e.offsetX ? offsetX = e.offsetX : offsetX = e.touches[0].pageX
    e.offsetY ? offsetY = e.offsetY : offsetX = e.touches[0].pageX
    x = offsetX / zoomer.offsetWidth * 100
    y = offsetY / zoomer.offsetHeight * 100
    zoomer.style.backgroundPosition = x + '% ' + y + '%';
  }









