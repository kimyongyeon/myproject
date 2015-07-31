/* 그누스트랩5 http://gnustrap.com */

$(document).ready(function($) {

    // 스크롤 이벤트 지정
    $('a.gnustrap-scroll').bind('click', function(event) {
        var $anchor = $(this);
        $('html, body').stop().animate({
            scrollTop: ($($anchor.attr('href')).offset().top - 70)
        }, 1200, 'easeInOutExpo');
        event.preventDefault();
    });
});
/* 텍사스 자동 반응형 */

jQuery(function($) {
$('#version').html('using jQuery ' + $.fn.jquery);
$('.textarea').expandable();});

/* 부트스트랩 팝오버 스크립트 */

$(document).ready(function(){
    $(".popover-top").popover({trigger: 'hover click','placement': 'top'});
});

$(document).ready(function(){
    $(".popover-left").popover({trigger: 'hover click','placement': 'left'});
});
$(document).ready(function(){
    $(".popover-right").popover({trigger: 'hover click','placement': 'right'});
});
$(document).ready(function(){
    $(".popover-bottom").popover({trigger: 'hover click','placement': 'bottom'});
});

/* 부트스트랩 툴팁 스크립트 */

$(document).ready(function(){
    $(".tooltip-top").tooltip({trigger: 'hover click','placement': 'top'});
});
$(document).ready(function(){
    $(".tooltip-left").tooltip({trigger: 'hover click','placement': 'left'});
});
$(document).ready(function(){
    $(".tooltip-right").tooltip({trigger: 'hover click','placement': 'right'});
});
$(document).ready(function(){
    $(".tooltip-bottom").tooltip({trigger: 'hover click','placement': 'bottom'});
});

