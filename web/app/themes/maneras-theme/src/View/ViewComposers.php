<?php
// @codingStandardsIgnoreFile
namespace ManerasTheme\View;

use Illuminate\View\Factory;

/**
 * Centralises registration of all Blade view‑composers.
 */
final class ViewComposers {

	/**
	 * Returns an array of all view‑composers.
	 *
	 * @return array
	 */
	private static function composers(): array {
		return array(
			Composers\HomeComposer::class,
			Composers\SingleArticle::class,
			Composers\ArchiveArticle::class,
			Composers\ArchiveEvent::class,
			// ⬇️ resto de composers:
			// Composers\SingleEvent::class,
		);
	}

	/**
	 * Registers all view‑composers.
	 *
	 * @param Factory $blade Blade factory instance.
	 *
	 * @return void
	 */
	public static function registerAll( Factory $blade ): void {
		foreach ( self::composers() as $composer ) {
			if ( method_exists( $composer, 'register' ) ) {
				$composer::register( $blade );
			}
		}
	}
}
