<?php

function is_valid_mf2_list($input) {
	// Input to this function must be an array
	if(!is_object($input))
		return [false, 'Input was not an object'];

	if(!isset($input->items))
		return [false, 'Input is missing a top-level "items" property'];

	if(!is_array($input->items))
		return [false, 'The "items" property must be an array'];

	// Every item must be valid
	foreach($input->items as $i=>$item) {
		list($valid, $error) = is_valid_mf2_object($item);
		if(!$valid) {
			return [false, 'Item '.$i.' was invalid: '.$error];
		}
	}

	return [true, null];
}

function is_valid_mf2_object($input) {
	// Input to this function must be an array
	if(!is_object($input))
		return [false, 'Input was not an object'];

	// Keys type and properties are required at a minimum and must be arrays
	if(!isset($input->type))
		return [false, 'Item is missing the "type" property'];

	if(!is_array($input->type))
		return [false, 'The "type" property is not an array'];

	if(!isset($input->properties))
		return [false, 'Item is missing the "properties" property'];

	if(!is_object($input->properties))
		return [false, 'The "properties" property is not an object'];

	// Every value of type must be a string beginning with h-
	foreach($input->type as $type) {
		if(!is_string($type) || substr($type, 0, 2) != 'h-')
			return [false, 'Every type must be an h-* value, got: "'.$type.'"'];
	}

	foreach($input->properties as $key=>$property) {
		// Every property must be an array
		if(!is_array($property))
			return [false, 'One of the values of "'.$key.'" is not an array'];

		// If a value of a property is not a string, it must be a valid mf2 object
		foreach($property as $k=>$val) {
			// e-* get parsed as objects
			if(is_object($val)) {
				if(!property_exists($val, 'value'))
					return [false, 'Object '.$k.' is missing the "value" property'];

				if(!property_exists($val, 'html'))
					return [false, 'Object '.$k.' is missing the "html" property'];
			} else if(!is_string($val)) {
				if(is_numeric($val))
					return [false, 'One of the values of "'.$key.'" is a number instead of a string'];
				list($valid, $error) = is_valid_mf2_object($val);
				if($error)
					return [false, 'One of the values of "'.$key.'" is not a valid mf2 object'];
			}
		}
	}

	if(isset($input->children)) {
		if(!is_array($input->children))
			return [false, 'The "children" property must be an array'];

		foreach($input->children as $child) {
			list($valid, $error) = is_valid_mf2_object($child);
			if(!$valid) {
				return [false, 'One of the child objects was not valid: '.$error];
			}
		}
	}

	return [true, null];
}