/* 애니메이션 효과를 주기 위한 스크립트 http://www.justinaguilar.com/animations/ */
/* 사용방법 id="menu2" 를 지정하면 해당 코드줄에 class="animated" 가 있어야함
   <div id="slideUp" class="animated">
   이글자는 브라우저상 어느정도 마우스스크롤하면 실행됩니다
   </div>
*/
		$(window).scroll(function() {
			$('#menu2').each(function(){
			var imagePos = $(this).offset().top;
			
			var topOfWindow = $(window).scrollTop();
				if (imagePos < topOfWindow+400) {
					$(this).addClass("slideUp");
				}
			});

			$('#animated1').each(function(){
			var imagePos = $(this).offset().top;
			
			var topOfWindow = $(window).scrollTop();
				if (imagePos < topOfWindow+800) {
					$(this).addClass("slideUp");
				}
			});

			$('#animated2').each(function(){
			var imagePos = $(this).offset().top;
			
			var topOfWindow = $(window).scrollTop();
				if (imagePos < topOfWindow+700) {
					$(this).addClass("pulse");
				}
			});

			$('#title3').each(function(){
			var imagePos = $(this).offset().top;
			
			var topOfWindow = $(window).scrollTop();
				if (imagePos < topOfWindow+700) {
					$(this).addClass("bounceIn");
				}
			});


			$('#title4').each(function(){
			var imagePos = $(this).offset().top;
			
			var topOfWindow = $(window).scrollTop();
				if (imagePos < topOfWindow+700) {
					$(this).addClass("bounceIn");
				}
			});


			$('#tail').each(function(){
			var imagePos = $(this).offset().top;
			
			var topOfWindow = $(window).scrollTop();
				if (imagePos < topOfWindow+900) {
					$(this).addClass("slideUp");
				}
			});

			$('#slideUp').each(function(){
			var imagePos = $(this).offset().top;
			
			var topOfWindow = $(window).scrollTop();
				if (imagePos < topOfWindow+900) {
					$(this).addClass("slideUp");
				}
			});

			$('#slideDown').each(function(){
			var imagePos = $(this).offset().top;
			
			var topOfWindow = $(window).scrollTop();
				if (imagePos < topOfWindow+900) {
					$(this).addClass("slideDown");
				}
			});

			$('#slideLeft').each(function(){
			var imagePos = $(this).offset().top;
			
			var topOfWindow = $(window).scrollTop();
				if (imagePos < topOfWindow+900) {
					$(this).addClass("slideLeft");
				}
			});

			$('#slideRight').each(function(){
			var imagePos = $(this).offset().top;
			
			var topOfWindow = $(window).scrollTop();
				if (imagePos < topOfWindow+900) {
					$(this).addClass("slideRight");
				}
			});

			$('#SlideExpandUp').each(function(){
			var imagePos = $(this).offset().top;
			
			var topOfWindow = $(window).scrollTop();
				if (imagePos < topOfWindow+900) {
					$(this).addClass("SlideExpandUp");
				}
			});


			$('#ExpandUp').each(function(){
			var imagePos = $(this).offset().top;
			
			var topOfWindow = $(window).scrollTop();
				if (imagePos < topOfWindow+900) {
					$(this).addClass("ExpandUp");
				}
			});


			$('#FadeIn').each(function(){
			var imagePos = $(this).offset().top;
			
			var topOfWindow = $(window).scrollTop();
				if (imagePos < topOfWindow+900) {
					$(this).addClass("FadeIn");
				}
			});


			$('#ExpandOpen').each(function(){
			var imagePos = $(this).offset().top;
			
			var topOfWindow = $(window).scrollTop();
				if (imagePos < topOfWindow+900) {
					$(this).addClass("ExpandOpen");
				}
			});


			$('#BigEntrance').each(function(){
			var imagePos = $(this).offset().top;
			
			var topOfWindow = $(window).scrollTop();
				if (imagePos < topOfWindow+900) {
					$(this).addClass("BigEntrance");
				}
			});


			$('#Hatch').each(function(){
			var imagePos = $(this).offset().top;
			
			var topOfWindow = $(window).scrollTop();
				if (imagePos < topOfWindow+900) {
					$(this).addClass("Hatch");
				}
			});


			$('#Bounce').each(function(){
			var imagePos = $(this).offset().top;
			
			var topOfWindow = $(window).scrollTop();
				if (imagePos < topOfWindow+900) {
					$(this).addClass("Bounce");
				}
			});


			$('#Pulse').each(function(){
			var imagePos = $(this).offset().top;
			
			var topOfWindow = $(window).scrollTop();
				if (imagePos < topOfWindow+900) {
					$(this).addClass("Pulse");
				}
			});


			$('#Floating').each(function(){
			var imagePos = $(this).offset().top;
			
			var topOfWindow = $(window).scrollTop();
				if (imagePos < topOfWindow+900) {
					$(this).addClass("Floating");
				}
			});


			$('#Tossing').each(function(){
			var imagePos = $(this).offset().top;
			
			var topOfWindow = $(window).scrollTop();
				if (imagePos < topOfWindow+900) {
					$(this).addClass("Tossing");
				}
			});


			$('#PullUp').each(function(){
			var imagePos = $(this).offset().top;
			
			var topOfWindow = $(window).scrollTop();
				if (imagePos < topOfWindow+900) {
					$(this).addClass("PullUp");
				}
			});


			$('#PullDown').each(function(){
			var imagePos = $(this).offset().top;
			
			var topOfWindow = $(window).scrollTop();
				if (imagePos < topOfWindow+900) {
					$(this).addClass("PullDown");
				}
			});


			$('#StretchLeft').each(function(){
			var imagePos = $(this).offset().top;
			
			var topOfWindow = $(window).scrollTop();
				if (imagePos < topOfWindow+900) {
					$(this).addClass("StretchLeft");
				}
			});


			$('#StretchRight').each(function(){
			var imagePos = $(this).offset().top;
			
			var topOfWindow = $(window).scrollTop();
				if (imagePos < topOfWindow+900) {
					$(this).addClass("StretchRight");
				}
			});


		});