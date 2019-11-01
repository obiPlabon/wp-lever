<li class="application-question custom-question">
    <label for="<?php echo $field->id; ?>"><?php
		echo $field->text;
		if ( $field->required ) {
			?> <span class="required">✱</span><?php
		}
		?></label>
    <div class="field">
        <div class="application-dropdown">
            <select <?php echo $field->required ? "required='required'" : "" ?>
                    id="<?php echo $field->id; ?>"
                    name="lever_cards[<?php echo $additionalCardsTemplate->id; ?>][field<?php echo $i; ?>]">
                <option value="">Select...</option>
				<?php
				foreach ( $field->options as $option ) {
					echo "<option>" . $option->text . "</option>";
				}
				?>
            </select>
        </div>
    </div>
</li>