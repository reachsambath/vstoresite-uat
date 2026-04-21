jQuery(document).ready(function ($) {
	if ($('.pcp-carousel-wrapper').length > 0) {
		function swiperSlide(selector, options) {
			if( 1 === parseInt( pcp_vars.swiperEnqueue ) ) {
				return new PCPSwiper( selector, options );
			} else {
				return new Swiper(selector, options);
			}
		}
		$('.pcp-carousel-wrapper').each(function () {
			var pcp_container = $(this),
				pcp_container_id = pcp_container.attr('id'),
				pcpCarousel = $('#' + pcp_container_id + ' .sp-pcp-carousel:not(.swiper-initialized)'),
				pcpCarouselData = pcpCarousel.data('carousel');
			if (pcpCarousel.length > 0) {
				// Carousel Swiper for Standard mode.
				var pcpSwiper = swiperSlide('#' + pcp_container_id + ' .sp-pcp-carousel', {
					speed: pcpCarouselData.speed,
					slidesPerView: pcpCarouselData.slidesPerView.mobile,
					spaceBetween: pcpCarouselData.spaceBetween,
					loop: pcpCarouselData.loop,
					loopFillGroupWithBlank: true,
					autoHeight: pcpCarouselData.autoHeight,
					simulateTouch: pcpCarouselData.simulateTouch,
					freeMode: pcpCarouselData.freeMode,
					allowTouchMove: pcpCarouselData.allowTouchMove,
					centeredSlides: pcpCarouselData.center_mode,
					mousewheel: pcpCarouselData.slider_mouse_wheel,
					lazy: pcpCarouselData.lazy,
					//grabCursor: true,
					pagination:
						pcpCarouselData.pagination == true
							? {
								el: '.swiper-pagination',
								clickable: true,
								dynamicBullets: pcpCarouselData.dynamicBullets,
								renderBullet: function (index, className) {
									if (pcpCarouselData.bullet_types == 'number') {
										return (
											'<span class="' +
											className +
											'">' +
											(index + 1) +
											'</span>'
										)
									} else {
										return '<span class="' + className + '"></span>'
									}
								}
							}
							: false,
					autoplay: {
						delay: pcpCarouselData.autoplay_speed
					},
					navigation:
						pcpCarouselData.navigation == true
							? {
								nextEl: '.pcp-button-next',
								prevEl: '.pcp-button-prev'
							}
							: false,
					breakpoints: {
						576: {
							slidesPerView: pcpCarouselData.slidesPerView.mobile_landscape,
							navigation:
								pcpCarouselData.navigation_mobile == true
									? {
										nextEl: '.pcp-button-next',
										prevEl: '.pcp-button-prev'
									}
									: false,
							pagination:
								pcpCarouselData.pagination_mobile == true
									? {
										el: '.swiper-pagination',
										clickable: true,
										dynamicBullets: pcpCarouselData.dynamicBullets,
										renderBullet: function (index, className) {
											if (pcpCarouselData.bullet_types == 'number') {
												return (
													'<span class="' +
													className +
													'">' +
													(index + 1) +
													'</span>'
												)
											} else {
												return '<span class="' + className + '"></span>'
											}
										}
									}
									: false
						},
						768: {
							slidesPerView: pcpCarouselData.slidesPerView.tablet,
						},
						992: {
							slidesPerView: pcpCarouselData.slidesPerView.desktop,
						},
						1200: {
							slidesPerView: pcpCarouselData.slidesPerView.lg_desktop,
						},
					},
					fadeEffect: {
						crossFade: true
					},
					ally: {
						enabled: pcpCarouselData.enabled,
						prevSlideMessage: pcpCarouselData.prevSlideMessage,
						nextSlideMessage: pcpCarouselData.nextSlideMessage,
						firstSlideMessage: pcpCarouselData.firstSlideMessage,
						lastSlideMessage: pcpCarouselData.lastSlideMessage,
						paginationBulletMessage: pcpCarouselData.paginationBulletMessage
					},
					keyboard: {
						enabled: pcpCarouselData.keyboard === "true" ? true : false,
					}
				})
				if (pcpCarouselData.autoplay === false) {
					pcpSwiper.autoplay.stop()
				}
				if (pcpCarouselData.stop_onHover && pcpCarouselData.autoplay) {
					$(pcpCarousel).on({
						mouseenter: function () {
							pcpSwiper.autoplay.stop();
						},
						mouseleave: function () {
							pcpSwiper.autoplay.start();
						}
					});
				}
				$(window).on('resize', function () {
					pcpSwiper.update()
				})
				$(window).trigger("resize")
			}
		})
	}
	/* Preloader js */
	$(document).ready(function () {
		$('.sp-pcp-section').each(function () {
		var pcp_container = $(this).attr('id');
		var PCP_Wrapper_ID = '#' + pcp_container;
		$(".pcp-preloader", PCP_Wrapper_ID).css({ "backgroundImage": "none", "visibility": "hidden" });
		})
	});
	
	// Add class for gutenberg block.
	$('.sp-pcp-section').addClass('sp-pcp-section-loaded');
})
