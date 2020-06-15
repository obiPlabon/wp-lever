<?php
/**
 * @package WP_Lever
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
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
