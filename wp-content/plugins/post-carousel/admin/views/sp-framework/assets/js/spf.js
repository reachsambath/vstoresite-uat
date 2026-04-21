/**
 *
 * -----------------------------------------------------------
 *
 * SPF Framework
 *
 * -----------------------------------------------------------
 *
 */
; (function ($, window, document, undefined) {
	'use strict'

	//
	// Constants
	//
	var SP_PC = SP_PC || {}

	SP_PC.funcs = {}

	SP_PC.vars = {
		onloaded: false,
		$body: $('body'),
		$window: $(window),
		$document: $(document),
		is_rtl: $('body').hasClass('rtl'),
		code_themes: []
	}

	//
	// Helper Functions
	//
	SP_PC.helper = {
		//
		// Generate UID
		//
		uid: function (prefix) {
			return (
				(prefix || '') +
				Math.random()
					.toString(36)
					.substr(2, 9)
			)
		},

		// Quote regular expression characters
		//
		preg_quote: function (str) {
			return (str + '').replace(/(\[|\-|\])/g, '\\$1')
		},

		//
		// Rename input names
		//
		name_nested_replace: function ($selector, field_id) {
			var checks = []
			var regex = new RegExp(
				'(' + SP_PC.helper.preg_quote(field_id) + ')\\[(\\d+)\\]',
				'g'
			)

			$selector.find(':radio').each(function () {
				if (this.checked || this.orginal_checked) {
					this.orginal_checked = true
				}
			})

			$selector.each(function (index) {
				$(this)
					.find(':input')
					.each(function () {
						this.name = this.name.replace(regex, field_id + '[' + index + ']')
						if (this.orginal_checked) {
							this.checked = true
						}
					})
			})
		},

		//
		// Debounce
		//
		debounce: function (callback, threshold, immediate) {
			var timeout
			return function () {
				var context = this,
					args = arguments
				var later = function () {
					timeout = null
					if (!immediate) {
						callback.apply(context, args)
					}
				}
				var callNow = immediate && !timeout
				clearTimeout(timeout)
				timeout = setTimeout(later, threshold)
				if (callNow) {
					callback.apply(context, args)
				}
			}
		},

		//
		// Get a cookie
		//
		get_cookie: function (name) {
			var e,
				b,
				cookie = document.cookie,
				p = name + '='

			if (!cookie) {
				return
			}

			b = cookie.indexOf('; ' + p)

			if (b === -1) {
				b = cookie.indexOf(p)

				if (b !== 0) {
					return null
				}
			} else {
				b += 2
			}

			e = cookie.indexOf(';', b)

			if (e === -1) {
				e = cookie.length
			}

			return decodeURIComponent(cookie.substring(b + p.length, e))
		},

		//
		// Set a cookie
		//
		set_cookie: function (name, value, expires, path, domain, secure) {
			var d = new Date()

			if (typeof expires === 'object' && expires.toGMTString) {
				expires = expires.toGMTString()
			} else if (parseInt(expires, 10)) {
				d.setTime(d.getTime() + parseInt(expires, 10) * 1000)
				expires = d.toGMTString()
			} else {
				expires = ''
			}

			document.cookie =
				name +
				'=' +
				encodeURIComponent(value) +
				(expires ? '; expires=' + expires : '') +
				(path ? '; path=' + path : '') +
				(domain ? '; domain=' + domain : '') +
				(secure ? '; secure' : '')
		},

		//
		// Remove a cookie
		//
		remove_cookie: function (name, path, domain, secure) {
			SP_PC.helper.set_cookie(name, '', -1000, path, domain, secure)
		}
	}

	//
	// Custom clone for textarea and select clone() bug
	//
	$.fn.spf_clone = function () {
		var base = $.fn.clone.apply(this, arguments),
			clone = this.find('select').add(this.filter('select')),
			cloned = base.find('select').add(base.filter('select'))

		for (var i = 0; i < clone.length; ++i) {
			for (var j = 0; j < clone[i].options.length; ++j) {
				if (clone[i].options[j].selected === true) {
					cloned[i].options[j].selected = true
				}
			}
		}

		this.find(':radio').each(function () {
			this.orginal_checked = this.checked
		})

		return base
	}

	//
	// Expand All Options
	//
	$.fn.spf_expand_all = function () {
		return this.each(function () {
			$(this).on('click', function (e) {
				e.preventDefault()
				$('.spf-wrapper').toggleClass('spf-show-all')
				$('.spf-section').spf_reload_script()
				$(this)
					.find('.fa')
					.toggleClass('fa-indent')
					.toggleClass('fa-outdent')
			})
		})
	}

	//
	// Options Navigation
	//
	$.fn.spf_nav_options = function () {
		return this.each(function () {
			var $nav = $(this),
				$links = $nav.find('a'),
				$hidden = $nav.closest('.spf').find('.spf-section-id'),
				$last_section

			$(window)
				.on('hashchange', function () {
					var hash = window.location.hash.match(new RegExp('tab=([^&]*)'))
					var slug = hash
						? hash[1]
						: $links
							.first()
							.attr('href')
							.replace('#tab=', '')
					var $link = $('#spf-tab-link-' + slug)

					if ($link.length > 0) {
						$link
							.closest('.spf-tab-depth-0')
							.addClass('spf-tab-active')
							.siblings()
							.removeClass('spf-tab-active')
						$links.removeClass('spf-section-active')
						$link.addClass('spf-section-active')

						if ($last_section !== undefined) {
							$last_section.hide()
						}

						var $section = $('#spf-section-' + slug)
						$section.css({ display: 'block' })
						$section.spf_reload_script()

						$hidden.val(slug)

						$last_section = $section
					}
				})
				.trigger('hashchange')
		})
	}

	//
	// Metabox Tabs
	//
	$.fn.spf_nav_metabox = function () {
		return this.each(function () {
			var $nav = $(this),
				$links = $nav.find('a'),
				unique_id = $nav.data('unique'),
				post_id = $('#post_ID').val() || 'global',
				$last_section,
				$last_link

			$links.on('click', function (e) {
				e.preventDefault()

				var $link = $(this),
					section_id = $link.data('section')

				if ($last_link !== undefined) {
					$last_link.removeClass('spf-section-active')
				}

				if ($last_section !== undefined) {
					$last_section.hide()
				}

				$link.addClass('spf-section-active')

				var $section = $('#spf-section-' + section_id)
				$section.css({ display: 'block' })
				$section.spf_reload_script()

				SP_PC.helper.set_cookie(
					'spf-last-metabox-tab-' + post_id + '-' + unique_id,
					section_id
				)

				$last_section = $section
				$last_link = $link
			})

			var get_cookie = SP_PC.helper.get_cookie(
				'spf-last-metabox-tab-' + post_id + '-' + unique_id
			)

			if (get_cookie) {
				$nav.find('a[data-section="' + get_cookie + '"]').trigger('click')
			} else {
				$links.first('a').trigger('click')
			}
		})
	}

	//
	// Metabox Page Templates Listener
	//
	$.fn.spf_page_templates = function () {
		if (this.length) {
			$(document).on(
				'change',
				'.editor-page-attributes__template select, #page_template',
				function () {
					var maybe_value = $(this).val() || 'default'

					$('.spf-page-templates')
						.removeClass('spf-show')
						.addClass('spf-hide')
					$(
						'.spf-page-' +
						maybe_value.toLowerCase().replace(/[^a-zA-Z0-9]+/g, '-')
					)
						.removeClass('spf-hide')
						.addClass('spf-show')
				}
			)
		}
	}

	//
	// Metabox Post Formats Listener
	//
	$.fn.spf_post_formats = function () {
		if (this.length) {
			$(document).on(
				'change',
				'.editor-post-format select, #formatdiv input[name="post_format"]',
				function () {
					var maybe_value = $(this).val() || 'default'

					// Fallback for classic editor version
					maybe_value = maybe_value === '0' ? 'default' : maybe_value

					$('.spf-post-formats')
						.removeClass('spf-show')
						.addClass('spf-hide')
					$('.spf-post-format-' + maybe_value)
						.removeClass('spf-hide')
						.addClass('spf-show')
				}
			)
		}
	}

	//
	// Search
	//
	$.fn.spf_search = function () {
		return this.each(function () {
			var $this = $(this),
				$input = $this.find('input')

			$input.on('change keyup', function () {
				var value = $(this).val(),
					$wrapper = $('.spf-wrapper'),
					$section = $wrapper.find('.spf-section'),
					$fields = $section.find('> .spf-field:not(.hidden)'),
					$titles = $fields.find('> .spf-title, .spf-search-tags')

				if (value.length > 3) {
					$fields.addClass('spf-hidden')
					$wrapper.addClass('spf-search-all')

					$titles.each(function () {
						var $title = $(this)

						if ($title.text().match(new RegExp('.*?' + value + '.*?', 'i'))) {
							var $field = $title.closest('.spf-field')

							$field.removeClass('spf-hidden')
							$field.parent().spf_reload_script()
						}
					})
				} else {
					$fields.removeClass('spf-hidden')
					$wrapper.removeClass('spf-search-all')
				}
			})
		})
	}

	//
	// Sticky Header
	//
	$.fn.spf_sticky = function () {
		return this.each(function () {
			var $this = $(this),
				$window = $(window),
				$inner = $this.find('.spf-header-inner'),
				padding =
					parseInt($inner.css('padding-left')) +
					parseInt($inner.css('padding-right')),
				offset = 32,
				scrollTop = 0,
				lastTop = 0,
				ticking = false,
				stickyUpdate = function () {
					var offsetTop = $this.offset().top,
						stickyTop = Math.max(offset, offsetTop - scrollTop),
						winWidth = Math.max(
							document.documentElement.clientWidth,
							window.innerWidth || 0
						)

					if (stickyTop <= offset && winWidth > 782) {
						$inner.css({ width: $this.outerWidth() - padding })
						$this.css({ height: $this.outerHeight() }).addClass('spf-sticky')
					} else {
						$inner.removeAttr('style')
						$this.removeAttr('style').removeClass('spf-sticky')
					}
				},
				requestTick = function () {
					if (!ticking) {
						requestAnimationFrame(function () {
							stickyUpdate()
							ticking = false
						})
					}

					ticking = true
				},
				onSticky = function () {
					scrollTop = $window.scrollTop()
					requestTick()
				}

			$window.on('scroll resize', onSticky)

			onSticky()
		})
	}

	//
	// Dependency System
	//
	$.fn.spf_dependency = function () {
		return this.each(function () {
			var $this = $(this),
				ruleset = $.spf_deps.createRuleset(),
				depends = [],
				is_global = false

			$this.children('[data-controller]').each(function () {
				var $field = $(this),
					controllers = $field.data('controller').split('|'),
					conditions = $field.data('condition').split('|'),
					values = $field
						.data('value')
						.toString()
						.split('|'),
					rules = ruleset

				if ($field.data('depend-global')) {
					is_global = true
				}

				$.each(controllers, function (index, depend_id) {
					var value = values[index] || '',
						condition = conditions[index] || conditions[0]

					rules = rules.createRule(
						'[data-depend-id="' + depend_id + '"]',
						condition,
						value
					)

					rules.include($field)

					depends.push(depend_id)
				})
			})

			if (depends.length) {
				if (is_global) {
					$.spf_deps.enable(SP_PC.vars.$body, ruleset, depends)
				} else {
					$.spf_deps.enable($this, ruleset, depends)
				}
			}
		})
	}

	//
	// Field: accordion
	//
	$.fn.spf_field_accordion = function () {
		return this.each(function () {
			// Keep the accordion open by default.

			var $titles = $(this).find('.spf-accordion-title')

			//$titles.on('load', function() {
			//var $title_area = $(this),
			// var  $content_area = $titles.next();

			//$content_area.addClass('spf-accordion-open');
			// if (!$content_area.data('opened')) {
			// $content_area.spf_reload_script();
			//  $content_area.data('opened', true);
			// }
			//  $content_area.addClass('spf-accordion-open');
			//});

			$titles.on('click', function () {
				var $title = $(this),
					$icon = $title.find('.spf-accordion-icon'),
					$content = $title.next()

				if ($icon.hasClass('fa-angle-right')) {
					$icon.removeClass('fa-angle-right').addClass('fa-angle-down');
					$title.toggleClass('expanded')
				} else {
					$icon.removeClass('fa-angle-down').addClass('fa-angle-right');
					$title.toggleClass('expanded')
				}

				if (!$content.data('opened')) {
					$content.spf_reload_script()
					$content.data('opened', true)
				}

				$content.toggleClass('spf-accordion-open')
			})
			/*  var $sortable = $(this).find('.spf-accordion-items');
			 $sortable.sortable({
			   axis: 'y',
			   helper: 'original',
			   cursor: 'move',
			   placeholder: 'widget-placeholder',
			   update: function( event, ui ) {
				 $sortable.spf_customizer_refresh();
			   }
			 });
			 $sortable.find('.spf-accordion-content').spf_reload_script();*/
		})
	}

	//
	// Field: sortable
	//
	$.fn.spf_field_sortable = function () {
		return this.each(function () {
			var $sortable = $(this).find('.spf--sortable')
			// Post content sort only with handle
			if ($(this).hasClass('post_content_sorter')) {
				$sortable.sortable({
					axis: 'y',
					helper: 'original',
					handle: '.spf--sortable-helper',
					cursor: 'move',
					placeholder: 'widget-placeholder'
					// update: function( event, ui ) {
					//   $sortable.spf_customizer_refresh();
					// }
				})
			} else {
				$sortable.sortable({
					axis: 'y',
					helper: 'original',
					cursor: 'move',
					placeholder: 'widget-placeholder'
					// update: function( event, ui ) {
					//   $sortable.spf_customizer_refresh();
					// }
				})
			}

			$sortable.find('.spf--sortable-content').spf_reload_script()
		})
	}

	//
	// Field: code_editor
	//
	$.fn.spf_field_code_editor = function () {
		return this.each(function () {
			if (typeof CodeMirror !== 'function') {
				return
			}

			var $this = $(this),
				$textarea = $this.find('textarea'),
				$inited = $this.find('.CodeMirror'),
				data_editor = $textarea.data('editor')

			if ($inited.length) {
				$inited.remove()
			}

			var interval = setInterval(function () {
				if ($this.is(':visible')) {
					var code_editor = CodeMirror.fromTextArea($textarea[0], data_editor)

					// load code-mirror theme css.
					if (
						data_editor.theme !== 'default' &&
						SP_PC.vars.code_themes.indexOf(data_editor.theme) === -1
					) {
						var $cssLink = $('<link>')

						$('#spf-codemirror-css').after($cssLink)

						$cssLink.attr({
							rel: 'stylesheet',
							id: 'spf-codemirror-' + data_editor.theme + '-css',
							href:
								data_editor.cdnURL + '/theme/' + data_editor.theme + '.min.css',
							type: 'text/css',
							media: 'all'
						})

						SP_PC.vars.code_themes.push(data_editor.theme)
					}

					CodeMirror.modeURL = data_editor.cdnURL + '/mode/%N/%N.min.js'
					CodeMirror.autoLoadMode(code_editor, data_editor.mode)

					code_editor.on('change', function (editor, event) {
						$textarea.val(code_editor.getValue()).trigger('change')
					})

					clearInterval(interval)
				}
			})
		})
	}

	//
	// Field: tabbed
	//
	$.fn.spf_field_tabbed = function () {
		return this.each(function () {
			var $this = $(this),
				$links = $this.find('.spf-tabbed-nav a'),
				$sections = $this.find('.spf-tabbed-section');

			$links.on('click', function (e) {
				e.preventDefault();

				var $link = $(this),
					index = $link.index(),
					$section = $sections.eq(index);

				// Store the active tab index in a cookie
				SP_PC.helper.set_cookie('activeTabIndex', index);

				$link.addClass('spf-tabbed-active').siblings().removeClass('spf-tabbed-active');
				$section.spf_reload_script();
				$section.removeClass('hidden').siblings().addClass('hidden');
			});
			// Check if there's a stored active tab index in the cookie
			var activeTabIndex = SP_PC.helper.get_cookie('activeTabIndex');
			// Check if the cookie exists
			if (activeTabIndex !== null) {
				$links.eq(activeTabIndex).trigger('click');
			} else {
				$links.first().trigger('click');
			}

		});
	};

	//
	// Field: fieldset
	//
	$.fn.spf_field_fieldset = function () {
		return this.each(function () {
			$(this)
				.find('.spf-fieldset-content')
				.spf_reload_script()
		})
	}

	//
	// Field: group
	//
	$.fn.spf_field_group = function () {
		return this.each(function () {
			var $this = $(this),
				$fieldset = $this.children('.spf-fieldset'),
				$group = $fieldset.length ? $fieldset : $this,
				$wrapper = $group.children('.spf-cloneable-wrapper'),
				$hidden = $group.children('.spf-cloneable-hidden'),
				$max = $group.children('.spf-cloneable-max'),
				$min = $group.children('.spf-cloneable-min'),
				field_id = $wrapper.data('field-id'),
				unique_id = $wrapper.data('unique-id'),
				is_number = Boolean(Number($wrapper.data('title-number'))),
				max = parseInt($wrapper.data('max')),
				min = parseInt($wrapper.data('min'))

			// clear accordion arrows if multi-instance
			if ($wrapper.hasClass('ui-accordion')) {
				$wrapper.find('.ui-accordion-header-icon').remove()
			}

			var update_title_numbers = function ($selector) {
				$selector.find('.spf-cloneable-title-number').each(function (index) {
					$(this).html(
						$(this)
							.closest('.spf-cloneable-item')
							.index() +
						1 +
						'.'
					)
				})
			}

			// Hide the taxonomy relation field. -- Shamim
			var $pcp_group_parent = $this.parent()
			var pcp_hide_the_taxonomy_relation = function (count_tax) {
				if (count_tax < 2) {
					$($pcp_group_parent)
						.find('.pcp_relate_among_taxonomies')
						.hide()
				} else {
					$($pcp_group_parent)
						.find('.pcp_relate_among_taxonomies')
						.show()
				}
			}

			var count = $wrapper.children('.spf-cloneable-item').length
			pcp_hide_the_taxonomy_relation(count)

			$wrapper.accordion({
				header: '> .spf-cloneable-item > .spf-cloneable-title',
				collapsible: true,
				active: false,
				animate: false,
				heightStyle: 'content',
				icons: {
					header: 'spf-cloneable-header-icon',
					activeHeader: 'spf-cloneable-header-icon'
				},
				activate: function (event, ui) {
					var $panel = ui.newPanel
					var $header = ui.newHeader

					if ($panel.length && !$panel.data('opened')) {
						var $fields = $panel.children()
						var $first = $fields.first().find('select').first()
						var $title = $header.find('.spf-cloneable-value')
						var $title_value = $first.children("option").filter(":selected").text();
						if ($title_value == 'Select Taxonomy') {
							$title_value = 'Taxonomy';
						}
						$title.text($title_value);
						$first.on('change', function (event) {
							$title.text($first.val());
							if ($title.text($first.val())) {
								$title.text($first.children("option").filter(":selected").text());
							}
						});

						$panel.spf_reload_script()
						$panel.data('opened', true)
						$panel.data('retry', false)
					} else if ($panel.data('retry')) {
						$panel.spf_reload_script_retry()
						$panel.data('retry', false)
					}
				}
			})

			$wrapper.sortable({
				axis: 'y',
				handle: '.spf-cloneable-title,.spf-cloneable-sort',
				helper: 'original',
				cursor: 'move',
				placeholder: 'widget-placeholder',
				start: function (event, ui) {
					$wrapper.accordion({ active: false })
					$wrapper.sortable('refreshPositions')
					ui.item.children('.spf-cloneable-content').data('retry', true)
				},
				update: function (event, ui) {
					SP_PC.helper.name_nested_replace(
						$wrapper.children('.spf-cloneable-item'),
						field_id
					)

					//$wrapper.spf_customizer_refresh();

					if (is_number) {
						update_title_numbers($wrapper)
					}
				}
			})

			$group.children('.spf-cloneable-add').on('click', function (e) {
				e.preventDefault()

				var count = $wrapper.children('.spf-cloneable-item').length

				$min.hide()

				if (max && count + 1 > max) {
					$max.show()
					return
				}

				var new_field_id = unique_id + field_id + '[' + count + ']'

				var $cloned_item = $hidden.spf_clone(true)

				$cloned_item.removeClass('spf-cloneable-hidden')
				// $cloned_item.find(':input').each( function() {
				//   this.name = new_field_id + this.name.replace( ( this.name.startsWith('_nonce') ? '_nonce' : unique_id ), '');
				// });
				$cloned_item.find(':input[name!="_pseudo"]').each(function () {
					this.name =
						new_field_id +
						this.name.replace(
							this.name.startsWith('_nonce') ? '_nonce' : unique_id,
							''
						)
				})
				$cloned_item.find('.spf-data-wrapper').each(function () {
					$(this).attr('data-unique-id', new_field_id)
				})
				$wrapper.append($cloned_item)
				$wrapper.accordion('refresh')
				$wrapper.accordion({ active: count })
				// $wrapper.spf_customizer_refresh();
				// $wrapper.spf_customizer_listen({closest: true});

				if (is_number) {
					update_title_numbers($wrapper)
				}
				pcp_hide_the_taxonomy_relation(count + 1)
			})

			var event_clone = function (e) {
				e.preventDefault()

				var count = $wrapper.children('.spf-cloneable-item').length

				$min.hide()

				if (max && count + 1 > max) {
					$max.show()
					return
				}

				var $this = $(this),
					$parent = $this.parent().parent(),
					$cloned_helper = $parent
						.children('.spf-cloneable-helper')
						.spf_clone(true),
					$cloned_title = $parent.children('.spf-cloneable-title').spf_clone(),
					$cloned_content = $parent
						.children('.spf-cloneable-content')
						.spf_clone(),
					cloned_regex = new RegExp(
						'(' + SP_PC.helper.preg_quote(field_id) + ')\\[(\\d+)\\]',
						'g'
					)

				$cloned_content.find('.spf-data-wrapper').each(function () {
					var $this = $(this)
					$this.attr(
						'data-unique-id',
						$this
							.attr('data-unique-id')
							.replace(
								cloned_regex,
								field_id + '[' + ($parent.index() + 1) + ']'
							)
					)
				})

				var $cloned = $('<div class="spf-cloneable-item" />')
				$cloned.append($cloned_helper)
				$cloned.append($cloned_title)
				$cloned.append($cloned_content)

				$wrapper
					.children()
					.eq($parent.index())
					.after($cloned)

				SP_PC.helper.name_nested_replace(
					$wrapper.children('.spf-cloneable-item'),
					field_id
				)

				$wrapper.accordion('refresh')
				// $wrapper.spf_customizer_refresh();
				// $wrapper.spf_customizer_listen({closest: true});

				if (is_number) {
					update_title_numbers($wrapper)
				}
				pcp_hide_the_taxonomy_relation(count + 1)
			}

			$wrapper
				.children('.spf-cloneable-item')
				.children('.spf-cloneable-helper')
				.on('click', '.spf-cloneable-clone', event_clone)
			$group
				.children('.spf-cloneable-hidden')
				.children('.spf-cloneable-helper')
				.on('click', '.spf-cloneable-clone', event_clone)
			var event_remove = function (e) {
				e.preventDefault()

				var count = $wrapper.children('.spf-cloneable-item').length

				$max.hide()
				$min.hide()

				if (min && count - 1 < min) {
					$min.show()
					return
				}

				$(this)
					.closest('.spf-cloneable-item')
					.remove()

				SP_PC.helper.name_nested_replace(
					$wrapper.children('.spf-cloneable-item'),
					field_id
				)

				//$wrapper.spf_customizer_refresh();

				if (is_number) {
					update_title_numbers($wrapper)
				}
				pcp_hide_the_taxonomy_relation(count - 1)
			}
			$wrapper
				.children('.spf-cloneable-item')
				.children('.spf-cloneable-helper')
				.on('click', '.spf-cloneable-remove', event_remove)
			$group
				.children('.spf-cloneable-hidden')
				.children('.spf-cloneable-helper')
				.on('click', '.spf-cloneable-remove', event_remove)
		})
	}

	//
	// Field: icon
	//
	$.fn.spf_field_icon = function () {
		return this.each(function () {
			var $this = $(this)

			$this.on('click', '.spf-icon-add', function (e) {
				e.preventDefault()

				var $button = $(this)
				var $modal = $('#spf-modal-icon')

				$modal.show()

				SP_PC.vars.$icon_target = $this

				if (!SP_PC.vars.icon_modal_loaded) {
					$modal.find('.spf-modal-loading').show()

					// window.wp.ajax.post( 'spf-get-icons', { nonce: $button.data('nonce') } ).done( function( response ) {
					window.wp.ajax
						.post('spf-get-icons', {
							nonce: $button.data('nonce')
						})
						.done(function (response) {
							$modal.find('.spf-modal-loading').hide()

							SP_PC.vars.icon_modal_loaded = true

							var $load = $modal.find('.spf-modal-load').html(response.content)

							$load.on('click', 'a', function (e) {
								e.preventDefault()

								var icon = $(this).data('spf-icon')

								SP_PC.vars.$icon_target
									.find('i')
									.removeAttr('class')
									.addClass(icon)
								SP_PC.vars.$icon_target
									.find('input')
									.val(icon)
									.trigger('change')
								SP_PC.vars.$icon_target
									.find('.spf-icon-preview')
									.removeClass('hidden')
								SP_PC.vars.$icon_target
									.find('.spf-icon-remove')
									.removeClass('hidden')

								$modal.hide()
							})

							$modal.on('change keyup', '.spf-icon-search', function () {
								var value = $(this).val(),
									$icons = $load.find('a')

								$icons.each(function () {
									var $elem = $(this)

									if (
										$elem.data('spf-icon').search(new RegExp(value, 'i')) < 0
									) {
										$elem.hide()
									} else {
										$elem.show()
									}
								})
							})

							$modal.on(
								'click',
								'.spf-modal-close, .spf-modal-overlay',
								function () {
									$modal.hide()
								}
							)
						})
						.fail(function (response) {
							$modal.find('.spf-modal-loading').hide()
							$modal.find('.spf-modal-load').html(response.error)
							$modal.on('click', function () {
								$modal.hide()
							})
						})
				}
			})

			$this.on('click', '.spf-icon-remove', function (e) {
				e.preventDefault()
				$this.find('.spf-icon-preview').addClass('hidden')
				$this
					.find('input')
					.val('')
					.trigger('change')
				$(this).addClass('hidden')
			})
		})
	}

	//
	// Field: slider
	//
	$.fn.spf_field_slider = function () {
		return this.each(function () {
			var $this = $(this),
				$input = $this.find('input'),
				$slider = $this.find('.spf-slider-ui'),
				data = $input.data(),
				value = $input.val() || 0

			if ($slider.hasClass('ui-slider')) {
				$slider.empty()
			}

			$slider.slider({
				range: 'min',
				value: value,
				min: data.min,
				max: data.max,
				step: data.step,
				slide: function (e, o) {
					$input.val(o.value).trigger('change')
				}
			})

			$input.keyup(function () {
				$slider.slider('value', $input.val())
			})
		})
	}

	//
	// Field: c
	//
	$.fn.spf_field_sorter = function () {
		return this.each(function () {
			var $this = $(this),
				$enabled = $this.find('.spf-enabled'),
				$has_disabled = $this.find('.spf-disabled'),
				$disabled = $has_disabled.length ? $has_disabled : false

			$enabled.sortable({
				connectWith: $disabled,

				placeholder: 'ui-sortable-placeholder',

				update: function (event, ui) {
					var $el = ui.item.find('input')

					if (ui.item.parent().hasClass('spf-enabled')) {
						$el.attr('name', $el.attr('name').replace('disabled', 'enabled'))
					} else {
						$el.attr('name', $el.attr('name').replace('enabled', 'disabled'))
					}

					// $this.spf_customizer_refresh();
				}
			})

			if ($disabled) {
				$disabled.sortable({
					connectWith: $enabled,
					placeholder: 'ui-sortable-placeholder'
					// update: function( event, ui ) {
					//   $this.spf_customizer_refresh();
					// }
				})
			}
		})
	}

	//
	// Field: spinner
	//
	$.fn.spf_field_spinner = function () {
		return this.each(function () {
			var $this = $(this),
				$input = $this.find('input'),
				$inited = $this.find('.ui-spinner-button'),
				$unit = $input.data('unit')

			if ($inited.length) {
				$inited.remove()
			}

			$input.spinner({
				max: $input.data('max') || 100,
				min: $input.data('min') || 0,
				step: $input.data('step') || 1,
				create: function (event, ui) {
					if ($unit.length) {
						$this
							.find('.ui-spinner-up')
							.after(
								'<span class="ui-button-text-only spf--unit">' +
								$unit +
								'</span>'
							)
					}
				},
				spin: function (event, ui) {
					$input.val(ui.value).trigger('change')
				}
			})
		})
	}

	//
	// Field: switcher
	//
	$.fn.spf_field_switcher = function () {
		return this.each(function () {
			var $switcher = $(this).find('.spf--switcher')

			$switcher.on('click', function () {
				var value = 0
				var $input = $switcher.find('input')

				if ($switcher.hasClass('spf--active')) {
					$switcher.removeClass('spf--active')
				} else {
					value = 1
					$switcher.addClass('spf--active')
				}

				$input.val(value).trigger('change')
			})
		})
	}

	//
	// Field: typography
	//
	$.fn.spf_field_typography = function () {
		return this.each(function () {
			var base = this
			var $this = $(this)
			var loaded_fonts = []
			var webfonts = spf_typography_json.webfonts
			var googlestyles = spf_typography_json.googlestyles
			var defaultstyles = spf_typography_json.defaultstyles

			//
			//
			// Sanitize google font subset
			base.sanitize_subset = function (subset) {
				subset = subset.replace('-ext', ' Extended')
				subset = subset.charAt(0).toUpperCase() + subset.slice(1)
				return subset
			}

			//
			//
			// Sanitize google font styles (weight and style)
			base.sanitize_style = function (style) {
				return googlestyles[style] ? googlestyles[style] : style
			}

			//
			//
			// Load google font
			base.load_google_font = function (font_family, weight, style) {
				if (font_family && typeof WebFont === 'object') {
					weight = weight ? weight.replace('normal', '') : ''
					style = style ? style.replace('normal', '') : ''

					if (weight || style) {
						font_family = font_family + ':' + weight + style
					}

					if (loaded_fonts.indexOf(font_family) === -1) {
						WebFont.load({ google: { families: [font_family] } })
					}

					loaded_fonts.push(font_family)
				}
			}

			//
			//
			// Append select options
			base.append_select_options = function (
				$select,
				options,
				condition,
				type,
				is_multi
			) {
				$select
					.find('option')
					.not(':first')
					.remove()

				var opts = ''

				$.each(options, function (key, value) {
					var selected
					var name = value

					// is_multi
					if (is_multi) {
						selected =
							condition && condition.indexOf(value) !== -1 ? ' selected' : ''
					} else {
						selected = condition && condition === value ? ' selected' : ''
					}

					if (type === 'subset') {
						name = base.sanitize_subset(value)
					} else if (type === 'style') {
						name = base.sanitize_style(value)
					}

					opts +=
						'<option value="' +
						value +
						'"' +
						selected +
						'>' +
						name +
						'</option>'
				})

				$select
					.append(opts)
					.trigger('spf.change')
					.trigger('chosen:updated')
			}

			base.init = function () {
				//
				//
				// Constants
				var selected_styles = []
				var $typography = $this.find('.spf--typography')
				var $type = $this.find('.spf--type')
				var $styles = $this.find('.spf--block-font-style')
				var unit = $typography.data('unit')
				var exclude_fonts = $typography.data('exclude')
					? $typography.data('exclude').split(',')
					: []

				//
				//
				// Chosen init
				if ($this.find('.spf--chosen').length) {
					var $chosen_selects = $this.find('select')

					$chosen_selects.each(function () {
						var $chosen_select = $(this),
							$chosen_inited = $chosen_select.parent().find('.chosen-container')

						if ($chosen_inited.length) {
							$chosen_inited.remove()
						}

						$chosen_select.chosen({
							allow_single_deselect: true,
							disable_search_threshold: 15,
							width: '100%'
						})
					})
				}

				//
				//
				// Font family select
				var $font_family_select = $this.find('.spf--font-family')
				var first_font_family = $font_family_select.val()

				// Clear default font family select options
				$font_family_select
					.find('option')
					.not(':first-child')
					.remove()

				var opts = ''

				$.each(webfonts, function (type, group) {
					// Check for exclude fonts
					if (exclude_fonts && exclude_fonts.indexOf(type) !== -1) {
						return
					}

					opts += '<optgroup label="' + group.label + '">'

					$.each(group.fonts, function (key, value) {
						// use key if value is object
						value = typeof value === 'object' ? key : value
						var selected = value === first_font_family ? ' selected' : ''
						opts +=
							'<option value="' +
							value +
							'" data-type="' +
							type +
							'"' +
							selected +
							'>' +
							value +
							'</option>'
					})

					opts += '</optgroup>'
				})

				// Append google font select options
				$font_family_select.append(opts).trigger('chosen:updated')

				//
				//
				// Font style select
				var $font_style_block = $this.find('.spf--block-font-style')

				if ($font_style_block.length) {
					var $font_style_select = $this.find('.spf--font-style-select')
					var first_style_value = $font_style_select.val()
						? $font_style_select.val().replace(/normal/g, '')
						: ''

					//
					// Font Style on on change listener
					$font_style_select.on('change spf.change', function (event) {
						var style_value = $font_style_select.val()

						// set a default value
						if (
							!style_value &&
							selected_styles &&
							selected_styles.indexOf('normal') === -1
						) {
							style_value = selected_styles[0]
						}

						// set font weight, for eg. replacing 800italic to 800
						var font_normal =
							style_value &&
								style_value !== 'italic' &&
								style_value === 'normal'
								? 'normal'
								: ''
						var font_weight =
							style_value &&
								style_value !== 'italic' &&
								style_value !== 'normal'
								? style_value.replace('italic', '')
								: font_normal
						var font_style =
							style_value && style_value.substr(-6) === 'italic' ? 'italic' : ''

						$this.find('.spf--font-weight').val(font_weight)
						$this.find('.spf--font-style').val(font_style)
					})

					//
					//
					// Extra font style select
					var $extra_font_style_block = $this.find('.spf--block-extra-styles')

					if ($extra_font_style_block.length) {
						var $extra_font_style_select = $this.find('.spf--extra-styles')
						var first_extra_style_value = $extra_font_style_select.val()
					}
				}

				//
				//
				// Subsets select
				var $subset_block = $this.find('.spf--block-subset')
				if ($subset_block.length) {
					var $subset_select = $this.find('.spf--subset')
					var first_subset_select_value = $subset_select.val()
					var subset_multi_select = $subset_select.data('multiple') || false
				}

				//
				//
				// Backup font family
				var $backup_font_family_block = $this.find(
					'.spf--block-backup-font-family'
				)

				//
				//
				// Font Family on Change Listener
				$font_family_select
					.on('change spf.change', function (event) {
						// Hide subsets on change
						if ($subset_block.length) {
							$subset_block.addClass('hidden')
						}

						// Hide extra font style on change
						if ($extra_font_style_block.length) {
							$extra_font_style_block.addClass('hidden')
						}

						// Hide backup font family on change
						if ($backup_font_family_block.length) {
							$backup_font_family_block.addClass('hidden')
						}

						var $selected = $font_family_select.find(':selected')
						var value = $selected.val()
						var type = $selected.data('type')

						if (type && value) {
							// Show backup fonts if font type google or custom
							if (
								(type === 'google' || type === 'custom') &&
								$backup_font_family_block.length
							) {
								$backup_font_family_block.removeClass('hidden')
							}

							// Appending font style select options
							if ($font_style_block.length) {
								// set styles for multi and normal style selectors
								var styles = defaultstyles

								// Custom or gogle font styles
								if (type === 'google' && webfonts[type].fonts[value][0]) {
									styles = webfonts[type].fonts[value][0]
								} else if (type === 'custom' && webfonts[type].fonts[value]) {
									styles = webfonts[type].fonts[value]
								}

								selected_styles = styles

								// Set selected style value for avoid load errors
								var set_auto_style =
									styles.indexOf('normal') !== -1 ? 'normal' : styles[0]
								var set_style_value =
									first_style_value && styles.indexOf(first_style_value) !== -1
										? first_style_value
										: set_auto_style

								// Append style select options
								base.append_select_options(
									$font_style_select,
									styles,
									set_style_value,
									'style'
								)

								// Clear first value
								first_style_value = false

								// Show style select after appended
								$font_style_block.removeClass('hidden')

								// Appending extra font style select options
								if (
									type === 'google' &&
									$extra_font_style_block.length &&
									styles.length > 1
								) {
									// Append extra-style select options
									base.append_select_options(
										$extra_font_style_select,
										styles,
										first_extra_style_value,
										'style',
										true
									)

									// Clear first value
									first_extra_style_value = false

									// Show style select after appended
									$extra_font_style_block.removeClass('hidden')
								}
							}

							// Appending google fonts subsets select options
							if (
								type === 'google' &&
								$subset_block.length &&
								webfonts[type].fonts[value][1]
							) {
								var subsets = webfonts[type].fonts[value][1]
								var set_auto_subset =
									subsets.length < 2 && subsets[0] !== 'latin' ? subsets[0] : ''
								var set_subset_value =
									first_subset_select_value &&
										subsets.indexOf(first_subset_select_value) !== -1
										? first_subset_select_value
										: set_auto_subset

								// check for multiple subset select
								set_subset_value =
									subset_multi_select && first_subset_select_value
										? first_subset_select_value
										: set_subset_value

								base.append_select_options(
									$subset_select,
									subsets,
									set_subset_value,
									'subset',
									subset_multi_select
								)

								first_subset_select_value = false

								$subset_block.removeClass('hidden')
							}
						} else {
							// Clear Styles
							$styles.find(':input').val('')

							// Clear subsets options if type and value empty
							if ($subset_block.length) {
								$subset_select
									.find('option')
									.not(':first-child')
									.remove()
								$subset_select.trigger('chosen:updated')
							}

							// Clear font styles options if type and value empty
							if ($font_style_block.length) {
								$font_style_select
									.find('option')
									.not(':first-child')
									.remove()
								$font_style_select.trigger('chosen:updated')
							}
						}

						// Update font type input value
						$type.val(type)
					})
					.trigger('spf.change')

				//
				//
				// Preview
				var $preview_block = $this.find('.spf--block-preview')

				if ($preview_block.length) {
					var $preview = $this.find('.spf--preview')

					// Set preview styles on change
					$this.on(
						'change',
						SP_PC.helper.debounce(function (event) {
							$preview_block.removeClass('hidden')

							var font_family = $font_family_select.val(),
								font_weight = $this.find('.spf--font-weight').val(),
								font_style = $this.find('.spf--font-style').val(),
								font_size = $this.find('.spf--font-size').val(),
								font_variant = $this.find('.spf--font-variant').val(),
								line_height = $this.find('.spf--line-height').val(),
								text_align = $this.find('.spf--text-align').val(),
								text_transform = $this.find('.spf--text-transform').val(),
								text_decoration = $this.find('.spf--text-decoration').val(),
								text_color = $this.find('.spf--color').val(),
								word_spacing = $this.find('.spf--word-spacing').val(),
								letter_spacing = $this.find('.spf--letter-spacing').val(),
								custom_style = $this.find('.spf--custom-style').val(),
								type = $this.find('.spf--type').val()

							if (type === 'google') {
								base.load_google_font(font_family, font_weight, font_style)
							}

							var properties = {}

							if (font_family) {
								properties.fontFamily = font_family
							}
							if (font_weight) {
								properties.fontWeight = font_weight
							}
							if (font_style) {
								properties.fontStyle = font_style
							}
							if (font_variant) {
								properties.fontVariant = font_variant
							}
							if (font_size) {
								properties.fontSize = font_size + unit
							}
							if (line_height) {
								properties.lineHeight = line_height + unit
							}
							if (letter_spacing) {
								properties.letterSpacing = letter_spacing + unit
							}
							if (word_spacing) {
								properties.wordSpacing = word_spacing + unit
							}
							if (text_align) {
								properties.textAlign = text_align
							}
							if (text_transform) {
								properties.textTransform = text_transform
							}
							if (text_decoration) {
								properties.textDecoration = text_decoration
							}
							if (text_color) {
								properties.color = text_color
							}

							$preview.removeAttr('style')

							// Customs style attribute
							if (custom_style) {
								$preview.attr('style', custom_style)
							}

							$preview.css(properties)
						}, 100)
					)

					// Preview black and white backgrounds trigger
					$preview_block.on('click', function () {
						$preview.toggleClass('spf--black-background')

						var $toggle = $preview_block.find('.spf--toggle')

						if ($toggle.hasClass('fa-toggle-off')) {
							$toggle.removeClass('fa-toggle-off').addClass('fa-toggle-on')
						} else {
							$toggle.removeClass('fa-toggle-on').addClass('fa-toggle-off')
						}
					})

					if (!$preview_block.hasClass('hidden')) {
						$this.trigger('change')
					}
				}
			}

			base.init()
		})
	}

	//
	// Confirm
	//
	$.fn.spf_confirm = function () {
		return this.each(function () {
			$(this).on('click', function (e) {
				var confirm_text =
					$(this).data('confirm') || window.spf_vars.i18n.confirm
				var confirm_answer = confirm(confirm_text)
				SP_PC.vars.is_confirm = true

				if (!confirm_answer) {
					e.preventDefault()
					SP_PC.vars.is_confirm = false

					return false
				}
			})
		})
	}

	$.fn.serializeObject = function () {
		var obj = {}

		$.each(this.serializeArray(), function (i, o) {
			var n = o.name,
				v = o.value

			obj[n] =
				obj[n] === undefined
					? v
					: $.isArray(obj[n])
						? obj[n].concat(v)
						: [obj[n], v]
		})

		return obj
	}

	//
	// Options Save
	//
	$.fn.spf_save = function () {
		return this.each(function () {
			var $this = $(this),
				$buttons = $('.spf-save'),
				$panel = $('.spf-options'),
				flooding = false,
				timeout

			$this.on('click', function (e) {
				if (!flooding) {
					var $text = $this.data('save'),
						$value = $this.val()

					$buttons.attr('value', $text)

					if ($this.hasClass('spf-save-ajax')) {
						e.preventDefault()

						$panel.addClass('spf-saving')
						$buttons.prop('disabled', true)

						window.wp.ajax
							.post('spf_' + $panel.data('unique') + '_ajax_save', {
								data: $('#spf-form').serializeJSONSP_PC()
							})
							.done(function (response) {
								clearTimeout(timeout)

								var $result_success = $('.spf-form-success')

								$result_success
									.empty()
									.append(response.notice)
									.slideDown('fast', function () {
										timeout = setTimeout(function () {
											$result_success.slideUp('fast')
										}, 2000)
									})

								// clear errors
								$('.spf-error').remove()

								var $append_errors = $('.spf-form-error')

								$append_errors.empty().hide()

								if (Object.keys(response.errors).length) {
									var error_icon = '<i class="spf-label-error spf-error">!</i>'

									$.each(response.errors, function (key, error_message) {
										var $field = $('[data-depend-id="' + key + '"]'),
											$link = $(
												'#spf-tab-link-' +
												($field.closest('.spf-section').index() + 1)
											),
											$tab = $link.closest('.spf-tab-depth-0')

										$field
											.closest('.spf-fieldset')
											.append(
												'<p class="spf-text-error spf-error">' +
												error_message +
												'</p>'
											)

										if (!$link.find('.spf-error').length) {
											$link.append(error_icon)
										}

										if (!$tab.find('.spf-arrow .spf-error').length) {
											$tab.find('.spf-arrow').append(error_icon)
										}

										console.log(error_message)

										$append_errors.append(
											'<div>' + error_icon + ' ' + error_message + '</div>'
										)
									})

									$append_errors.show()
								}

								$panel.removeClass('spf-saving')
								$buttons.prop('disabled', false).attr('value', $value)
								flooding = false
							})
							.fail(function (response) {
								alert(response.error)
							})
					}
				}

				flooding = true
			})
		})
	}

	//
	// WP Color Picker
	//
	if (typeof Color === 'function') {
		Color.fn.toString = function () {
			if (this._alpha < 1) {
				return this.toCSS('rgba', this._alpha).replace(/\s+/g, '')
			}

			var hex = parseInt(this._color, 10).toString(16)

			if (this.error) {
				return ''
			}

			if (hex.length < 6) {
				for (var i = 6 - hex.length - 1; i >= 0; i--) {
					hex = '0' + hex
				}
			}

			return '#' + hex
		}
	}

	SP_PC.funcs.parse_color = function (color) {
		var value = color.replace(/\s+/g, ''),
			trans =
				value.indexOf('rgba') !== -1
					? parseFloat(value.replace(/^.*,(.+)\)/, '$1') * 100)
					: 100,
			rgba = trans < 100 ? true : false

		return { value: value, transparent: trans, rgba: rgba }
	}

	$.fn.spf_color = function () {
		return this.each(function () {
			var $input = $(this),
				picker_color = SP_PC.funcs.parse_color($input.val()),
				palette_color = window.spf_vars.color_palette.length
					? window.spf_vars.color_palette
					: true,
				$container

			// Destroy and Re-init.
			if ($input.hasClass('wp-color-picker')) {
				$input
					.closest('.wp-picker-container')
					.after($input)
					.remove()
			}

			$input.wpColorPicker({
				palettes: palette_color,
				change: function (event, ui) {
					var ui_color_value = ui.color.toString()

					$container.removeClass('spf--transparent-active')
					$container
						.find('.spf--transparent-offset')
						.css('background-color', ui_color_value)
					$input.val(ui_color_value).trigger('change')
				},
				create: function () {
					$container = $input.closest('.wp-picker-container')

					var a8cIris = $input.data('a8cIris'),
						$transparent_wrap = $(
							'<div class="spf--transparent-wrap">' +
							'<div class="spf--transparent-slider"></div>' +
							'<div class="spf--transparent-offset"></div>' +
							'<div class="spf--transparent-text"></div>' +
							'<div class="spf--transparent-button">transparent <i class="fa fa-toggle-off"></i></div>' +
							'</div>'
						).appendTo($container.find('.wp-picker-holder')),
						$transparent_slider = $transparent_wrap.find(
							'.spf--transparent-slider'
						),
						$transparent_text = $transparent_wrap.find(
							'.spf--transparent-text'
						),
						$transparent_offset = $transparent_wrap.find(
							'.spf--transparent-offset'
						),
						$transparent_button = $transparent_wrap.find(
							'.spf--transparent-button'
						)

					if ($input.val() === 'transparent') {
						$container.addClass('spf--transparent-active')
					}

					$transparent_button.on('click', function () {
						if ($input.val() !== 'transparent') {
							$input
								.val('transparent')
								.trigger('change')
								.removeClass('iris-error')
							$container.addClass('spf--transparent-active')
						} else {
							$input.val(a8cIris._color.toString()).trigger('change')
							$container.removeClass('spf--transparent-active')
						}
					})

					$transparent_slider.slider({
						value: picker_color.transparent,
						step: 1,
						min: 0,
						max: 100,
						slide: function (event, ui) {
							var slide_value = parseFloat(ui.value / 100)
							a8cIris._color._alpha = slide_value
							$input.wpColorPicker('color', a8cIris._color.toString())
							$transparent_text.text(
								slide_value === 1 || slide_value === 0 ? '' : slide_value
							)
						},
						create: function () {
							var slide_value = parseFloat(picker_color.transparent / 100),
								text_value = slide_value < 1 ? slide_value : ''

							$transparent_text.text(text_value)
							$transparent_offset.css('background-color', picker_color.value)

							$container.on('click', '.wp-picker-clear', function () {
								a8cIris._color._alpha = 1
								$transparent_text.text('')
								$transparent_slider.slider('option', 'value', 100)
								$container.removeClass('spf--transparent-active')
								$input.trigger('change')
							})

							$container.on('click', '.wp-picker-default', function () {
								var default_color = SP_PC.funcs.parse_color(
									$input.data('default-color')
								),
									default_value = parseFloat(default_color.transparent / 100),
									default_text = default_value < 1 ? default_value : ''

								a8cIris._color._alpha = default_value
								$transparent_text.text(default_text)
								$transparent_slider.slider(
									'option',
									'value',
									default_color.transparent
								)
							})
						}
					})
				}
			})
		})
	}

	//
	// Number (only allow numeric inputs)
	//
	$.fn.spf_number = function () {
		return this.each(function () {
			$(this).on('keypress', function (e) {
				if (
					e.keyCode !== 0 &&
					e.keyCode !== 8 &&
					e.keyCode !== 45 &&
					e.keyCode !== 46 &&
					(e.keyCode < 48 || e.keyCode > 57)
				) {
					return false
				}
			})
		})
	}

	//
	// ChosenJS
	//
	$.fn.spf_chosen = function () {
		return this.each(function () {
			var $this = $(this),
				$inited = $this.parent().find('.chosen-container'),
				is_sortable = $this.hasClass('spf-chosen-sortable') || false,
				is_ajax = $this.hasClass('spf-chosen-ajax') || false,
				is_multiple = $this.attr('multiple') || false,
				set_width = is_multiple ? 'auto' : 'auto',
				set_options = $.extend(
					{
						allow_single_deselect: true,
						disable_search_threshold: 10,
						width: set_width,
						no_results_text: window.spf_vars.i18n.no_results_text
					},
					$this.data('chosen-settings')
				)

			if ($inited.length) {
				$inited.remove()
			}

			// Chosen ajax
			if (is_ajax) {
				var set_ajax_options = $.extend(
					{
						data: {
							type: 'post',
							nonce: ''
						},
						allow_single_deselect: true,
						disable_search_threshold: -1,
						width: '100%',
						min_length: 3,
						type_delay: 500,
						typing_text: window.spf_vars.i18n.typing_text,
						searching_text: window.spf_vars.i18n.searching_text,
						no_results_text: window.spf_vars.i18n.no_results_text
					},
					$this.data('chosen-settings')
				)

				$this.SP_PCAjaxChosen(set_ajax_options)
			} else {
				$this.chosen(set_options)
			}

			// Chosen keep options order
			if (is_multiple) {
				var $hidden_select = $this.parent().find('.spf-hidden-select')
				var $hidden_value = $hidden_select.val() || []

				$this.on('change', function (obj, result) {
					if (result && result.selected) {
						$hidden_select.append(
							'<option value="' +
							result.selected +
							'" selected="selected">' +
							result.selected +
							'</option>'
						)
					} else if (result && result.deselected) {
						$hidden_select
							.find('option[value="' + result.deselected + '"]')
							.remove()
					}

					// Force customize refresh
					if (
						$hidden_select.children().length === 0 &&
						window.wp.customize !== undefined
					) {
						window.wp.customize
							.control($hidden_select.data('customize-setting-link'))
							.setting.set('')
					}

					$hidden_select.trigger('change')
				})

				// Chosen order abstract
				$this.SP_PCChosenOrder($hidden_value, true)
			}

			// Chosen sortable
			if (is_sortable) {
				var $chosen_container = $this.parent().find('.chosen-container')
				var $chosen_choices = $chosen_container.find('.chosen-choices')

				$chosen_choices.bind('mousedown', function (event) {
					if ($(event.target).is('span')) {
						event.stopPropagation()
					}
				})

				$chosen_choices.sortable({
					items: 'li:not(.search-field)',
					helper: 'orginal',
					cursor: 'move',
					placeholder: 'search-choice-placeholder',
					start: function (e, ui) {
						ui.placeholder.width(ui.item.innerWidth())
						ui.placeholder.height(ui.item.innerHeight())
					},
					update: function (e, ui) {
						var select_options = ''
						var chosen_object = $this.data('chosen')
						var $prev_select = $this.parent().find('.spf-hidden-select')

						$chosen_choices.find('.search-choice-close').each(function () {
							var option_array_index = $(this).data('option-array-index')
							$.each(chosen_object.results_data, function (index, data) {
								if (data.array_index === option_array_index) {
									select_options +=
										'<option value="' +
										data.value +
										'" selected>' +
										data.value +
										'</option>'
								}
							})
						})

						$prev_select.children().remove()
						$prev_select.append(select_options)
						$prev_select.trigger('change')
					}
				})
			}
		})
	}

	//
	// Helper Checkbox Checker
	//
	$.fn.spf_checkbox = function () {
		return this.each(function () {
			var $this = $(this),
				$input = $this.find('.spf--input'),
				$checkbox = $this.find('.spf--checkbox')

			$checkbox.on('click', function () {
				$input.val(Number($checkbox.prop('checked'))).trigger('change')
			})
		})
	}

	//
	// Siblings
	//
	$.fn.spf_siblings = function () {
		return this.each(function () {
			var $this = $(this),
				$siblings = $this.find('.spf--sibling'),
				multiple = $this.data('multiple') || false

			$siblings.on('click', function () {
				var $sibling = $(this)

				if (multiple) {
					if ($sibling.hasClass('spf--active')) {
						$sibling.removeClass('spf--active')
						$sibling
							.find('input')
							.prop('checked', false)
							.trigger('change')
					} else {
						$sibling.addClass('spf--active')
						$sibling
							.find('input')
							.prop('checked', true)
							.trigger('change')
					}
				} else {
					$this.find('input').prop('checked', false)
					$sibling
						.find('input')
						.prop('checked', true)
						.trigger('change')
					$sibling
						.addClass('spf--active')
						.siblings()
						.removeClass('spf--active')
				}
			})
		})
	}

	//
	// Help Tooltip
	//
	// $.fn.spf_help = function () {
	// 	return this.each(function () {
	// 		var $this = $(this),
	// 			$tooltip,
	// 			offset_left

	// 		$this.on({
	// 			mouseenter: function () {
	// 				$tooltip = $('<div class="spf-tooltip"></div>')
	// 					.html($this.find('.spf-help-text').html())
	// 					.appendTo('body')
	// 				offset_left = SP_PC.vars.is_rtl
	// 					? $this.offset().left - $tooltip.outerWidth()
	// 					: $this.offset().left + 24

	// 				$tooltip.css({
	// 					top: $this.offset().top - ($tooltip.outerHeight() / 2 - 14),
	// 					left: offset_left
	// 				})
	// 			},
	// 			mouseleave: function () {
	// 				if ($tooltip !== undefined) {
	// 					$tooltip.remove()
	// 				}
	// 			}
	// 		})
	// 	})
	// }
	//
	// Help Tooltip
	//
	$.fn.spf_help = function () {
		return this.each(function () {
			var $this = $(this);
			var $tooltip;
			var $class = '';
			$this.on({
				mouseenter: function () {
					// this class add with the support tooltip.
					if ($this.find('.spf-support').length > 0) {
						$class = 'support-tooltip';
					}
					$tooltip = $('<div class="spf-tooltip ' + $class + '"></div>')
						.html($this.find('.spf-help-text').html())
						.appendTo('body');

					var offset_left = SP_PC.vars.is_rtl
						? $this.offset().left - $tooltip.outerWidth()
						: $this.offset().left + 24;
					var $top = $this.offset().top - ($tooltip.outerHeight() / 2 - 14);
					// this block used for support tooltip.
					if ($this.find('.spf-support').length > 0) {
						$top = $this.offset().top + 42;
						offset_left = $this.offset().left - 231;
					}
					$tooltip.css({
						top: $top,
						left: offset_left,
					});
				},
				mouseleave: function () {
					if ($tooltip !== undefined) {
						// Check if the cursor is still over the tooltip
						if (!$tooltip.is(':hover')) {
							$tooltip.remove();
						}
					}
				},
			});
			// Event delegation to handle tooltip removal when the cursor leaves the tooltip itself.
			$('body').on('mouseleave', '.spf-tooltip', function () {
				if ($tooltip !== undefined) {
					$tooltip.remove();
				}
			});
		});
	}

	//
	// Window on resize
	//
	SP_PC.vars.$window
		.on(
			'resize spf.resize',
			SP_PC.helper.debounce(function (event) {
				var window_width =
					navigator.userAgent.indexOf('AppleWebKit/') > -1
						? SP_PC.vars.$window.width()
						: window.innerWidth

				if (window_width <= 782 && !SP_PC.vars.onloaded) {
					$('.spf-section').spf_reload_script()
					SP_PC.vars.onloaded = true
				}
			}, 200)
		)
		.trigger('spf.resize')

	//
	// Widgets Framework
	//
	$.fn.spf_widgets = function () {
		if (this.length) {
			$(document).on('widget-added widget-updated', function (event, $widget) {
				$widget.find('.spf-fields').spf_reload_script()
			})

			$('.widgets-sortables, .control-section-sidebar').on(
				'sortstop',
				function (event, ui) {
					ui.item.find('.spf-fields').spf_reload_script_retry()
				}
			)

			$(document).on('click', '.widget-top', function (event) {
				$(this)
					.parent()
					.find('.spf-fields')
					.spf_reload_script()
			})
		}
	}

	//
	// Retry Plugins
	//
	$.fn.spf_reload_script_retry = function () {
		return this.each(function () {
			var $this = $(this)
		})
	}

	//
	// Reload Plugins
	//
	$.fn.spf_reload_script = function (options) {
		var settings = $.extend(
			{
				dependency: true
			},
			options
		)

		return this.each(function () {
			var $this = $(this)
			// Avoid for conflicts
			if (!$this.data('inited')) {
				// Field plugins
				$this.children('.spf-field-accordion').spf_field_accordion()
				$this.children('.spf-field-code_editor').spf_field_code_editor()
				$this.children('.spf-field-fieldset').spf_field_fieldset()
				$this.children('.spf-field-group').spf_field_group()
				$this.children('.spf-field-icon').spf_field_icon()
				$this.children('.spf-field-slider').spf_field_slider()
				$this.children('.spf-field-sortable').spf_field_sortable()
				$this.children('.spf-field-sorter').spf_field_sorter()
				$this.children('.spf-field-spinner').spf_field_spinner()
				$this.children('.spf-field-switcher').spf_field_switcher()
				$this.children('.spf-field-typography').spf_field_typography()
				$this.children('.spf-field-tabbed').spf_field_tabbed();


				// Field colors
				$this
					.children('.spf-field-border')
					.find('.spf-color')
					.spf_color()
				$this
					.children('.spf-field-background')
					.find('.spf-color')
					.spf_color()
				$this
					.children('.spf-field-color')
					.find('.spf-color')
					.spf_color()
				$this
					.children('.spf-field-color_group')
					.find('.spf-color')
					.spf_color()
				$this
					.children('.spf-field-link_color')
					.find('.spf-color')
					.spf_color()
				$this
					.children('.spf-field-typography')
					.find('.spf-color')
					.spf_color()

				// Field chosenjs
				$this
					.children('.spf-field-select')
					.find('.spf-chosen')
					.spf_chosen()

				// Field Checkbox
				$this
					.children('.spf-field-checkbox')
					.find('.spf-checkbox')
					.spf_checkbox()

				// Field Siblings
				$this
					.children('.spf-field-button_set')
					.find('.spf-siblings')
					.spf_siblings()
				$this
					.children('.spf-field-layout_preset')
					.find('.spf-siblings')
					.spf_siblings()
				$this
					.children('.spf-field-palette')
					.find('.spf-siblings')
					.spf_siblings()

				// Help Tooltip
				$this
					.children('.spf-field')
					.find('.spf-help')
					.spf_help()
				$('.pcp-admin-header').find('.spf-support-area')
					.spf_help()

				if (settings.dependency) {
					$this.spf_dependency()
				}

				$this.data('inited', true)

				$(document).trigger('spf-reload-script', $this)
			}
		})
	}

	//
	// Document ready and run scripts.
	//
	$(document).ready(function () {
		$('.spf-save').spf_save()
		$('.spf-confirm').spf_confirm()
		$('.spf-nav-options').spf_nav_options()
		$('.spf-nav-metabox').spf_nav_metabox()
		$('.spf-expand-all').spf_expand_all()
		$('.spf-search').spf_search()
		$('.spf-sticky-header').spf_sticky()
		$('.spf-page-templates').spf_page_templates()
		$('.spf-post-formats').spf_post_formats()
		$('.spf-onload').spf_reload_script()
		$('.widget').spf_widgets()
	})

	// ======================================================
	// Post
	// ------------------------------------------------------
	// Trigger taxonomy list when post type is selected.
	$('.sp_pcp_post_type select').on('change', function (event) {
		event.preventDefault()
		var data = {
			action: 'sps_get_taxonomies',
			pcp_post_types: $(this).val(),
			nonce: $('#spf_pcp_metabox_noncesp_pcp_view_options').val()
		}
		$.post(ajaxurl, data, function (response) {
			$('.sp_pcp_post_taxonomy select').html(response)
			$('.sp_pcp_post_taxonomy select').trigger('chosen:updated')
		})
	})
	// Update terms list on the change of post taxonomy.
	$(document).on('change', '.sp_pcp_post_taxonomy select', function (e) {
		e.preventDefault();
		var taxSelector = $(this)
		var data = {
			action: 'sps_get_terms', // Callback function.
			pcp_post_taxonomy: $(this).val(),
			nonce: $('#spf_pcp_metabox_noncesp_pcp_view_options').val()
		}
		$.ajax({
			type: "POST",
			url: ajaxurl,
			data: data,
			error: function (response) {
				console.log(response)
			},
			success: function (response) {
				$(taxSelector)
					.parent()
					.parent('.sp_pcp_post_taxonomy')
					.next('.sp_pcp_taxonomy_terms')
					.find('select')
					.html(response)
				$(taxSelector)
					.parent()
					.parent('.sp_pcp_post_taxonomy')
					.next('.sp_pcp_taxonomy_terms')
					.find('select')
					.trigger('chosen:updated')
			}
		})
	})

	// Populate specific post list in the drop-down.
	var pt_saved_value = $('.sp_pcp_post_type select').val()
	// Change Include only post_type on change of Post Type field.
	var data_include_only = $('.sp_pcp_include_only_posts .spf-chosen-ajax').data(
		'chosen-settings'
	)
	// Change Exclude post_type on change of Post Type field.
	var data_exclude = $('.sp_pcp_exclude_posts .spf-chosen-ajax').data(
		'chosen-settings'
	)
	if ($('.spf-nav').hasClass('spf-nav-metabox')) {
		data_exclude.data.query_args.post_type = pt_saved_value
		data_include_only.data.query_args.post_type = pt_saved_value
	}
	$('.sp_pcp_post_type select').on('change', function (event) {
		event.preventDefault()
		var pt_value = $(this).val()
		// Change Include only post_type on change of Post Type field.
		data_include_only.data.query_args.post_type = pt_value
		// Change Exclude post_type on change of Post Type field.
		data_exclude.data.query_args.post_type = pt_value
	})

	/*  Keep the accordion field's first item opened */
	$(window).on('load', function () {
		$('.pcp-opened-accordion').each(function () {
			if (!$(this).hasClass('hidden')) {
				$(this).addClass('pcp_saved_filter')
			}
		})
	})
	$('.spf-field-checkbox.pcp_advanced_filter').on('change', function (event) {
		$('.pcp-opened-accordion').each(function () {
			if ($(this).hasClass('hidden')) {
				$(this).removeClass('pcp_saved_filter')
			} else {
				$(this).addClass('pcp_saved_filter')
			}
			if (!$(this).hasClass('pcp_saved_filter')) {
				if (
					$(this)
						.find('.spf-accordion-title')
						.siblings('.spf-accordion-content')
						.hasClass('spf-accordion-open')
				) {
					$(this).find('.spf-accordion-title')
				} else {
					$(this)
						.find('.spf-accordion-title')
						.trigger('click')
					$(this)
						.find('.spf-accordion-content')
						.find('.spf-cloneable-add')
						.trigger('click')
				}
			}
		})
	})

	// Pro only tag.
	$('.pcp-pagination-type li:nth-child(n+2) input').attr('disabled', true)
	$("label:contains((Pro))").css({ 'pointer-events': 'none', 'opacity': '0.8', 'user-select': 'none' })
	$("label:contains((Pro)) input").attr('disabled', true).css('opacity', '1')
	$("select option:contains((Pro))").attr('disabled', true).css('opacity', '1')
	// Image custom size.
	// Post Meta fields 5th to last.
	// Post content type with limit.

	// $('.post_content_sorter .spf--sortable-item:has(div.pcp-pro-only)').css({ "pointer-events": "none", "borderColor": "#bebebe" });
	// $('.post_content_sorter .spf--sortable-item:has(div.pcp-pro-only)').find('h4.spf-accordion-title, .spf--sortable-helper').css({ "backgroundColor": "#f0f0f0", "color": "#777", "marginLeft": "0", "paddingRight": "0" });
	// $('.post_content_sorter .spf--sortable-item:has(div.pcp-pro-only)').find('.spf--sortable-helper .fa').css("color", "#777");



	var preview_box = $('#spsp-preview-box');
	var pcp_display = $('#sp_pcp_display').hide();

	// $('#spsp-show-preview:contains(Hide)').
	$(document).on('click', '#spsp-show-preview:contains(Hide)', function (e) {
		e.preventDefault();
		var _this = $(this);
		_this.html('<i class="fa fa-eye" aria-hidden="true"></i> Show Preview');
		preview_box.html('');
		pcp_display.hide();
	});
	$(document).on('click', '#spsp-show-preview:not(:contains(Hide))', function (e) {
		e.preventDefault();
		var previewJS = window.spf_vars.previewJS;
		var _data = $('form#post').serialize();
		var _this = $(this);
		var $sp_id = $(this).data('id');
		var data = {
			action: 'spf_preview_meta_box',
			id: $sp_id,
			data: _data,
			nonce: $('#spf_pcp_metabox_noncesp_pcp_view_options').val()
		};
		$.ajax({
			type: "POST",
			url: ajaxurl,
			data: data,
			error: function (response) {
				console.log(response)
			},
			success: function (response) {
				pcp_display.show();
				preview_box.html(response);
				$.getScript(previewJS, function () {
					_this.html('<i class="fa fa-eye-slash" aria-hidden="true"></i> Hide Preview');
					$(document).on('change', '#sp_pcp_view_options', function (e) {
						e.preventDefault();
						_this.html('<i class="fa fa-refresh" aria-hidden="true"></i> Update Preview');
					});
					$(document).on('change', '#spf-section-sp_pcp_layouts_1', function (e) {
						e.preventDefault();
						_this.html('<i class="fa fa-refresh" aria-hidden="true"></i> Update Preview');
					});
					$("html, body").animate({ scrollTop: pcp_display.offset().top - 50 }, "slow");
				})
			}
		})
	});

	function isValidJSONString(str) {
		try {
			JSON.parse(str);
		} catch (e) {
			return false;
		}
		return true;
	}
	// Smart-post-show export.
	var $export_type = $('.pcp_what_export').find('input:checked').val();
	$('.pcp_what_export').on('change', function () {
		$export_type = $(this).find('input:checked').val();
	});

	$('.pcp_export .spf--button').on('click', function (event) {
		event.preventDefault();

		var $shortcode_ids = $('.pcp_post_ids select').val();
		var $ex_nonce = $('#spf_options_noncesp_post_carousel_tools').val();
		var selected_shortcode = $export_type === 'selected_shortcodes' ? $shortcode_ids : 'all_shortcodes';
		if ($export_type === 'all_shortcodes' || $export_type === 'selected_shortcodes') {
			var data = {
				action: 'pcp_export_shortcodes',
				pcp_ids: selected_shortcode,
				nonce: $ex_nonce,
			}
		} else {
			$('.spf-form-result.spf-form-success').text('No carousel selected.').show();
			setTimeout(function () {
				$('.spf-form-result.spf-form-success').hide().text('');
			}, 3000);
		}
		$.post(ajaxurl, data, function (resp) {
			if (resp) {
				// Convert JSON Array to string.
				if (isValidJSONString(resp)) {
					var json = JSON.stringify(JSON.parse(resp));
				} else {
					var json = JSON.stringify(resp);
				}
				// Convert JSON string to BLOB.
				var blob = new Blob([json], { type: 'application/json' });
				var link = document.createElement('a');
				var pcp_time = $.now();
				link.href = window.URL.createObjectURL(blob);
				link.download = "smart-post-show-" + pcp_time + ".json";
				link.click();
				$('.spf-form-result.spf-form-success').text('Exported successfully!').show();
				setTimeout(function () {
					$('.spf-form-result.spf-form-success').hide().text('');
					$('.pcp_post_ids select').val('').trigger('chosen:updated');
				}, 3000);
			}
		});
	});
	// smart-post-show import.
	$('.pcp_import button.import').on('click', function (event) {
		event.preventDefault();
		var $this = $(this),
			this_text = $this.text(),
			pcp_shortcodes = $('#import').prop('files')[0];
		if ($('#import').val() != '') {
			$this.prop('disabled', true).css('opacity', '0.7');
			var $im_nonce = $('#spf_options_noncesp_post_carousel_tools').val();
			var reader = new FileReader();
			reader.readAsText(pcp_shortcodes);
			reader.onload = function (event) {
				var jsonObj = JSON.stringify(event.target.result);
				$.ajax({
					url: ajaxurl,
					type: 'POST',
					data: {
						shortcode: jsonObj,
						action: 'pcp_import_shortcodes',
						nonce: $im_nonce,
					},
					success: function (resp) {
						$('.spf-form-result.spf-form-success').text('Imported successfully!').show();
						setTimeout(function () {
							$('.spf-form-result.spf-form-success').hide().text('');
							$('#import').val('');
							$this.prop('disabled', false).css('opacity', '1');
							window.location.replace($('#pcp_shortcode_link_redirect').attr('href'));
						}, 2000);
					},
					error: function (error) {
						$('#import').val('');
						$this.prop('disabled', false).css('opacity', '1');
					}
				});
			}
		} else {
			$('.spf-form-result.spf-form-success').text('No exported json file chosen.').show();
			setTimeout(function () {
				$('.spf-form-result.spf-form-success').hide().text('');
			}, 3000);
		}
	});

	$(document).on('keyup change', '.sp_post_carousel_page_pcp_settings #spf-form', function (e) {
		e.preventDefault();
		var $button = $(this).find('.spf-save');
		$button.css({ "background-color": "#00C263", "pointer-events": "initial" }).val('Save Settings');
	});
	$('.sp_post_carousel_page_pcp_settings .spf-save').on('click', function (e) {
		e.preventDefault();
		$(this).css({ "background-color": "#C5C5C6", "pointer-events": "none" }).val('Changes Saved');
	})
	// Function to update icon type
	function updateNavPosition(selector, regex, type) {
		var str = "";
		$(selector + ' option:selected').each(function () {
			str = $(this).val();
		});
		var src = $(selector + ' .spf-fieldset img').attr('src');
		var result = src.match(regex);
		if (result && result[1]) {
			src = src.replace(result[1], str);
			$(selector + ' .spf-fieldset img').attr('src', src);
		}
		if (type.includes(str)) {
			$(selector + ' .pcp-pro-notice').hide();
		} else {
			var noticeText = "This is a <a href='https://smartpostshow.com/pricing/' target='_blank'>Pro Feature!</a>";
			$(selector + ' .pcp-pro-notice').html(noticeText).show();
		}
	}
	$('.pcp_carousel_nav_position').on('change', function () {
		updateNavPosition(".pcp_carousel_nav_position", /nav-position\/(.+)\.svg/, 'top_right');
	});
	if ($('.pcp_carousel_nav_position').length > 0) {
		updateNavPosition(".pcp_carousel_nav_position", /nav-position\/(.+)\.svg/, 'top_right');
	}

})(jQuery, window, document)