<?php
/**
 * Author: Todd Motto | @toddmotto
 * URL: html5blank.com | @html5blank
 * Custom functions, support, custom post types and more.
 */

/*------------------------------------*\
    External Modules/Files
\*------------------------------------*/

// Load any external files you have here

/*------------------------------------*\
    Theme Support
\*------------------------------------*/

if (!isset($content_width))
{
    $content_width = 900;
}

if (function_exists('add_theme_support'))
{
    // Add Menu Support
    add_theme_support('menus');

    // Add Thumbnail Theme Support
    add_theme_support('post-thumbnails');
    add_image_size('xlarge', 1500, '', true); // Large Thumbnail
    add_image_size('large', 1000, '', true); // Large Thumbnail
    add_image_size('medium', 500, '', true); // Medium Thumbnail
    add_image_size('small', 250, '', true); // Small Thumbnail
    add_image_size('facebook', 1800, 1125, true); // Facebook Thumbnail

    // Add Support for Custom Backgrounds - Uncomment below if you're going to use
    /*add_theme_support('custom-background', array(
    'default-color' => 'FFF',
    'default-image' => get_template_directory_uri() . '/img/bg.jpg'
    ));*/

    // Add Support for Custom Header - Uncomment below if you're going to use
    /*add_theme_support('custom-header', array(
    'default-image'          => get_template_directory_uri() . '/img/headers/default.jpg',
    'header-text'            => false,
    'default-text-color'     => '000',
    'width'                  => 1000,
    'height'                 => 198,
    'random-default'         => false,
    'wp-head-callback'       => $wphead_cb,
    'admin-head-callback'    => $adminhead_cb,
    'admin-preview-callback' => $adminpreview_cb
    ));*/

    // Enables post and comment RSS feed links to head
    add_theme_support('automatic-feed-links');

    // Add Title Tag since 4.1
    add_theme_support( 'title-tag' );

    // Localisation Support
    load_theme_textdomain('html5blank', get_template_directory() . '/languages');
}

/*------------------------------------*\
    Functions
\*------------------------------------*/

// HTML5 Blank navigation
function html5blank_nav()
{
    wp_nav_menu(
    array(
        'theme_location'  => 'header-menu',
        'menu'            => '',
        'container'       => 'div',
        'container_class' => 'menu-{menu slug}-container',
        'container_id'    => '',
        'menu_class'      => 'menu',
        'menu_id'         => '',
        'echo'            => true,
        'fallback_cb'     => 'wp_page_menu',
        'before'          => '',
        'after'           => '',
        'link_before'     => '',
        'link_after'      => '',
        'items_wrap'      => '<ul>%3$s</ul>',
        'depth'           => 0,
        'walker'          => ''
        )
    );
}

// Load HTML5 Blank scripts (header.php)
function html5blank_header_scripts()
{
    if ($GLOBALS['pagenow'] != 'wp-login.php' && !is_admin()) {

        wp_register_script('modernizr', get_template_directory_uri() . '/js/lib/modernizr-custom.js', array(), '2.7.1'); // Modernizr
        wp_enqueue_script('modernizr'); // Enqueue it!

		wp_register_script( 'plugins', get_template_directory_uri() . '/js/min/plugins-min.js', array('jquery'), '1.0.1', true );
		wp_enqueue_script( 'plugins' );

		wp_register_script( 'scripts', get_template_directory_uri() . '/js/min/scripts-min.js', array('jquery'), '1.0.1', true );
		wp_enqueue_script( 'scripts' );
    }
}


// Load HTML5 Blank styles
function html5blank_styles()
{

    wp_register_style('font', 'https://fonts.googleapis.com/css?family=Merriweather', array(), '1.0', 'all');
    wp_enqueue_style('font'); // Enqueue it!

    wp_register_style('global', get_template_directory_uri() . '/css/global.css', array(), '1.0', 'all');
    wp_enqueue_style('global'); // Enqueue it!
}

