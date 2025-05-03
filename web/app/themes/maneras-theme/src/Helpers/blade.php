<?php

use Illuminate\Container\Container;
use Illuminate\Events\Dispatcher;
use Illuminate\Filesystem\Filesystem;
use Illuminate\View\Compilers\BladeCompiler;
use Illuminate\View\Engines\CompilerEngine;
use Illuminate\View\Engines\EngineResolver;
use Illuminate\View\Factory;
use Illuminate\View\FileViewFinder;
use Jenssegers\Blade\Blade;

/**
 * Manually create a Blade instance without using Jenssegers\Blade constructor
 * to avoid dependency on Container::terminating()
 *
 * @param string $views Path to views directory
 * @param string $cache Path to cache directory
 * @return Factory
 */
function create_blade_instance( $views, $cache ) {
	// Create dependencies manually
	$filesystem      = new Filesystem();
	$eventDispatcher = new Dispatcher( new Container() );

	// Create the view finder
	$viewFinder = new FileViewFinder( $filesystem, array( $views ) );

	// Create the engine resolver
	$resolver = new EngineResolver();

	// Create the blade compiler and register the blade engine
	$bladeCompiler = new BladeCompiler( $filesystem, $cache );
	$resolver->register(
		'blade',
		function () use ( $bladeCompiler, $filesystem ) {
			return new CompilerEngine( $bladeCompiler, $filesystem );
		}
	);

	// Create the factory
	$factory = new Factory( $resolver, $viewFinder, $eventDispatcher );
	$factory->addExtension( 'blade.php', 'blade' );

	return $factory;
}

/**
 * Render and display a Blade view.
 *
 * @param string $view Name of the view (without .blade.php).
 * @param array  $data Data to pass to the template.
 *
 * @return void
 */
function render( string $view, array $data = array() ): void {
	$themeDir = get_stylesheet_directory();
	$views    = $themeDir . '/resources/views';
	$cache    = $themeDir . '/cache';
	if ( ! is_dir( $cache ) ) {
		mkdir( $cache, 0755, true );
	}

	// Create a custom blade instance
	$factory = create_blade_instance( $views, $cache );

	// Register view composers if needed
	if ( class_exists( 'ManerasTheme\\View\\ViewComposers' ) ) {
		\ManerasTheme\View\ViewComposers::registerAll( $factory );
	}

	echo $factory->make( $view, $data )->render();
	exit;
}

/**
 * Renders a Blade template.
 *
 * @param string $template The name of the Blade template to render.
 * @param array  $data     An associative array of data to pass to the template.
 *
 * @return void
 */
function render_blade_template( string $template, array $data = array() ): void {
	$themeDir = get_stylesheet_directory();
	$views    = $themeDir . '/resources/views';
	$cache    = $themeDir . '/cache';
	if ( ! is_dir( $cache ) ) {
		mkdir( $cache, 0755, true );
	}

	// Create a custom blade instance
	$factory = create_blade_instance( $views, $cache );

	// Register view composers if needed
	if ( class_exists( 'ManerasTheme\\View\\ViewComposers' ) ) {
		\ManerasTheme\View\ViewComposers::registerAll( $factory );
	}

	echo $factory->make( $template, $data )->render();
	exit;
}
