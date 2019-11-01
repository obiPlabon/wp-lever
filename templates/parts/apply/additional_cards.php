<?php

$additionalCardsTemplateJson = get_option( 'additional_cards_template', '' );
if ( $additionalCardsTemplateJson === '' ) {
	return;
}

$additionalCardsTemplate = json_decode( $additionalCardsTemplateJson );

if ( $additionalCardsTemplate === null ) {
	return;
}
/*
echo "<pre>";
var_dump( $additionalCardsTemplate );
echo "</pre>";
*/
?>
<div class="lever-apply-section">
    <h4><?php echo $additionalCardsTemplate->text; ?></h4>
    <input type="hidden" value='<?php echo addcslashes( $additionalCardsTemplateJson, "'" ); ?>'
           name="cards[<?php echo $additionalCardsTemplate->id; ?>][baseTemplate]"/>

    <ul>
		<?php
		$i = 0;
		foreach ( $additionalCardsTemplate->fields as $field ) {
			include( "field.php" );
			$i ++;
		}
		?>
    </ul>
</div>