// Register HTML5 Blank Navigation
function register_html5_menu()
{
    register_nav_menus(array( // Using array to specify more menus if needed
        'header-menu' => __('Header Menu', 'html5blank'), // Main Navigation
        'sidebar-menu' => __('Sidebar Menu', 'html5blank'), // Sidebar Navigation
        'extra-menu' => __('Extra Menu', 'html5blank') // Extra Navigation if needed (duplicate as many as you need!)
    ));
}

// Remove the <div> surrounding the dynamic navigation to cleanup markup
function my_wp_nav_menu_args($args = '')
{
    $args['container'] = false;
    return $args;
}

// Remove Injected classes, ID's and Page ID's from Navigation <li> items
function my_css_attributes_filter($var)
{
    return is_array($var) ? array() : '';
}

// Remove invalid rel attribute values in the categorylist
function remove_category_rel_from_category_list($thelist)
{
    return str_replace('rel="category tag"', 'rel="tag"', $thelist);
}

// Add page slug to body class, love this - Credit: Starkers Wordpress Theme
function add_slug_to_body_class($classes)
{
    global $post;
    if (is_home()) {
        $key = array_search('blog', $classes);
        if ($key > -1) {
            unset($classes[$key]);
        }
    } elseif (is_page()) {
        $classes[] = sanitize_html_class($post->post_name);
    } elseif (is_singular()) {
        $classes[] = sanitize_html_class($post->post_name);
    }

    return $classes;
}

// Remove the width and height attributes from inserted images
function remove_width_attribute( $html ) {
   $html = preg_replace( '/(width|height)="\d*"\s/', "", $html );
   return $html;
}


// If Dynamic Sidebar Exists
if (function_exists('register_sidebar'))
{
    // Define Sidebar Widget Area 1
    register_sidebar(array(
        'name' => __('Widget Area 1', 'html5blank'),
        'description' => __('Description for this widget-area...', 'html5blank'),
        'id' => 'widget-area-1',
        'before_widget' => '<div id="%1$s" class="%2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3>',
        'after_title' => '</h3>'
    ));

    // Define Sidebar Widget Area 2
    register_sidebar(array(
        'name' => __('Widget Area 2', 'html5blank'),
        'description' => __('Description for this widget-area...', 'html5blank'),
        'id' => 'widget-area-2',
        'before_widget' => '<div id="%1$s" class="%2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3>',
        'after_title' => '</h3>'
    ));
}

// Remove wp_head() injected Recent Comment styles
function my_remove_recent_comments_style()
{
    global $wp_widget_factory;
    remove_action('wp_head', array(
        $wp_widget_factory->widgets['WP_Widget_Recent_Comments'],
        'recent_comments_style'
    ));
}

// Pagination for paged posts, Page 1, Page 2, Page 3, with Next and Previous Links, No plugin
function html5wp_pagination()
{
    global $wp_query;
    $big = 999999999;
    echo paginate_links(array(
        'base' => str_replace($big, '%#%', get_pagenum_link($big)),
        'format' => '?paged=%#%',
        'current' => max(1, get_query_var('paged')),
        'total' => $wp_query->max_num_pages
    ));
}

// Custom Excerpts
function html5wp_index($length) // Create 20 Word Callback for Index page Excerpts, call using html5wp_excerpt('html5wp_index');
{
    return 20;
}

// Create 40 Word Callback for Custom Post Excerpts, call using html5wp_excerpt('html5wp_custom_post');
function html5wp_custom_post($length)
{
    return 40;
}

// Create the Custom Excerpts callback
function html5wp_excerpt($length_callback = '', $more_callback = '')
{
    global $post;
    if (function_exists($length_callback)) {
        add_filter('excerpt_length', $length_callback);
    }
    if (function_exists($more_callback)) {
        add_filter('excerpt_more', $more_callback);
    }
    $output = get_the_excerpt();
    $output = apply_filters('wptexturize', $output);
    $output = apply_filters('convert_chars', $output);
    $output = '<p>' . $output . '</p>';
    echo $output;
}

