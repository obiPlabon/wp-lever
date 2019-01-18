<?php
/**
 * @package WP_Lever
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="lever empty-response">
    <div class="lever-group>">
        <div class="lever-job">
            <div class="lever-job-info">
                <h4 class="lever-job-title">
                    <a target="_blank"><?php esc_html_e( 'There are no available entries.', 'wp-lever' ); ?></a>
                </h4>
            </div>
            <div class="lever-job-action"></div>
        </div>
    </div>
</div>