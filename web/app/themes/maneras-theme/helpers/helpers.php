<?php
/**
 * Helpers for the theme.
 *
 * Some of them are based on the Laravel framework.
 *
 * helpers/helpers.php
 */

if ( file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
	require_once __DIR__ . '/vendor/autoload.php';
}

use Illuminate\Container\Container;
use Illuminate\Events\Dispatcher;
use Illuminate\Filesystem\Filesystem;
use Illuminate\View\Compilers\BladeCompiler;
use Illuminate\View\Engines\CompilerEngine;
use Illuminate\View\Engines\PhpEngine;
use Illuminate\View\Engines\EngineResolver;
use Illuminate\View\Factory;
use Illuminate\View\FileViewFinder;

if ( ! function_exists( 'get_blade' ) ) {
	/**
	 * Bootstrap Blade templating.
	 *
	 * @return Factory
	 */
	function get_blade(): Factory {
		static $factory = null;
		if ( $factory === null ) {
			$themeDir = get_stylesheet_directory();
			$views    = array( $themeDir . '/resources/views' );
			$cache    = $themeDir . '/cache';

			// 1) File system and events
			$filesystem      = new Filesystem();
			$eventDispatcher = new Dispatcher( new Container() );
			$bladeCompiler   = new BladeCompiler( $filesystem, $cache );

			// 💥 In development, clear the cache on every request
			if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
				$cachePath = $cache . '/*.php';
				foreach ( glob( $cachePath ) as $compiledFile ) {
					@unlink( $compiledFile );
				}
			}

			// 2) Engine resolver
			$resolver = new EngineResolver();

			// – Blade engine
			$resolver->register(
				'blade',
				function () use ( $bladeCompiler, $filesystem ) {
					return new CompilerEngine( $bladeCompiler, $filesystem );
				}
			);

			// – PHP engine (for .php if needed)
			$resolver->register(
				'php',
				function () use ( $filesystem ) {
					return new PhpEngine( $filesystem );
				}
			);

			// 3) View finder
			$finder = new FileViewFinder( $filesystem, $views );

			// 4) Factory
			$factory = new Factory( $resolver, $finder, $eventDispatcher );
			// Associate the extension
			$factory->addExtension( 'blade.php', 'blade' );
		}
		return $factory;
	}
}

if ( ! function_exists( 'vite_asset' ) ) {
	/**
	 * Returns the versioned URL of an asset processed by Vite.
	 *
	 * @param string $path Path you pass (may or may not include "resources/")
	 * @return string Public URL to the file in public/.vite/
	 */
	function vite_asset( string $path ): string {
		static $manifest = null;
		if ( is_null( $manifest ) ) {
			$manPath = get_stylesheet_directory() . '/public/.vite/manifest.json';
			if ( ! file_exists( $manPath ) ) {
				return '';
			}
			$manifest = json_decode( file_get_contents( $manPath ), true );
		}

		// Normalize by removing the "resources/" prefix if it exists
		$normalized = preg_replace( '#^resources/#', '', ltrim( $path, '/' ) );

		// 1) Try the normalized key
		if ( isset( $manifest[ $normalized ]['file'] ) ) {
			return get_stylesheet_directory_uri()
				. '/public/'
				. $manifest[ $normalized ]['file'];
		}

		// 2) Try the key as is (in case no prefix is used)
		if ( isset( $manifest[ $path ]['file'] ) ) {
			return get_stylesheet_directory_uri()
				. '/public/'
				. $manifest[ $path ]['file'];
		}

		// 3) As a fallback, search by entry['src']
		foreach ( $manifest as $entry ) {
			if ( isset( $entry['src'] ) ) {
				// compare with both versions
				if ( $entry['src'] === $normalized || $entry['src'] === $path ) {
					return get_stylesheet_directory_uri()
						. '/public/'
						. $entry['file'];
				}
			}
		}

		// 4) Not found
		return '';
	}
}
