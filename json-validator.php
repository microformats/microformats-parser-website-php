<?php

function is_valid_mf2_list($input, $level=0) {
	$indent = str_repeat("      ",$level);

	// Input to this function must be an array
	if(!is_object($input))
		return [false, $indent.'Input was not an object'];

	if(!isset($input->items))
		return [false, $indent.'Input is missing a top-level "items" property'];

	if(!is_array($input->items))
		return [false, $indent.'The "items" property must be an array'];

	// Every item must be valid
	foreach($input->items as $i=>$item) {
		list($valid, $error) = is_valid_mf2_object($item, $level+1);
		if(!$valid) {
			return [false, $indent.'Item '.$i.' was invalid:'."\n".$error];
		}
	}

	return [true, null];
}

function is_valid_mf2_object($input, $level=0) {
	$indent = str_repeat("      ",$level);

	// Input to this function must be an array
	if(!is_object($input))
		return [false, $indent.'Input was not an object'];

	// Keys type and properties are required at a minimum and must be arrays
	if(!isset($input->type))
		return [false, $indent.'Item is missing the "type" property'];

	if(!is_array($input->type))
		return [false, $indent.'The "type" property is not an array'];

	if(!isset($input->properties))
		return [false, $indent.'Item is missing the "properties" property'];

	if(!is_object($input->properties))
		return [false, $indent.'The "properties" property is not an object'];

	// Every value of type must be a string beginning with h-
	foreach($input->type as $type) {
		if(!is_string($type) || substr($type, 0, 2) != 'h-')
			return [false, $indent.'Every type must be an h-* value, got: "'.$type.'"'];
	}

	foreach($input->properties as $key=>$property) {
		// Every property must be an array
		if(!is_array($property))
			return [false, $indent.'One of the values of "'.$key.'" is not an array'];

		// If a value of a property is not a string, it must be a valid mf2 object
		foreach($property as $k=>$val) {
			if(is_object($val)) {
				// Try to detect e- parsed objects
				if(property_exists($val, 'value') && !property_exists($val, 'html')) 
					return [false, $indent.'One of the values of '.$key.' is missing the "html" property'];

				if(property_exists($val, 'html') && !property_exists($val, 'value'))
					return [false, $indent.'One of the values of '.$key.' is missing the "value" property'];

				// Otherwise this must be a nested object
				list($valid, $error) = is_valid_mf2_object($val, $level+1);
				if(!$valid)
					return [false, $indent.'One of the values of "'.$key.'" is not a valid mf2 object:'."\n".$error];

			} else if(!is_string($val)) {
				if(is_numeric($val))
					return [false, $indent.'One of the values of "'.$key.'" is a number instead of a string'];

				list($valid, $error) = is_valid_mf2_object($val, $level+1);
				if($error)
					return [false, $indent.'One of the values of "'.$key.'" is not a valid mf2 object'."\n"];
			}
		}
	}

	if(isset($input->children)) {
		if(!is_array($input->children))
			return [false, $indent.'The "children" property must be an array'];

		foreach($input->children as $child) {
			list($valid, $error) = is_valid_mf2_object($child, $level+1);
			if(!$valid) {
				return [false, $indent.'One of the child objects was not valid:'."\n".$error."\n"];
			}
		}
	}

	return [true, null];
}

