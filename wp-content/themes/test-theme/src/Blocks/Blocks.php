<?php

/**
 * Class Blocks is the base class for Gutenberg blocks registration.
 * It provides the ability to register custom blocks using manifest.json.
 *
 * @package TestTheme\Blocks
 */

declare(strict_types=1);

namespace TestTheme\Blocks;

use TestThemeVendor\EightshiftLibs\Blocks\AbstractBlocks;

/**
 * Class Blocks
 */
class Blocks extends AbstractBlocks
{
	/**
	 * Register all the hooks
	 *
	 * @return void
	 */
	public function register(): void
	{
		// Register all custom blocks.
		\add_action('init', [$this, 'getBlocksDataFullRaw'], 10);
		\add_action('init', [$this, 'registerBlocks'], 11);

		// Remove P tags from content.
		\remove_filter('the_content', 'wpautop');

		// Create new custom category for custom blocks.
		if (\is_wp_version_compatible('5.8')) {
			\add_filter('block_categories_all', [$this, 'getCustomCategory'], 10, 2);
		} else {
			\add_filter('block_categories', [$this, 'getCustomCategoryOld'], 10, 2);
		}

		// Register custom theme support options.
		\add_action('after_setup_theme', [$this, 'addThemeSupport'], 25);

		// Register custom project color palette.
		\add_action('after_setup_theme', [$this, 'changeEditorColorPalette'], 11);

		// Filter block content.
		\add_filter('render_block_data', [$this, 'filterBlocksContent'], 10, 2);

		// Output inline css variables.
		\add_action('wp_footer', [$this, 'outputCssVariablesInline']);

    // Disable core blocks
    \add_filter('allowed_block_types_all', [$this, 'allowedBlocks'], 10, 2);
	}

  /**
  * Filter which blocks are displayed in the block editor.
  *
  * @param array|bool $allowedBlockTypes Array of block type slugs, or boolean to enable/disable all.
  * @param object     $post The post resource data.
  *
  * @return array
  */
  public function allowedBlocks($allowedBlockTypes, object $post): array
  {
    return array_merge(
      $this->getAllBlocksList([], $post)
    );
  }
}
