<li class="application-question custom-question">
    <label for="<?php echo $field->id; ?>"><?php
        echo $field->text;
        if ($field->required){
            ?> <span class="required">✱</span><?php
        }
    ?></label>
    <div class="field">
        <input <?php echo $field ->required?"required='required'":""?> id="<?php echo $field->id; ?>" type="text" placeholder="Type your response" name="lever_cards[<?php echo $additionalCardsTemplate->id; ?>][field<?php echo $i; ?>]">
    </div>
</li>