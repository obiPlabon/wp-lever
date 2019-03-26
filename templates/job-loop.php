<?php

?>
<li class="lever-job" id="<?php echo esc_attr( $offer->id ); ?>">
    <h2 class="lever-job-title" ><?php echo esc_html( $offer->text ); ?></h2>
    <a href="<?php echo esc_url( $offer->hostedUrl ); ?>" target="_blank" class="lever-job-apply qbutton green small"><?php esc_html_e( 'Apply', 'wp-lever' ); ?></a>
    <?php if ($expandable) { ?>
        <div class="job-content panel-collapse collapse in">
            <?php echo $offer->description; ?>
        </div>
    <?php } ?>
</li>