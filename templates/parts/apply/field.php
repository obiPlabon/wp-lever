<?php

switch ( $field->type ) {
	case "text":
		include( "text_field.php" );
		break;
	case "dropdown":
		include( "dropdown_field.php" );
		break;
	case "file-upload":
		include( "file_upload_field.php" );
		break;
}