<?php
/**
 * @package WP_Lever
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<style rel="stylesheet" type="text/css">
    <?php if( !empty($atts['primary-color'])) : ?>

    .lever-filters .filter-values a:hover,
    .lever .lever-job-apply {
        background: <?php echo $atts['primary-color']; ?> !important;
    }

    .lever-job-header{
        border-color: <?php echo $atts['primary-color']; ?> !important;
    }

    .lever-apply-form input[type='checkbox']:checked:after{
        color: <?php echo $atts['primary-color']; ?> !important;
    }

    <?php endif; ?>

    <?php if( !empty($atts['primary-text-color'])) : ?>

    .lever-filters .filter-values a:hover,
    .lever .lever-job-apply {
        color: <?php echo $atts['primary-text-color']; ?> !important;
    }

    .lever .lever-job-apply:hover,
    .lever .lever-job-apply:focus {
        filter: brightness(65%);
    }

    .lever-apply-form input[type='checkbox']:checked:after{
        background-color: <?php echo $atts['primary-text-color']; ?> !important;
    }

    <?php endif; ?>
</style>