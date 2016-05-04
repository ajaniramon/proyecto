<?php
/**
 * Controlador
 *
 * $Revision: 1.2 $
 */
 include 'igep/include/gvHidraMaps.php';


	class ComponentesMap extends gvHidraMaps {
        /**
         *      constructor function
         *      @return void
         */
		function ComponentesMap () {
                
            //Llamamos al constructor del padre. Cargamos la accines genéricas de Igep           	
			parent::gvHidraMaps();				

			$this->_AddMapping('abrirAplicacion', 'AppMainWindow');
			$this->_AddForward('abrirAplicacion', 'gvHidraOpenApp', 'index.php?view=igep/views/aplicacion.php');
			$this->_AddForward('abrirAplicacion', 'gvHidraCloseApp', 'index.php?view=igep/views/gvHidraCloseApp.php');
			
			//.....//	
				
			/*PedidoMaestro - PedidoDetalle*/
			$this->_AddMapping('PedidoMaestro__nuevo', 'PedidoMaestro');
			$this->_AddForward('PedidoMaestro__nuevo', 'gvHidraSuccess', 'index.php?view=views/Pedidos/p_PedidoMaestro.php&panel=listar');

			$this->_AddMapping('PedidoMaestro__operarBD', 'PedidoMaestro');
			$this->_AddForward('PedidoMaestro__operarBD', 'gvHidraSuccess', 'index.php?view=views/Pedidos/p_PedidoMaestro.php&panel=listar');
			$this->_AddForward('PedidoMaestro__operarBD', 'gvHidraError', 'index.php?view=views/Pedidos/p_PedidoMaestro.php');
			$this->_AddForward('PedidoMaestro__operarBD', 'gvHidraNoData', 'index.php?view=views/Pedidos/p_PedidoMaestro.php&panel=buscar');

			$this->_AddMapping('PedidoMaestro__iniciarVentana', 'PedidoMaestro');
			$this->_AddForward('PedidoMaestro__iniciarVentana', 'gvHidraSuccess', 'index.php?view=views/Pedidos/p_PedidoMaestro.php&panel=buscar');
			$this->_AddForward('PedidoMaestro__iniciarVentana', 'gvHidraError', 'index.php?view=views/Pedidos/p_PedidoMaestro.php&panel=buscar');

			$this->_AddMapping('PedidoMaestro__buscar', 'PedidoMaestro');
			$this->_AddForward('PedidoMaestro__buscar', 'gvHidraSuccess', 'index.php?view=views/Pedidos/p_PedidoMaestro.php&panel=listar');
			$this->_AddForward('PedidoMaestro__buscar', 'gvHidraError', 'index.php?view=views/Pedidos/p_PedidoMaestro.php&panel=listar');
			$this->_AddForward('PedidoMaestro__buscar', 'gvHidraNoData', 'index.php?view=views/Pedidos/p_PedidoMaestro.php&panel=buscar');

			$this->_AddMapping('PedidoMaestro__cancelarTodo', 'PedidoMaestro');
			$this->_AddForward('PedidoMaestro__cancelarTodo', 'gvHidraSuccess', 'index.php?view=views/Pedidos/p_PedidoMaestro.php');

			$this->_AddMapping('PedidoMaestro__recargar', 'PedidoMaestro');
			$this->_AddForward('PedidoMaestro__recargar', 'gvHidraSuccess', 'index.php?view=views/Pedidos/p_PedidoMaestro.php&panel=listar');
			$this->_AddForward('PedidoMaestro__recargar', 'gvHidraError', 'index.php?view=views/Pedidos/p_PedidoMaestro.php&panel=listar');

			$this->_AddMapping('PedidoDetalle__operarBD', 'PedidoDetalle');
			$this->_AddForward('PedidoDetalle__operarBD', 'gvHidraSuccess', 'index.php?view=views/Pedidos/p_PedidoMaestro.php&panel=listar');
			$this->_AddForward('PedidoDetalle__operarBD', 'gvHidraError', 'index.php?view=views/Pedidos/p_PedidoMaestro.php');
			/*Cliente*/
			$this->_AddMapping('Cliente__iniciarVentana', 'Cliente');
			$this->_AddForward('Cliente__iniciarVentana', 'gvHidraSuccess', 'index.php?view=views/Clientes/p_Cliente.php&panel=buscar');
			$this->_AddForward('Cliente__iniciarVentana', 'gvHidraError', 'index.php?view=views/Clientes/p_Cliente.php');

			$this->_AddMapping('Cliente__buscar', 'Cliente');
			$this->_AddForward('Cliente__buscar', 'gvHidraSuccess', 'index.php?view=views/Clientes/p_Cliente.php&panel=listar');
			$this->_AddForward('Cliente__buscar', 'gvHidraError', 'index.php?view=views/Clientes/p_Cliente.php');
			$this->_AddForward('Cliente__buscar', 'gvHidraNoData', 'index.php?view=views/Clientes/p_Cliente.php&panel=listar');
			
			$this->_AddMapping('Cliente__operarBD', 'Cliente');
			$this->_AddForward('Cliente__operarBD', 'gvHidraSuccess', 'index.php?view=views/Clientes/p_Cliente.php&panel=listar');
			$this->_AddForward('Cliente__operarBD', 'gvHidraError', 'index.php?view=views/Clientes/p_Cliente.php');
			
			$this->_AddMapping('Cliente__borrar', 'Cliente');
			$this->_AddForward('Cliente__borrar', 'gvHidraSuccess', 'index.php?view=views/Clientes/p_Cliente.php&panel=listar');
			$this->_AddForward('Cliente__borrar', 'gvHidraError', 'index.php?view=views/Clientes/p_Cliente.php');
			$this->_AddForward('Cliente__borrar', 'gvHidraNoData', 'index.php?view=views/Clientes/p_Cliente.php&panel=buscar');
			
			$this->_AddMapping('Cliente__cancelarTodo', 'Cliente');
			$this->_AddForward('Cliente__cancelarTodo', 'gvHidraSuccess', 'index.php?view=views/Clientes/p_Cliente.php');
			
			$this->_AddMapping('Cliente__cancelarEdicion', 'Cliente');
			$this->_AddForward('Cliente__cancelarEdicion', 'gvHidraSuccess', 'index.php?view=views/Clientes/p_Cliente.php&panel=listar');
			
			$this->_AddMapping('Cliente__editar', 'Cliente');
			$this->_AddForward('Cliente__editar', 'gvHidraSuccess', 'index.php?view=views/Clientes/p_Cliente.php&panel=editar');
			$this->_AddForward('Cliente__editar', 'gvHidraError', 'index.php?view=views/Clientes/p_Cliente.php&panel=listar');
			
			$this->_AddMapping('Cliente__nuevo', 'Cliente');
			$this->_AddForward('Cliente__nuevo', 'gvHidraSuccess', 'index.php?view=views/Clientes/p_Cliente.php&panel=listar');				
			
			$this->_AddMapping('Cliente__insertar', 'Cliente');
			$this->_AddForward('Cliente__insertar', 'gvHidraSuccess', 'index.php?view=views/Clientes/p_Cliente.php&panel=buscar');
			$this->_AddForward('Cliente__insertar', 'gvHidraError', 'index.php?view=views/Clientes/p_Cliente.php');
			
			$this->_AddMapping('Cliente__modificar', 'Cliente');
			$this->_AddForward('Cliente__modificar', 'gvHidraSuccess', 'index.php?view=views/Clientes/p_Cliente.php&panel=listar');
			$this->_AddForward('Cliente__modificar', 'gvHidraError', 'index.php?view=views/Clientes/p_Cliente.php');
			/*Articulo*/
			$this->_AddMapping('Articulo__iniciarVentana', 'Articulo');
			$this->_AddForward('Articulo__iniciarVentana', 'gvHidraSuccess', 'index.php?view=views/Articulos/p_Articulo.php&panel=buscar');
			$this->_AddForward('Articulo__iniciarVentana', 'gvHidraError', 'index.php?view=views/Articulos/p_Articulo.php');

			$this->_AddMapping('Articulo__buscar', 'Articulo');
			$this->_AddForward('Articulo__buscar', 'gvHidraSuccess', 'index.php?view=views/Articulos/p_Articulo.php&panel=listar');
			$this->_AddForward('Articulo__buscar', 'gvHidraError', 'index.php?view=views/Articulos/p_Articulo.php');
			$this->_AddForward('Articulo__buscar', 'gvHidraNoData', 'index.php?view=views/Articulos/p_Articulo.php&panel=listar');
			
			$this->_AddMapping('Articulo__borrar', 'Articulo');
			$this->_AddForward('Articulo__borrar', 'gvHidraSuccess', 'index.php?view=views/Articulos/p_Articulo.php&panel=listar');
			$this->_AddForward('Articulo__borrar', 'gvHidraError', 'index.php?view=views/Articulos/p_Articulo.php');
			$this->_AddForward('Articulo__borrar', 'gvHidraNoData', 'index.php?view=views/Articulos/p_Articulo.php&panel=buscar');
			
			$this->_AddMapping('Articulo__cancelarTodo', 'Articulo');
			$this->_AddForward('Articulo__cancelarTodo', 'gvHidraSuccess', 'index.php?view=views/Articulos/p_Articulo.php');
			
			$this->_AddMapping('Articulo__cancelarEdicion', 'Articulo');
			$this->_AddForward('Articulo__cancelarEdicion', 'gvHidraSuccess', 'index.php?view=views/Articulos/p_Articulo.php&panel=listar');
			
			$this->_AddMapping('Articulo__editar', 'Articulo');
			$this->_AddForward('Articulo__editar', 'gvHidraSuccess', 'index.php?view=views/Articulos/p_Articulo.php&panel=editar');
			$this->_AddForward('Articulo__editar', 'gvHidraError', 'index.php?view=views/Articulos/p_Articulo.php&panel=listar');
			
			$this->_AddMapping('Articulo__nuevo', 'Articulo');
			$this->_AddForward('Articulo__nuevo', 'gvHidraSuccess', 'index.php?view=views/Articulos/p_Articulo.php&panel=editar');
			
			$this->_AddMapping('Articulo__insertar', 'Articulo');
			$this->_AddForward('Articulo__insertar', 'gvHidraSuccess', 'index.php?view=views/Articulos/p_Articulo.php&panel=editar');
			$this->_AddForward('Articulo__insertar', 'gvHidraError', 'index.php?view=views/Articulos/p_Articulo.php');
			
			$this->_AddMapping('Articulo__modificar', 'Articulo');
			$this->_AddForward('Articulo__modificar', 'gvHidraSuccess', 'index.php?view=views/Articulos/p_Articulo.php&panel=listar');
			$this->_AddForward('Articulo__modificar', 'gvHidraError', 'index.php?view=views/Articulos/p_Articulo.php&panel=listar');
			/*Categoria*/
			$this->_AddMapping('Categoria__iniciarVentana', 'Categoria');
			$this->_AddForward('Categoria__iniciarVentana', 'gvHidraSuccess', 'index.php?view=views/Categorias/p_Categoria.php&panel=buscar');
			$this->_AddForward('Categoria__iniciarVentana', 'gvHidraError', 'index.php?view=views/Categorias/p_Categoria.php');

			$this->_AddMapping('Categoria__buscar', 'Categoria');
			$this->_AddForward('Categoria__buscar', 'gvHidraSuccess', 'index.php?view=views/Categorias/p_Categoria.php&panel=listar');
			$this->_AddForward('Categoria__buscar', 'gvHidraError', 'index.php?view=views/Categorias/p_Categoria.php');
			$this->_AddForward('Categoria__buscar', 'gvHidraNoData', 'index.php?view=views/Categorias/p_Categoria.php&panel=listar');
			
			$this->_AddMapping('Categoria__borrar', 'Categoria');
			$this->_AddForward('Categoria__borrar', 'gvHidraSuccess', 'index.php?view=views/Categorias/p_Categoria.php&panel=listar');
			$this->_AddForward('Categoria__borrar', 'gvHidraError', 'index.php?view=views/Categorias/p_Categoria.php');
			$this->_AddForward('Categoria__borrar', 'gvHidraNoData', 'index.php?view=views/Categorias/p_Categoria.php&panel=buscar');
			
			$this->_AddMapping('Categoria__cancelarTodo', 'Categoria');
			$this->_AddForward('Categoria__cancelarTodo', 'gvHidraSuccess', 'index.php?view=views/Categorias/p_Categoria.php');
			
			$this->_AddMapping('Categoria__cancelarEdicion', 'Categoria');
			$this->_AddForward('Categoria__cancelarEdicion', 'gvHidraSuccess', 'index.php?view=views/Categorias/p_Categoria.php&panel=listar');
			
			$this->_AddMapping('Categoria__editar', 'Categoria');
			$this->_AddForward('Categoria__editar', 'gvHidraSuccess', 'index.php?view=views/Categorias/p_Categoria.php&panel=editar');
			$this->_AddForward('Categoria__editar', 'gvHidraError', 'index.php?view=views/Categorias/p_Categoria.php&panel=listar');
			
			$this->_AddMapping('Categoria__nuevo', 'Categoria');
			$this->_AddForward('Categoria__nuevo', 'gvHidraSuccess', 'index.php?view=views/Categorias/p_Categoria.php&panel=editar');
			
			$this->_AddMapping('Categoria__insertar', 'Categoria');
			$this->_AddForward('Categoria__insertar', 'gvHidraSuccess', 'index.php?view=views/Categorias/p_Categoria.php&panel=editar');
			$this->_AddForward('Categoria__insertar', 'gvHidraError', 'index.php?view=views/Categorias/p_Categoria.php');
			
			$this->_AddMapping('Categoria__modificar', 'Categoria');
			$this->_AddForward('Categoria__modificar', 'gvHidraSuccess', 'index.php?view=views/Categorias/p_Categoria.php&panel=listar');
			$this->_AddForward('Categoria__modificar', 'gvHidraError', 'index.php?view=views/Categorias/p_Categoria.php&panel=listar');}}?>