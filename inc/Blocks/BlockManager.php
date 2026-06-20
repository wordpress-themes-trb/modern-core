<?php
declare(strict_types=1);

namespace ModernCore\Blocks;
use ModernCore\Core\HooksManager;

class BlockManager {

    public function register_hooks( HooksManager $hooks ): void {
        $hooks->add_action( 'init', $this, 'register_custom_blocks' );
        $hooks->add_filter( 'block_categories_all', $this, 'register_block_category' );
        // Force the script injection hook
        $hooks->add_action( 'enqueue_block_editor_assets', $this, 'force_vite_scripts' );
    }

    public function force_vite_scripts(): void {
        wp_enqueue_script(
            'modern-core-advanced-header-script',
            get_template_directory_uri() . '/assets/dist/blocks/advanced-header/index.js',
            ['wp-blocks', 'wp-element', 'wp-components', 'wp-block-editor', 'wp-i18n'],
            time(), // Bypasses browser cache automatically
            true
        );
    }

    public function register_custom_blocks(): void {
        $blocks_dir = get_template_directory() . '/Blocks';
        if ( ! is_dir( $blocks_dir ) ) { return; }

        $blocks = array_diff( scandir( $blocks_dir ), [ '.', '..' ] );
        foreach ( $blocks as $block ) {
            $block_path = $blocks_dir . '/' . $block;
            if ( is_dir( $block_path ) && file_exists( $block_path . '/block.json' ) ) {
                register_block_type( $block_path );
            }
        }
    }

    public function register_block_category( array $categories ): array {
        return array_merge( $categories, [
            [ 'slug' => 'modern-core', 'title' => __( 'Modern Core Blocks', 'modern-core' ) ],
        ]);
    }
}