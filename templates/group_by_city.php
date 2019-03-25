<?php
/**
 * Group by City template
 *
 * @package WP Lever
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>

<ul class="lever <?php echo $expandable ? "expandable" :  "" ?>">
    <?php foreach ( $lever_jobs as $city => $offers ) : ?>
        <h3><?php echo esc_html( $city ); ?></h3>
        <?php foreach ( $offers as $offer ) : ?>
            <?php include 'job-loop.php'; ?>
        <?php endforeach; ?>
    <?php endforeach; ?>
</ul>
