/*<<$classname_detalle|capitalize>>*/
$this->_AddMapping('<<$classname_detalle|capitalize>>__operarBD', '<<$classname_detalle|capitalize>>');
$this->_AddForward('<<$classname_detalle|capitalize>>__operarBD', 'gvHidraSuccess', 'index.php?view=views/<<$nombreModulo|capitalize>>/p_<<$classname_maestro|capitalize>>.php&panel=editar');
$this->_AddForward('<<$classname_detalle|capitalize>>__operarBD', 'gvHidraError', 'index.php?view=views/<<$nombreModulo|capitalize>>/p_<<$classname_maestro|capitalize>>.php');
$this->_AddForward('<<$classname_detalle|capitalize>>__operarBD', 'gvHidraNoData', 'index.php?view=views/<<$nombreModulo|capitalize>>/p_<<$classname_maestro|capitalize>>.php&panel=editar');
