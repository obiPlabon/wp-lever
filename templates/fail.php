<div class="lever">
    <div class="lever-landing-page">
		<?php
		echo get_option( 'fail_message', '<h3 class="default-msg">Failed to send the application please try again later.</h3>' );
		?>

        <div class="lever-job-action">
            <a href="<?php  echo $this->build_apply_url( $job_id );?>" class="lever-job-apply"><?php esc_html_e( 'Back to Apply Page', 'wp-lever' ); ?></a>
        </div>
    </div>
</div>

<?php
include 'parts/style-overwrites.php';