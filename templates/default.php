<?php
/**
 * Default template
 *
 * @package WP_Lever
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<?php if ( ! empty( $filters ) ) : ?>
    <a id="filtered"></a>
    <div class="lever-filters">
        <span class="filter-by-label">Filter by:</span>
		<?php foreach ( $filters as $filter => $filter_values ) : ?>
            <div class="filter">
                <div class="filter-type"><?php echo isset( $active_filters[ $filter ] ) ? $active_filters[ $filter ] : $filter; ?></div>
                <div class="filter-values">
                    <div class="filter-value<?php echo ! isset( $active_filters[ $filter ] ) || $active_filters[ $filter ] == '' ? ' active' : ''; ?>">
                        <a href="<?php echo $this->build_filter_url( $filter, '' ); ?>#filtered"><?php esc_html_e( 'All', 'wp-lever' ); ?></a>
                    </div>
					<?php foreach ( $filter_values as $filter_value ) : ?>
                        <div class="filter-value <?php echo isset( $active_filters[ $filter ] ) && $active_filters[ $filter ] == $filter_value ? ' active' : ''; ?>">
                            <a href="<?php echo $this->build_filter_url( $filter, $filter_value ); ?>#filtered"><?php echo $filter_value; ?></a>
                        </div>
					<?php endforeach; ?>
                </div>
            </div>
		<?php endforeach; ?>
    </div>
<?php endif; ?>

<div class="lever">
	<?php
	/**
	 * @var \WP_Lever\Data_Transfer_Objects\Job_Posting[] $jobs
	 */
	foreach ( $jobs_by_group as $group => $jobs ) : ?>
        <div class="lever-group group-<?php echo str_replace( ' ', '-', strtolower( $group ) ); ?>">
            <h3 class="lever-group-title"><?php echo $group; ?></h3>
            <div class="lever-horizontal-line"></div>
			<?php foreach ( $jobs as $job ) : ?>
                <div class="lever-job" id="<?php echo esc_attr( $job->get_id() ); ?>">
                    <div class="lever-job-info">
                        <h4 class="lever-job-title">
                            <a href="<?php echo esc_url( $job->get_description_url() ); ?>"
                               target="_blank"><?php echo esc_html( $job->get_title() ); ?></a>
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
                        <a href="<?php echo esc_url( $job->get_description_url() ); ?>" target="_blank"
                           class="lever-job-apply"><?php esc_html_e( 'Apply', 'wp-lever' ); ?></a>
                    </div>
                </div>
			<?php endforeach; ?>
        </div>
	<?php endforeach; ?>
</div>