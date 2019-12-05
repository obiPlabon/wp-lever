<div class="lever">
    <div class="lever-job-page" id="<?php echo esc_attr( $job->get_id() ); ?>">
        <div class="lever-job-header lever-job">
            <div class="lever-job-info">
                <h4 class="lever-job-title">
					<?php echo esc_html( $job->get_title() ); ?>
                </h4>
                <div>
                    <ul class="lever-categories">
						<?php
						$location   = $job->get_categories()->get_location();
						$department = $job->get_categories()->get_department();
						$team       = $job->get_categories()->get_team();
						$commitment = $job->get_categories()->get_commitment();
						?>
						<?php if ( ! empty( $location ) ): ?>
                            <li><?php echo esc_html( $location ); ?></li>
						<?php endif; ?>
						<?php if ( ! empty( $department ) ): ?>
                            <li><?php echo esc_html( $department ); ?></li>
						<?php endif; ?>
						<?php if ( ! empty( $team ) ): ?>
                            <li><?php echo esc_html( $team ); ?></li>
						<?php endif; ?>
						<?php if ( ! empty( $commitment ) ): ?>
                            <li><?php echo esc_html( $commitment ); ?></li>
						<?php endif; ?>
                    </ul>
                </div>
            </div>
            <div class="lever-job-action">
                <a href="<?php echo $this->build_apply_url( $job->get_id() ); ?>"
                   class="lever-job-apply"><?php esc_html_e( 'Apply For this job', 'wp-lever' ); ?></a>
            </div>
        </div>
        <div class="lever-job-content">
            <div class="lever-job-section">
				<?php
				echo $job->get_description()->get_formatted();
				?>
            </div>
            <div class="lever-job-section lever-job-list">
				<?php
				foreach ( $job->get_lists() as $list ) {
					?>
                    <h5><?php echo $list->get_title(); ?></h5>
                    <div><?php echo $list->get_content(); ?></div>
					<?php
				}
				?>
            </div>
            <div class="lever-job-section">
				<?php echo $job->get_additional()->get_formatted(); ?>
            </div>
        </div>
        <div class="lever-job-action text-center">
            <a href="<?php echo $this->build_apply_url( $job->get_id() ); ?>"
               class="lever-job-apply action-lg"><?php esc_html_e( 'Apply For this job', 'wp-lever' ); ?></a>
        </div>
    </div>
</div>

<?php
include 'parts/style-overwrites.php';