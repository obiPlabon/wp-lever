<div class="lever">
    <div class="lever-landing-page">
		<?php
		echo get_option( 'success_message', '<h3 class="default-msg">Your application has successfully been sent.</h3>' );
		?>
    </div>
</div>

<?php
include 'parts/style-overwrites.php';