<?php
/**
 * Default template
 *
 * @package WP Lever
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>

<ul class="lever <?php echo $expandable ? "expandable" :  "" ?>">
    <?php foreach ( $lever_jobs as $offer ) : ?>
        <?php include 'job-loop.php'; ?>
    <?php endforeach; ?>
</ul>