// Custom View Article link to Post
function html5_blank_view_article($more)
{
    global $post;
    return '...';
}

// Remove Admin bar
function remove_admin_bar()
{
    return false;
}

// Remove 'text/css' from our enqueued stylesheet
function html5_style_remove($tag)
{
    return preg_replace('~\s+type=["\'][^"\']++["\']~', '', $tag);
}

// Remove thumbnail width and height dimensions that prevent fluid images in the_thumbnail
function remove_thumbnail_dimensions( $html )
{
    $html = preg_replace('/(width|height)=\"\d*\"\s/', "", $html);
    return $html;
}

// Custom Gravatar in Settings > Discussion
function html5blankgravatar ($avatar_defaults)
{
    $myavatar = get_template_directory_uri() . '/img/gravatar.jpg';
    $avatar_defaults[$myavatar] = "Custom Gravatar";
    return $avatar_defaults;
}

// Threaded Comments
function enable_threaded_comments()
{
    if (!is_admin()) {
        if (is_singular() AND comments_open() AND (get_option('thread_comments') == 1)) {
            wp_enqueue_script('comment-reply');
        }
    }
}

// Custom Comments Callback
function html5blankcomments($comment, $args, $depth)
{
    $GLOBALS['comment'] = $comment;
    extract($args, EXTR_SKIP);

    if ( 'div' == $args['style'] ) {
        $tag = 'div';
        $add_below = 'comment';
    } else {
        $tag = 'li';
        $add_below = 'div-comment';
    }
?>
    <!-- heads up: starting < for the html tag (li or div) in the next line: -->
    <<?php echo $tag ?> <?php comment_class(empty( $args['has_children'] ) ? '' : 'parent') ?> id="comment-<?php comment_ID() ?>">
    <?php if ( 'div' != $args['style'] ) : ?>
    <div id="div-comment-<?php comment_ID() ?>" class="comment-body">
    <?php endif; ?>
    <div class="comment-author vcard">
    <?php if ($args['avatar_size'] != 0) echo get_avatar( $comment, $args['avatar_size'] ); ?>
    <?php printf(__('<cite class="fn">%s</cite> <span class="says">says:</span>'), get_comment_author_link()) ?>
    </div>
<?php if ($comment->comment_approved == '0') : ?>
    <em class="comment-awaiting-moderation"><?php _e('Your comment is awaiting moderation.') ?></em>
    <br />
<?php endif; ?>

    <div class="comment-meta commentmetadata"><a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ) ?>">
        <?php
            printf( __('%1$s at %2$s'), get_comment_date(),  get_comment_time()) ?></a><?php edit_comment_link(__('(Edit)'),'  ','' );
        ?>
    </div>

    <?php comment_text() ?>

    <div class="reply">
    <?php comment_reply_link(array_merge( $args, array('add_below' => $add_below, 'depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
    </div>
    <?php if ( 'div' != $args['style'] ) : ?>
    </div>
    <?php endif; ?>
<?php }

/*------------------------------------*\
    Actions + Filters + ShortCodes
\*------------------------------------*/

// Add Actions
add_action('init', 'html5blank_header_scripts'); // Add Custom Scripts to wp_head
//add_action('wp_print_scripts', 'html5blank_conditional_scripts'); // Add Conditional Page Scripts
add_action('get_header', 'enable_threaded_comments'); // Enable Threaded Comments
add_action('wp_enqueue_scripts', 'html5blank_styles'); // Add Theme Stylesheet
add_action('init', 'register_html5_menu'); // Add HTML5 Blank Menu
//add_action('init', 'create_post_type_html5'); // Add our HTML5 Blank Custom Post Type
add_action('widgets_init', 'my_remove_recent_comments_style'); // Remove inline Recent Comment Styles from wp_head()
add_action('init', 'html5wp_pagination'); // Add our HTML5 Pagination

// Remove Actions
remove_action('wp_head', 'feed_links_extra', 3); // Display the links to the extra feeds such as category feeds
remove_action('wp_head', 'feed_links', 2); // Display the links to the general feeds: Post and Comment Feed
remove_action('wp_head', 'rsd_link'); // Display the link to the Really Simple Discovery service endpoint, EditURI link
remove_action('wp_head', 'wlwmanifest_link'); // Display the link to the Windows Live Writer manifest file.
remove_action('wp_head', 'wp_generator'); // Display the XHTML generator that is generated on the wp_head hook, WP version
remove_action('wp_head', 'rel_canonical');
remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);

// Add Filters
add_filter('avatar_defaults', 'html5blankgravatar'); // Custom Gravatar in Settings > Discussion
add_filter('body_class', 'add_slug_to_body_class'); // Add slug to body class (Starkers build)
add_filter('widget_text', 'do_shortcode'); // Allow shortcodes in Dynamic Sidebar
add_filter('widget_text', 'shortcode_unautop'); // Remove <p> tags in Dynamic Sidebars (better!)
add_filter('wp_nav_menu_args', 'my_wp_nav_menu_args'); // Remove surrounding <div> from WP Navigation
// add_filter('nav_menu_css_class', 'my_css_attributes_filter', 100, 1); // Remove Navigation <li> injected classes (Commented out by default)
// add_filter('nav_menu_item_id', 'my_css_attributes_filter', 100, 1); // Remove Navigation <li> injected ID (Commented out by default)
// add_filter('page_css_class', 'my_css_attributes_filter', 100, 1); // Remove Navigation <li> Page ID's (Commented out by default)
add_filter('the_category', 'remove_category_rel_from_category_list'); // Remove invalid rel attribute
add_filter('the_excerpt', 'shortcode_unautop'); // Remove auto <p> tags in Excerpt (Manual Excerpts only)
add_filter('the_excerpt', 'do_shortcode'); // Allows Shortcodes to be executed in Excerpt (Manual Excerpts only)
add_filter('excerpt_more', 'html5_blank_view_article'); // Add 'View Article' button instead of [...] for Excerpts
add_filter('show_admin_bar', 'remove_admin_bar'); // Remove Admin bar
add_filter('style_loader_tag', 'html5_style_remove'); // Remove 'text/css' from enqueued stylesheet
add_filter('post_thumbnail_html', 'remove_thumbnail_dimensions', 10); // Remove width and height dynamic attributes to thumbnails
add_filter('post_thumbnail_html', 'remove_width_attribute', 10 ); // Remove width and height dynamic attributes to post images
add_filter('image_send_to_editor', 'remove_width_attribute', 10 ); // Remove width and height dynamic attributes to post images

// Remove Filters
remove_filter('the_excerpt', 'wpautop'); // Remove <p> tags from Excerpt altogether




/*------------------------------------*\
    Eigene Codes
\*------------------------------------*/

// Add Favicon to Backend
function add_favicon() {
  	$favicon_url = get_stylesheet_directory_uri() . '/img/icons/favicon.ico';
	echo '<link rel="shortcut icon" href="' . $favicon_url . '" />';
}
add_action('login_head', 'add_favicon');
add_action('admin_head', 'add_favicon');


// Customise the Login Area
function my_login_stylesheet() {
    wp_enqueue_style( 'custom-login', get_template_directory_uri() . '/css/style-login.css' );
}
add_action( 'login_enqueue_scripts', 'my_login_stylesheet' );


// Customise the Login Image URL
add_filter( 'login_headerurl', 'custom_loginlogo_url' );
function custom_loginlogo_url($url) {
	return 'http://google.de';
}

// Customise the footer in admin area
function wpfme_footer_admin () {
	echo 'Seite gestaltet und entwickelt von <a href="http://philipp-seiffert.com" target="_blank">Philipp Seiffert (M.A.) | Web- und Grafikdesign</a>. Angetrieben durch <a href="http://wordpress.org" target="_blank">WordPress</a>.';
}
add_filter('admin_footer_text', 'wpfme_footer_admin');

// Remove query string from static files
function remove_cssjs_ver( $src ) {
 if( strpos( $src, '?ver=' ) )
 $src = remove_query_arg( 'ver', $src );
 return $src;
}
add_filter( 'style_loader_src', 'remove_cssjs_ver', 10, 2 );
add_filter( 'script_loader_src', 'remove_cssjs_ver', 10, 2 );

//add post thumbnails to RSS images
function cwc_rss_post_thumbnail($content) {
   global $post;
     if(has_post_thumbnail($post->ID)) {
        $content = '<p>' . get_the_post_thumbnail($post->ID,'large') .
    '</p>' . get_the_content() . '</br></br>';
     }
    return $content;
 }
add_filter('the_excerpt_rss', 'cwc_rss_post_thumbnail');
add_filter('the_content_feed', 'cwc_rss_post_thumbnail');


// Move Yoast to bottom
function yoasttobottom() {
	return 'low';
}

add_filter( 'wpseo_metabox_prio', 'yoasttobottom');


// Add SVG to Media Uploader
function cc_mime_types( $mimes ){
	$mimes['svg'] = 'image/svg+xml';
	return $mimes;
}

add_filter( 'upload_mimes', 'cc_mime_types' );

// Facebook Bildgröße für Open Graph
add_filter('wpseo_opengraph_image_size', 'mysite_opengraph_image_size');

function mysite_opengraph_image_size($val) {
    return 'facebook';
}

// Suchwoerter hervorheben
function wps_highlight_results($text){
     if(is_search()){
     $sr = get_query_var('s');
     $keys = explode(" ",$sr);
     $text = preg_replace('/('.implode('|', $keys) .')/iu', '<mark class="search-excerpt">'.$sr.'</mark>', $text);
     }
     return $text;
}
add_filter('the_content', 'wps_highlight_results');
add_filter('the_excerpt', 'wps_highlight_results');
add_filter('the_title', 'wps_highlight_results');

// Clean comment-reply.min
function clean_header(){
	wp_deregister_script( 'comment-reply' );
}
add_action('init','clean_header');



// Emojis entfernen
function pw_remove_emojicons() 
{
    // Remove from comment feed and RSS
    remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
    remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );

    // Remove from emails
    remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );

    // Remove from head tag
    remove_action( 'wp_head', 'print_emoji_detection_script', 7 );

    // Remove from print related styling
    remove_action( 'wp_print_styles', 'print_emoji_styles' );

    // Remove from admin area
    remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
    remove_action( 'admin_print_styles', 'print_emoji_styles' );
}

