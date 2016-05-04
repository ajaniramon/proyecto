
			/*<<$classname|capitalize>>*/
			$this->_AddMapping('<<$classname|capitalize>>__iniciarVentana', '<<$classname|capitalize>>');
			$this->_AddForward('<<$classname|capitalize>>__iniciarVentana', 'gvHidraSuccess', 'index.php?view=views/<<$nombreModulo|capitalize>>/p_<<$classname|capitalize>>.php&panel=buscar');
			$this->_AddForward('<<$classname|capitalize>>__iniciarVentana', 'gvHidraError', 'index.php?view=views/<<$nombreModulo|capitalize>>/p_<<$classname|capitalize>>.php');

			$this->_AddMapping('<<$classname|capitalize>>__buscar', '<<$classname|capitalize>>');
			$this->_AddForward('<<$classname|capitalize>>__buscar', 'gvHidraSuccess', 'index.php?view=views/<<$nombreModulo|capitalize>>/p_<<$classname|capitalize>>.php&panel=listar');
			$this->_AddForward('<<$classname|capitalize>>__buscar', 'gvHidraError', 'index.php?view=views/<<$nombreModulo|capitalize>>/p_<<$classname|capitalize>>.php');
			$this->_AddForward('<<$classname|capitalize>>__buscar', 'gvHidraNoData', 'index.php?view=views/<<$nombreModulo|capitalize>>/p_<<$classname|capitalize>>.php&panel=listar');
			
			$this->_AddMapping('<<$classname|capitalize>>__borrar', '<<$classname|capitalize>>');
			$this->_AddForward('<<$classname|capitalize>>__borrar', 'gvHidraSuccess', 'index.php?view=views/<<$nombreModulo|capitalize>>/p_<<$classname|capitalize>>.php&panel=listar');
			$this->_AddForward('<<$classname|capitalize>>__borrar', 'gvHidraError', 'index.php?view=views/<<$nombreModulo|capitalize>>/p_<<$classname|capitalize>>.php');
			$this->_AddForward('<<$classname|capitalize>>__borrar', 'gvHidraNoData', 'index.php?view=views/<<$nombreModulo|capitalize>>/p_<<$classname|capitalize>>.php&panel=buscar');
			
			$this->_AddMapping('<<$classname|capitalize>>__cancelarTodo', '<<$classname|capitalize>>');
			$this->_AddForward('<<$classname|capitalize>>__cancelarTodo', 'gvHidraSuccess', 'index.php?view=views/<<$nombreModulo|capitalize>>/p_<<$classname|capitalize>>.php');
			
			$this->_AddMapping('<<$classname|capitalize>>__cancelarEdicion', '<<$classname|capitalize>>');
			$this->_AddForward('<<$classname|capitalize>>__cancelarEdicion', 'gvHidraSuccess', 'index.php?view=views/<<$nombreModulo|capitalize>>/p_<<$classname|capitalize>>.php&panel=listar');
			
			$this->_AddMapping('<<$classname|capitalize>>__editar', '<<$classname|capitalize>>');
			$this->_AddForward('<<$classname|capitalize>>__editar', 'gvHidraSuccess', 'index.php?view=views/<<$nombreModulo|capitalize>>/p_<<$classname|capitalize>>.php&panel=editar');
			$this->_AddForward('<<$classname|capitalize>>__editar', 'gvHidraError', 'index.php?view=views/<<$nombreModulo|capitalize>>/p_<<$classname|capitalize>>.php&panel=listar');
			
			$this->_AddMapping('<<$classname|capitalize>>__nuevo', '<<$classname|capitalize>>');
			$this->_AddForward('<<$classname|capitalize>>__nuevo', 'gvHidraSuccess', 'index.php?view=views/<<$nombreModulo|capitalize>>/p_<<$classname|capitalize>>.php&panel=editar');
			
			$this->_AddMapping('<<$classname|capitalize>>__insertar', '<<$classname|capitalize>>');
			$this->_AddForward('<<$classname|capitalize>>__insertar', 'gvHidraSuccess', 'index.php?view=views/<<$nombreModulo|capitalize>>/p_<<$classname|capitalize>>.php&panel=editar');
			$this->_AddForward('<<$classname|capitalize>>__insertar', 'gvHidraError', 'index.php?view=views/<<$nombreModulo|capitalize>>/p_<<$classname|capitalize>>.php');
			
			$this->_AddMapping('<<$classname|capitalize>>__modificar', '<<$classname|capitalize>>');
			$this->_AddForward('<<$classname|capitalize>>__modificar', 'gvHidraSuccess', 'index.php?view=views/<<$nombreModulo|capitalize>>/p_<<$classname|capitalize>>.php&panel=listar');
			$this->_AddForward('<<$classname|capitalize>>__modificar', 'gvHidraError', 'index.php?view=views/<<$nombreModulo|capitalize>>/p_<<$classname|capitalize>>.php&panel=listar');
