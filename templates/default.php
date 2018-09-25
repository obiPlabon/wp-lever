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

<ul class="lever">
    <?php foreach ( $lever_jobs as $lever_job ) : ?>
    <li class="lever-job" id="<?php echo esc_attr( $lever_job->id ); ?>">
        <h2 class="lever-job-title"><?php echo esc_html( $lever_job->text ); ?></h2>
        <a href="<?php echo esc_url( $lever_job->hostedUrl ); ?>" target="_blank" class="lever-job-apply"><?php esc_html_e( 'Apply', 'wp-lever' ); ?></a>
    </li>
    <?php endforeach; ?>
</ul>