add_action( 'init', 'pw_remove_emojicons' );

// Contact Form 7 nur auf bestimmten Seiten
add_action( 'wp_print_scripts', 'deregister_cf7_javascript', 100 );
function deregister_cf7_javascript() {
    if ( !is_page(array(7,424)) ) {
        wp_deregister_script( 'contact-form-7' );
    }
}
add_action( 'wp_print_styles', 'deregister_cf7_styles', 100 );
function deregister_cf7_styles() {
    if ( !is_page(array(7,424)) ) {
        wp_deregister_style( 'contact-form-7' );
    }
}


// Statify für Redakteure
add_action('admin_init', 'add_theme_caps');
function add_theme_caps() {
   $editor = get_role('editor');
   $editor->add_cap('edit_dashboard');
}

// ACF Option Seite hinzufügen
if( function_exists('acf_add_options_page') ) {

    acf_add_options_page(array(
        'page_title'    => 'Optionen',
        'menu_title'    => 'Optionen',
        'menu_slug'     => 'seiten-optionen',
        'capability'    => 'edit_posts',
        'redirect'      => false
    ));

}


// ACF GOOGLE MAP
function my_acf_init() {

    acf_update_setting('google_api_key', 'AIzaSyBhWdmaTHRs1XGw2NYgBvMiJdStapQzXZI');
}

