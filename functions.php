<?php

show_admin_bar( false );

add_theme_support( 'post-thumbnails' );
add_theme_support( 'html5' );
add_theme_support( 'title-tag' );

if ( function_exists('register_nav_menu')) { register_nav_menu( 'main', 'Main Nav' ); }

add_action( 'widgets_init', 'register_main_sidebar' );
add_action( 'widgets_init', 'register_post_sidebar' );

add_action( 'wp_enqueue_scripts', 'grauburo_styles' );

//  --- Functions ---

function grauburo_styles() {
  wp_enqueue_style( 'lity', get_template_directory_uri() . '/css/lity.min.css' );
  wp_enqueue_style( 'theme-style', get_stylesheet_uri() );
  wp_enqueue_script( 'jquery' );
  wp_enqueue_script( 'lity', get_template_directory_uri() . '/js/lity.min.js','','',true );
}

function register_main_sidebar(){
	register_sidebar( array(
		'name' => 'top',
		'id' => 'homepage-top-bar',
		'description' => 'Выводиться как боковая панель только на главной странице сайта.',
		'before_widget' => '<li class="homepage-widget-block">',
		'after_widget' => '</li>',
		'before_title' => '<h2 class="widgettitle">',
		'after_title' => '</h2>',
	) );
}

function register_post_sidebar(){
	register_sidebar( array(
		'name' => 'post',
		'id' => 'postside-bar',
		'description' => 'Выводиться последние статьи на странице статьи.'
	) );
}

// фильтр передает переменную $template - путь до файла шаблона.
// Изменяя этот путь мы изменяем файл шаблона.
add_filter('template_include', 'my_template');

function my_template( $template ) {

// шаблон для архива панорам
	if( is_category( array( '3d-tours', 'google-pano', 'blog' ) ) ){
		return get_stylesheet_directory() . '/category-pano.php';
	}
	return $template;
}

//

add_filter('single_template', create_function(
  '$the_template',
  'foreach( (array) get_the_category() as $cat ) {
    if ( file_exists(TEMPLATEPATH . "/single-{$cat->slug}.php") )
      return TEMPLATEPATH . "/single-{$cat->slug}.php"; }
    return $the_template;' )
);

/** Подключение Carbon Fields и файлов конфигурации */

require_once __DIR__ . '/includes/carbon-fields/carbon-fields-plugin.php';

add_action('carbon_register_fields', 'crb_register_custom_fields');

function crb_register_custom_fields() {
    include_once(dirname(__FILE__) . '/includes/custom-fields/theme-meta-options.php');
    include_once(dirname(__FILE__) . '/includes/custom-fields/page-meta-options.php');
    include_once(dirname(__FILE__) . '/includes/custom-fields/term-meta-options.php');
}

/** Удаляем визуальный редактор **/
 
add_action( 'admin_head', 'hide_editor' );

function hide_editor() {
	$template_file = basename( get_page_template() );

	if($template_file == 'pricing.php' || has_category(array('3d-tours', 'google-pano'))) { 
		remove_post_type_support('page', 'editor');
    }
    if(has_category(array('3d-tours', 'google-pano', 'photo'))) { 
		remove_post_type_support('post', 'editor');
	}
}

?>