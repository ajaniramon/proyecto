<?php

# a map of the database field types to their java field types

$field_types_map = array( 
                          //'blob'    => 'Object' ,
                          'date'    => 'gvHidraDate',
                          'integer' => 'gvHidraInteger',
						  'decimal' => 'gvHidraFloat',
						  'number' => 'gvHidraFloat',
						  'serial' => 'gvHidraInteger',
						  'float' => 'gvHidraFloat',
						  'double' => 'gvHidraFloat',
                          'text'    => 'gvHidraString',
                          'timestamp' => 'gvHidraDatetime'
);

?>