add_action('acf/init', 'my_acf_init');


/*------------------------------------*\
    CF 7 Configuration Error
\*------------------------------------*/

add_filter( 'wpcf7_validate_configuration', '__return_false' );



/*------------------------------------*\
    BREADCRUMPS
\*------------------------------------*/



function dez_schema_breadcrumb() {
global $post;
//schema link
$schema_link = 'http://data-vocabulary.org/Breadcrumb';
$home = 'Startseite';
$delimiter = ' &raquo; ';
$homeLink = get_bloginfo('url');
if (is_home() || is_front_page()) {
// no need for breadcrumbs in homepage
}
else {
echo '<div id="mpbreadcrumbs">';
// main breadcrumbs lead to homepage
echo '<span itemscope itemtype="' . $schema_link . '">Sie sind hier: <a itemprop="url" href="' . $homeLink . '">' . '<span itemprop="title">' . $home . '</span>' . '</a></span>' . $delimiter . ' ';
// if blog page exists
if (get_page_by_path('blog')) {
if (!is_page('blog')) {
echo '<span itemscope itemtype="' . $schema_link . '"><a itemprop="url" href="' . get_permalink(get_page_by_path('blog')) . '">' . '<span itemprop="title">Blog</span></a></span>' . $delimiter . ' ';
}
}
if (is_category()) {
$thisCat = get_category(get_query_var('cat'), false);
if ($thisCat->parent != 0) {
$category_link = get_page_link(1212);
echo '<span itemscope itemtype="' . $schema_link . '"><a itemprop="url" href="' . $category_link . '">' . '<span itemprop="title">Neuigkeiten</span>' . '</a></span>' . $delimiter . ' ';
}
$category_id = get_cat_ID(single_cat_title('', false));
$category_link = get_page_link(1212);
echo '<span itemscope itemtype="' . $schema_link . '"><a itemprop="url" href="' . $category_link . '">' . '<span itemprop="title">Neuigkeiten</span>' . '</a></span>';
}
elseif (is_single() && !is_attachment()) {
if (get_post_type() != 'post') {
$post_type = get_post_type_object(get_post_type());
$slug = $post_type->rewrite;
echo '<span itemscope itemtype="' . $schema_link . '"><a itemprop="url" href="' . $homeLink . '/' . $slug['slug'] . '">' . '<span itemprop="title">' . $post_type->labels->singular_name . '</span>' . '</a></span>';
echo ' ' . $delimiter . ' ' . get_the_title();
}
else {
$category = get_the_category();
if ($category) {
foreach ($category as $cat) {
echo '<span itemscope itemtype="' . $schema_link . '"><a itemprop="url" href="' . get_page_link(1212) . '">' . '<span itemprop="title">Neuigkeiten</span>' . '</a></span>' . $delimiter . ' ';
}
}
echo get_the_title();
}
}
elseif (!is_single() && !is_page() && get_post_type() != 'post' && !is_404()) {
$post_type = get_post_type_object(get_post_type());
echo $post_type->labels->singular_name;
}
elseif (is_attachment()) {
$parent = get_post($post->post_parent);
$cat = get_the_category($parent->ID);
$cat = $cat[0];
echo get_category_parents($cat, TRUE, ' ' . $delimiter . ' ');
echo '<span itemscope itemtype="' . $schema_link . '"><a itemprop="url" href="' . get_permalink($parent) . '">' . '<span itemprop="title">' . $parent->post_title . '</span>' . '</a></span>';
echo ' ' . $delimiter . ' ' . get_the_title();
}
elseif (is_page() && !$post->post_parent) {
$get_post_slug = $post->post_title;
$post_slug = str_replace('-', ' ', $get_post_slug);
echo '<span itemscope itemtype="' . $schema_link . '"><a itemprop="url" href="' . get_permalink() . '">' . '<span itemprop="title">' . ucfirst($post_slug) . '</span>' . '</a></span>';
}
elseif (is_page() && $post->post_parent) {
$parent_id = $post->post_parent;
$breadcrumbs = array();
while ($parent_id) {
$page = get_page($parent_id);
$breadcrumbs[] = '<span itemscope itemtype="' . $schema_link . '"><a itemprop="url" href="' . get_permalink($page->ID) . '">' . '<span itemprop="title">' . get_the_title($page->ID) . '</span>' . '</a></span>';
$parent_id = $page->post_parent;
}
$breadcrumbs = array_reverse($breadcrumbs);
for ($i = 0; $i < count($breadcrumbs); $i++) {
echo $breadcrumbs[$i];
if ($i != count($breadcrumbs) - 1)
echo ' ' . $delimiter . ' ';
}
echo $delimiter . '<span itemscope itemtype="' . $schema_link . '"><a itemprop="url" href="' . get_permalink() . '">' . '<span itemprop="title">' . the_title_attribute('echo=0') . '</span>' . '</a></span>';
}
elseif (is_tag()) {
$tag_id = get_term_by('name', single_cat_title('', false), 'post_tag');
if ($tag_id) {
$tag_link = get_tag_link($tag_id->term_id);
}
echo '<span itemscope itemtype="' . $schema_link . '"><a itemprop="url" href="' . get_page_link(1212) . '">' . '<span itemprop="title">Neuigkeiten</span>' . '</a></span>';
}
elseif (is_author()) {
global $author;
$userdata = get_userdata($author);
echo '<span itemscope itemtype="' . $schema_link . '"><a itemprop="url" href="' . get_author_posts_url($userdata->ID) . '">' . '<span itemprop="title">' . $userdata->display_name . '</span>' . '</a></span>';
}
elseif (is_404()) {
echo 'Error 404';
}
elseif (is_search()) {
echo 'Search results for "' . get_search_query() . '"';
}
elseif (is_day()) {
echo '<span itemscope itemtype="' . $schema_link . '"><a itemprop="url" href="' . get_year_link(get_the_time('Y')) . '">' . '<span itemprop="title">' . get_the_time('Y') . '</span>' . '</a></span>' . $delimiter . ' ';
echo '<span itemscope itemtype="' . $schema_link . '"><a itemprop="url" href="' . get_month_link(get_the_time('Y'), get_the_time('m')) . '">' . '<span itemprop="title">' . get_the_time('F') . '</span>' . '</a></span>' . $delimiter . ' ';
echo '<span itemscope itemtype="' . $schema_link . '"><a itemprop="url" href="' . get_day_link(get_the_time('Y'), get_the_time('m'), get_the_time('d')) . '">' . '<span itemprop="title">' . get_the_time('d') . '</span>' . '</a></span>';
}
elseif (is_month()) {
echo '<span itemscope itemtype="' . $schema_link . '"><a itemprop="url" href="' . get_year_link(get_the_time('Y')) . '">' . '<span itemprop="title">' . get_the_time('Y') . '</span>' . '</a></span>' . $delimiter . ' ';
echo '<span itemscope itemtype="' . $schema_link . '"><a itemprop="url" href="' . get_month_link(get_the_time('Y'), get_the_time('m')) . '">' . '<span itemprop="title">' . get_the_time('F') . '</span>' . '</a></span>';
}
elseif (is_year()) {
echo '<span itemscope itemtype="' . $schema_link . '"><a itemprop="url" href="' . get_year_link(get_the_time('Y')) . '">' . '<span itemprop="title">' . get_the_time('Y') . '</span>' . '</a></span>';
}
if (get_query_var('paged')) {
if (is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author())
echo ' (';
echo __('Page') . ' ' . get_query_var('paged');
if (is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author())
echo ')';
}
echo '</div>';
}
}

