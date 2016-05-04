<?php /* Smarty version 2.6.14, created on 2016-04-06 16:27:28
         compiled from patrones/P2M2FIL-LISM1LIS/actionMaestro.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'capitalize', 'patrones/P2M2FIL-LISM1LIS/actionMaestro.tpl', 35, false),array('modifier', 'explode', 'patrones/P2M2FIL-LISM1LIS/actionMaestro.tpl', 195, false),)), $this); ?>
<?php echo '<?php'; ?>

/* gvHIDRA. Herramienta Integral de Desarrollo Rápido de Aplicaciones de la Generalitat Valenciana
*
* Copyright (C) 2006 Generalitat Valenciana.
*
* This program is free software; you can redistribute it and/or
* modify it under the terms of the GNU General Public License
* as published by the Free Software Foundation; either version 2
* of the License, or (at your option) any later version.
*
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with this program; if not, write to the Free Software
* Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307,USA.
*
* For more information, contact:
*
*  Generalitat Valenciana
*  Conselleria d'Infraestructures i Transport
*  Av. Blasco Ibáñez, 50
*  46010 VALENCIA
*  SPAIN
*  +34 96386 24 83
*  gvhidra@gva.es
*  www.gvhidra.org
*
*/

/**
* Clase Manejadora <?php echo ((is_array($_tmp=$this->_tpl_vars['classname_maestro'])) ? $this->_run_mod_handler('capitalize', true, $_tmp) : smarty_modifier_capitalize($_tmp)); ?>

* 
* Creada con Genaro: generador de código de gvHIDRA
* 
* @autor genaro
* @version 2.0
* @license http://opensource.org/licenses/gpl-2.0.php GNU Public License v.2
*/

class <?php echo ((is_array($_tmp=$this->_tpl_vars['classname_maestro'])) ? $this->_run_mod_handler('capitalize', true, $_tmp) : smarty_modifier_capitalize($_tmp)); ?>
 extends gvHidraForm_DB
{
	public function __construct() {

		//Recogemos dsn de conexion
		$conf = ConfigFramework::getConfig();
		$g_dsn = $conf->getDSN('<?php echo $this->_tpl_vars['dsn']; ?>
');

		$nombreTablas= array('<?php echo $this->_tpl_vars['tablename_maestro']; ?>
');
		parent::__construct($g_dsn, $nombreTablas);

		
		/************************ QUERYs ************************/
		
		//Consulta del modo de trabajo LIS
		$str_select = "SELECT <?php unset($this->_sections['select']);
$this->_sections['select']['name'] = 'select';
$this->_sections['select']['loop'] = is_array($_loop=$this->_tpl_vars['fields_maestro']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['select']['show'] = true;
$this->_sections['select']['max'] = $this->_sections['select']['loop'];
$this->_sections['select']['step'] = 1;
$this->_sections['select']['start'] = $this->_sections['select']['step'] > 0 ? 0 : $this->_sections['select']['loop']-1;
if ($this->_sections['select']['show']) {
    $this->_sections['select']['total'] = $this->_sections['select']['loop'];
    if ($this->_sections['select']['total'] == 0)
        $this->_sections['select']['show'] = false;
} else
    $this->_sections['select']['total'] = 0;
if ($this->_sections['select']['show']):

            for ($this->_sections['select']['index'] = $this->_sections['select']['start'], $this->_sections['select']['iteration'] = 1;
                 $this->_sections['select']['iteration'] <= $this->_sections['select']['total'];
                 $this->_sections['select']['index'] += $this->_sections['select']['step'], $this->_sections['select']['iteration']++):
$this->_sections['select']['rownum'] = $this->_sections['select']['iteration'];
$this->_sections['select']['index_prev'] = $this->_sections['select']['index'] - $this->_sections['select']['step'];
$this->_sections['select']['index_next'] = $this->_sections['select']['index'] + $this->_sections['select']['step'];
$this->_sections['select']['first']      = ($this->_sections['select']['iteration'] == 1);
$this->_sections['select']['last']       = ($this->_sections['select']['iteration'] == $this->_sections['select']['total']);
 echo $this->_tpl_vars['fields_maestro'][$this->_sections['select']['index']]; ?>
 as \"lis_<?php echo $this->_tpl_vars['fields_maestro'][$this->_sections['select']['index']]; ?>
\"<?php if ($this->_sections['select']['last']):  else: ?>, <?php endif;  endfor; endif; ?> FROM <?php echo $this->_tpl_vars['tablename_maestro']; ?>
";
		$this->setSelectForSearchQuery($str_select);

		//Where del modo de trabajo LIS
		//$str_where = "";
		//$this->setWhereForSearchQuery($str_where);

		//Order del modo de trabajo LIS
		$this->setOrderByForSearchQuery('1');

		/************************ END QUERYs ************************/

		/************************ MATCHINGs ************************/

		//Seccion de matching: asociacion campos TPL y campos BD

		//Modo de trabajo FIL
<?php unset($this->_sections['fil']);
$this->_sections['fil']['name'] = 'fil';
$this->_sections['fil']['loop'] = is_array($_loop=$this->_tpl_vars['fields_maestro']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['fil']['show'] = true;
$this->_sections['fil']['max'] = $this->_sections['fil']['loop'];
$this->_sections['fil']['step'] = 1;
$this->_sections['fil']['start'] = $this->_sections['fil']['step'] > 0 ? 0 : $this->_sections['fil']['loop']-1;
if ($this->_sections['fil']['show']) {
    $this->_sections['fil']['total'] = $this->_sections['fil']['loop'];
    if ($this->_sections['fil']['total'] == 0)
        $this->_sections['fil']['show'] = false;
} else
    $this->_sections['fil']['total'] = 0;
if ($this->_sections['fil']['show']):

            for ($this->_sections['fil']['index'] = $this->_sections['fil']['start'], $this->_sections['fil']['iteration'] = 1;
                 $this->_sections['fil']['iteration'] <= $this->_sections['fil']['total'];
                 $this->_sections['fil']['index'] += $this->_sections['fil']['step'], $this->_sections['fil']['iteration']++):
$this->_sections['fil']['rownum'] = $this->_sections['fil']['iteration'];
$this->_sections['fil']['index_prev'] = $this->_sections['fil']['index'] - $this->_sections['fil']['step'];
$this->_sections['fil']['index_next'] = $this->_sections['fil']['index'] + $this->_sections['fil']['step'];
$this->_sections['fil']['first']      = ($this->_sections['fil']['iteration'] == 1);
$this->_sections['fil']['last']       = ($this->_sections['fil']['iteration'] == $this->_sections['fil']['total']);
?>
		$this->addMatching("fil_<?php echo $this->_tpl_vars['fields_maestro'][$this->_sections['fil']['index']]; ?>
", "<?php echo $this->_tpl_vars['fields_maestro'][$this->_sections['fil']['index']]; ?>
", "<?php echo $this->_tpl_vars['tablename_maestro']; ?>
");
<?php endfor; endif; ?>

		//Modo de trabajo LIS
<?php unset($this->_sections['lis']);
$this->_sections['lis']['name'] = 'lis';
$this->_sections['lis']['loop'] = is_array($_loop=$this->_tpl_vars['fields_maestro']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['lis']['show'] = true;
$this->_sections['lis']['max'] = $this->_sections['lis']['loop'];
$this->_sections['lis']['step'] = 1;
$this->_sections['lis']['start'] = $this->_sections['lis']['step'] > 0 ? 0 : $this->_sections['lis']['loop']-1;
if ($this->_sections['lis']['show']) {
    $this->_sections['lis']['total'] = $this->_sections['lis']['loop'];
    if ($this->_sections['lis']['total'] == 0)
        $this->_sections['lis']['show'] = false;
} else
    $this->_sections['lis']['total'] = 0;
if ($this->_sections['lis']['show']):

            for ($this->_sections['lis']['index'] = $this->_sections['lis']['start'], $this->_sections['lis']['iteration'] = 1;
                 $this->_sections['lis']['iteration'] <= $this->_sections['lis']['total'];
                 $this->_sections['lis']['index'] += $this->_sections['lis']['step'], $this->_sections['lis']['iteration']++):
$this->_sections['lis']['rownum'] = $this->_sections['lis']['iteration'];
$this->_sections['lis']['index_prev'] = $this->_sections['lis']['index'] - $this->_sections['lis']['step'];
$this->_sections['lis']['index_next'] = $this->_sections['lis']['index'] + $this->_sections['lis']['step'];
$this->_sections['lis']['first']      = ($this->_sections['lis']['iteration'] == 1);
$this->_sections['lis']['last']       = ($this->_sections['lis']['iteration'] == $this->_sections['lis']['total']);
?>
		$this->addMatching("lis_<?php echo $this->_tpl_vars['fields_maestro'][$this->_sections['lis']['index']]; ?>
", "<?php echo $this->_tpl_vars['fields_maestro'][$this->_sections['lis']['index']]; ?>
", "<?php echo $this->_tpl_vars['tablename_maestro']; ?>
");
<?php endfor; endif; ?>

		/************************ END MATCHINGs ************************/


		/************************ TYPEs ************************/

		//Fechas: gvHidraDate type
<?php unset($this->_sections['fecha']);
$this->_sections['fecha']['name'] = 'fecha';
$this->_sections['fecha']['loop'] = is_array($_loop=$this->_tpl_vars['fields_maestro']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['fecha']['show'] = true;
$this->_sections['fecha']['max'] = $this->_sections['fecha']['loop'];
$this->_sections['fecha']['step'] = 1;
$this->_sections['fecha']['start'] = $this->_sections['fecha']['step'] > 0 ? 0 : $this->_sections['fecha']['loop']-1;
if ($this->_sections['fecha']['show']) {
    $this->_sections['fecha']['total'] = $this->_sections['fecha']['loop'];
    if ($this->_sections['fecha']['total'] == 0)
        $this->_sections['fecha']['show'] = false;
} else
    $this->_sections['fecha']['total'] = 0;
if ($this->_sections['fecha']['show']):

            for ($this->_sections['fecha']['index'] = $this->_sections['fecha']['start'], $this->_sections['fecha']['iteration'] = 1;
                 $this->_sections['fecha']['iteration'] <= $this->_sections['fecha']['total'];
                 $this->_sections['fecha']['index'] += $this->_sections['fecha']['step'], $this->_sections['fecha']['iteration']++):
$this->_sections['fecha']['rownum'] = $this->_sections['fecha']['iteration'];
$this->_sections['fecha']['index_prev'] = $this->_sections['fecha']['index'] - $this->_sections['fecha']['step'];
$this->_sections['fecha']['index_next'] = $this->_sections['fecha']['index'] + $this->_sections['fecha']['step'];
$this->_sections['fecha']['first']      = ($this->_sections['fecha']['iteration'] == 1);
$this->_sections['fecha']['last']       = ($this->_sections['fecha']['iteration'] == $this->_sections['fecha']['total']);
 $this->assign('campo', $this->_tpl_vars['fields_maestro'][$this->_sections['fecha']['index']]);  $this->assign('reqVal', $this->_tpl_vars['customFields'][$this->_tpl_vars['campo']]['reqVal']);  $this->assign('calVal_fil', $this->_tpl_vars['customFields'][$this->_tpl_vars['campo']]['calVal']);  $this->assign('calVal_lis', $this->_tpl_vars['customFields'][$this->_tpl_vars['campo']]['calVal']);  if ($this->_tpl_vars['types_maestro'][$this->_sections['fecha']['index']] == 'gvHidraDate'): ?>
		$fecha = new gvHidraDate(false);
<?php if ($this->_tpl_vars['calVal_fil'] == 1): ?>
    	$fecha->setCalendar(true);
<?php else: ?>
    	$fecha->setCalendar(false);
<?php endif; ?>
    	$this->addFieldType('fil_<?php echo $this->_tpl_vars['fields_maestro'][$this->_sections['fecha']['index']]; ?>
',$fecha);
<?php if ($this->_tpl_vars['notnulls_maestro'][$this->_sections['fecha']['index']] == 'true'): ?>
		$fecha = new gvHidraDate(true);
<?php endif;  if ($this->_tpl_vars['calVal_lis'] == 1): ?>
		$fecha->setCalendar(true);
<?php else: ?>
		$fecha->setCalendar(false);
<?php endif;  if ($this->_tpl_vars['reqVal'] == 1): ?>
		$fecha->setRequired(true);
<?php endif; ?>
		$this->addFieldType('lis_<?php echo $this->_tpl_vars['fields_maestro'][$this->_sections['fecha']['index']]; ?>
',$fecha);

<?php endif;  if ($this->_tpl_vars['types_maestro'][$this->_sections['fecha']['index']] == 'gvHidraDatetime'): ?>
		$fecha = new gvHidraDatetime(false);
<?php if ($this->_tpl_vars['calVal_fil'] == 1): ?>
		$fecha->setCalendar(true);
<?php else: ?>
		$fecha->setCalendar(false);
<?php endif; ?>
		$this->addFieldType('fil_<?php echo $this->_tpl_vars['fields_maestro'][$this->_sections['fecha']['index']]; ?>
',$fecha);
<?php if ($this->_tpl_vars['notnulls_maestro'][$this->_sections['fecha']['index']] == 'true'): ?>
		$fecha = new gvHidraDatetime(true);
<?php endif;  if ($this->_tpl_vars['calVal_lis'] == 1): ?>
		$fecha->setCalendar(true);
<?php else: ?>
		$fecha->setCalendar(false);
<?php endif;  if ($this->_tpl_vars['reqVal'] == 1): ?>
		$fecha->setRequired(true);
<?php endif; ?>
		$this->addFieldType('lis_<?php echo $this->_tpl_vars['fields_maestro'][$this->_sections['fecha']['index']]; ?>
',$fecha);

<?php endif;  endfor; endif; ?>

		//Strings: gvHidraString type
<?php unset($this->_sections['string']);
$this->_sections['string']['name'] = 'string';
$this->_sections['string']['loop'] = is_array($_loop=$this->_tpl_vars['fields_maestro']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['string']['show'] = true;
$this->_sections['string']['max'] = $this->_sections['string']['loop'];
$this->_sections['string']['step'] = 1;
$this->_sections['string']['start'] = $this->_sections['string']['step'] > 0 ? 0 : $this->_sections['string']['loop']-1;
if ($this->_sections['string']['show']) {
    $this->_sections['string']['total'] = $this->_sections['string']['loop'];
    if ($this->_sections['string']['total'] == 0)
        $this->_sections['string']['show'] = false;
} else
    $this->_sections['string']['total'] = 0;
if ($this->_sections['string']['show']):

            for ($this->_sections['string']['index'] = $this->_sections['string']['start'], $this->_sections['string']['iteration'] = 1;
                 $this->_sections['string']['iteration'] <= $this->_sections['string']['total'];
                 $this->_sections['string']['index'] += $this->_sections['string']['step'], $this->_sections['string']['iteration']++):
$this->_sections['string']['rownum'] = $this->_sections['string']['iteration'];
$this->_sections['string']['index_prev'] = $this->_sections['string']['index'] - $this->_sections['string']['step'];
$this->_sections['string']['index_next'] = $this->_sections['string']['index'] + $this->_sections['string']['step'];
$this->_sections['string']['first']      = ($this->_sections['string']['iteration'] == 1);
$this->_sections['string']['last']       = ($this->_sections['string']['iteration'] == $this->_sections['string']['total']);
 $this->assign('campo', $this->_tpl_vars['fields_maestro'][$this->_sections['string']['index']]);  $this->assign('mascara', $this->_tpl_vars['customFields'][$this->_tpl_vars['campo']]['maskVal']);  $this->assign('reqVal', $this->_tpl_vars['customFields'][$this->_tpl_vars['campo']]['reqVal']);  if ($this->_tpl_vars['types_maestro'][$this->_sections['string']['index']] == 'gvHidraString'):  if ($this->_tpl_vars['lengths_maestro'][$this->_sections['string']['index']] == ""):  $this->assign('length', '200');  else:  $this->assign('length', $this->_tpl_vars['lengths_maestro'][$this->_sections['string']['index']]);  endif; ?>
		$string = new gvHidraString(false, <?php echo $this->_tpl_vars['length']; ?>
);
<?php if ($this->_tpl_vars['mascara'] != ''): ?>
		$string -> setInputMask('<?php echo $this->_tpl_vars['mascara']; ?>
');
<?php endif; ?>
		$this->addFieldType('fil_<?php echo $this->_tpl_vars['fields_maestro'][$this->_sections['string']['index']]; ?>
',$string);
<?php if ($this->_tpl_vars['notnulls_maestro'][$this->_sections['string']['index']] == 'true'): ?>
		$string = new gvHidraString(false, <?php echo $this->_tpl_vars['length']; ?>
);
<?php if ($this->_tpl_vars['mascara'] != ''): ?>
		$string->setInputMask('<?php echo $this->_tpl_vars['mascara']; ?>
');
<?php endif;  if ($this->_tpl_vars['reqVal'] == 1): ?>
		$string->setRequired(true);
<?php endif;  endif; ?>
		$this->addFieldType('lis_<?php echo $this->_tpl_vars['fields_maestro'][$this->_sections['string']['index']]; ?>
',$string);
		
<?php endif;  endfor; endif; ?>

		//Integers: gvHidraInteger type
<?php unset($this->_sections['int']);
$this->_sections['int']['name'] = 'int';
$this->_sections['int']['loop'] = is_array($_loop=$this->_tpl_vars['fields_maestro']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['int']['show'] = true;
$this->_sections['int']['max'] = $this->_sections['int']['loop'];
$this->_sections['int']['step'] = 1;
$this->_sections['int']['start'] = $this->_sections['int']['step'] > 0 ? 0 : $this->_sections['int']['loop']-1;
if ($this->_sections['int']['show']) {
    $this->_sections['int']['total'] = $this->_sections['int']['loop'];
    if ($this->_sections['int']['total'] == 0)
        $this->_sections['int']['show'] = false;
} else
    $this->_sections['int']['total'] = 0;
if ($this->_sections['int']['show']):

            for ($this->_sections['int']['index'] = $this->_sections['int']['start'], $this->_sections['int']['iteration'] = 1;
                 $this->_sections['int']['iteration'] <= $this->_sections['int']['total'];
                 $this->_sections['int']['index'] += $this->_sections['int']['step'], $this->_sections['int']['iteration']++):
$this->_sections['int']['rownum'] = $this->_sections['int']['iteration'];
$this->_sections['int']['index_prev'] = $this->_sections['int']['index'] - $this->_sections['int']['step'];
$this->_sections['int']['index_next'] = $this->_sections['int']['index'] + $this->_sections['int']['step'];
$this->_sections['int']['first']      = ($this->_sections['int']['iteration'] == 1);
$this->_sections['int']['last']       = ($this->_sections['int']['iteration'] == $this->_sections['int']['total']);
 $this->assign('campo', $this->_tpl_vars['fields_maestro'][$this->_sections['int']['index']]);  $this->assign('reqVal', $this->_tpl_vars['customFields'][$this->_tpl_vars['campo']]['reqVal']);  if ($this->_tpl_vars['types_maestro'][$this->_sections['int']['index']] == 'gvHidraInteger'): ?>
		$int = new gvHidraInteger(false, <?php echo $this->_tpl_vars['lengths_maestro'][$this->_sections['int']['index']]; ?>
);
		$this->addFieldType('fil_<?php echo $this->_tpl_vars['fields_maestro'][$this->_sections['int']['index']]; ?>
',$int);
<?php if ($this->_tpl_vars['notnulls_maestro'][$this->_sections['int']['index']] == 'true'): ?>
		$int = new gvHidraInteger(true, <?php echo $this->_tpl_vars['lengths_maestro'][$this->_sections['int']['index']]; ?>
);
<?php endif;  if ($this->_tpl_vars['reqVal'] == 1): ?>
		$int->setRequired(true);
<?php endif; ?>
		$this->addFieldType('lis_<?php echo $this->_tpl_vars['fields_maestro'][$this->_sections['int']['index']]; ?>
',$int);
		
<?php endif;  endfor; endif; ?>

		//Floats: gvHidraFloat type
<?php unset($this->_sections['float']);
$this->_sections['float']['name'] = 'float';
$this->_sections['float']['loop'] = is_array($_loop=$this->_tpl_vars['fields_maestro']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['float']['show'] = true;
$this->_sections['float']['max'] = $this->_sections['float']['loop'];
$this->_sections['float']['step'] = 1;
$this->_sections['float']['start'] = $this->_sections['float']['step'] > 0 ? 0 : $this->_sections['float']['loop']-1;
if ($this->_sections['float']['show']) {
    $this->_sections['float']['total'] = $this->_sections['float']['loop'];
    if ($this->_sections['float']['total'] == 0)
        $this->_sections['float']['show'] = false;
} else
    $this->_sections['float']['total'] = 0;
if ($this->_sections['float']['show']):

            for ($this->_sections['float']['index'] = $this->_sections['float']['start'], $this->_sections['float']['iteration'] = 1;
                 $this->_sections['float']['iteration'] <= $this->_sections['float']['total'];
                 $this->_sections['float']['index'] += $this->_sections['float']['step'], $this->_sections['float']['iteration']++):
$this->_sections['float']['rownum'] = $this->_sections['float']['iteration'];
$this->_sections['float']['index_prev'] = $this->_sections['float']['index'] - $this->_sections['float']['step'];
$this->_sections['float']['index_next'] = $this->_sections['float']['index'] + $this->_sections['float']['step'];
$this->_sections['float']['first']      = ($this->_sections['float']['iteration'] == 1);
$this->_sections['float']['last']       = ($this->_sections['float']['iteration'] == $this->_sections['float']['total']);
 $this->assign('campo', $this->_tpl_vars['fields_maestro'][$this->_sections['float']['index']]);  $this->assign('reqVal', $this->_tpl_vars['customFields'][$this->_tpl_vars['campo']]['reqVal']);  if ($this->_tpl_vars['types_maestro'][$this->_sections['float']['index']] == 'gvHidraFloat'):  $this->assign('partes', ((is_array($_tmp=',')) ? $this->_run_mod_handler('explode', true, $_tmp, $this->_tpl_vars['lengths_maestro'][$this->_sections['float']['index']]) : explode($_tmp, $this->_tpl_vars['lengths_maestro'][$this->_sections['float']['index']]))); ?>
		$float = new gvHidraFloat(false, <?php echo $this->_tpl_vars['partes'][0]; ?>
);
		$float->setFloatLength(<?php echo $this->_tpl_vars['partes'][1]; ?>
);
		$this->addFieldType('fil_<?php echo $this->_tpl_vars['fields_maestro'][$this->_sections['float']['index']]; ?>
',$float);
<?php if ($this->_tpl_vars['notnulls_maestro'][$this->_sections['float']['index']] == 'true'): ?>
		$float = new gvHidraFloat(true, <?php echo $this->_tpl_vars['partes'][0]; ?>
);
		$float->setFloatLength(<?php echo $this->_tpl_vars['partes'][1]; ?>
);
<?php endif;  if ($this->_tpl_vars['reqVal'] == 1): ?>
		$float->setRequired(true);
<?php endif; ?>
		$this->addFieldType('lis_<?php echo $this->_tpl_vars['fields_maestro'][$this->_sections['float']['index']]; ?>
',$float);
		
<?php endif;  endfor; endif; ?>

		/************************ END TYPEs ************************/
				
		/************************ COMPONENTS ************************/
		
		//Declaracion de Listas y WindowSelection
		//La definición debe estar en el AppMainWindow.php

<?php unset($this->_sections['components']);
$this->_sections['components']['name'] = 'components';
$this->_sections['components']['loop'] = is_array($_loop=$this->_tpl_vars['fields_maestro']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['components']['show'] = true;
$this->_sections['components']['max'] = $this->_sections['components']['loop'];
$this->_sections['components']['step'] = 1;
$this->_sections['components']['start'] = $this->_sections['components']['step'] > 0 ? 0 : $this->_sections['components']['loop']-1;
if ($this->_sections['components']['show']) {
    $this->_sections['components']['total'] = $this->_sections['components']['loop'];
    if ($this->_sections['components']['total'] == 0)
        $this->_sections['components']['show'] = false;
} else
    $this->_sections['components']['total'] = 0;
if ($this->_sections['components']['show']):

            for ($this->_sections['components']['index'] = $this->_sections['components']['start'], $this->_sections['components']['iteration'] = 1;
                 $this->_sections['components']['iteration'] <= $this->_sections['components']['total'];
                 $this->_sections['components']['index'] += $this->_sections['components']['step'], $this->_sections['components']['iteration']++):
$this->_sections['components']['rownum'] = $this->_sections['components']['iteration'];
$this->_sections['components']['index_prev'] = $this->_sections['components']['index'] - $this->_sections['components']['step'];
$this->_sections['components']['index_next'] = $this->_sections['components']['index'] + $this->_sections['components']['step'];
$this->_sections['components']['first']      = ($this->_sections['components']['iteration'] == 1);
$this->_sections['components']['last']       = ($this->_sections['components']['iteration'] == $this->_sections['components']['total']);
 $this->assign('campo', $this->_tpl_vars['fields_maestro'][$this->_sections['components']['index']]);  $this->assign('componente', $this->_tpl_vars['customFields'][$this->_tpl_vars['campo']]['componente']);  if ($this->_tpl_vars['componente'] == 2): ?>
		$check_fil = new gvHidraCheckBox('fil_<?php echo $this->_tpl_vars['fields_maestro'][$this->_sections['components']['index']]; ?>
');
		$check_fil->setChecked(true);
		$check_fil->setValueChecked('');
		$check_fil->setValueUnchecked('');
		$this->addCheckBox($check_fil);
		
		$check_lis = new gvHidraCheckBox('lis_<?php echo $this->_tpl_vars['fields_maestro'][$this->_sections['components']['index']]; ?>
');
		$check_lis->setChecked(false);
		$check_lis->setValueChecked('');
		$check_lis->setValueUnchecked('');
		$this->addCheckBox($check_lis);
		
<?php endif;  if ($this->_tpl_vars['componente'] == 3): ?>
		$radio_fil = new gvHidraList('fil_<?php echo $this->_tpl_vars['fields_maestro'][$this->_sections['components']['index']]; ?>
');
		$radio_fil->setRadio(true);
		$radio_fil->addOption('','Default 1');
		$radio_fil->addOption('','Default 2');
		$radio_fil->setSelected('');
		$this->addList($radio_fil);
		
		$radio_lis = new gvHidraList('lis_<?php echo $this->_tpl_vars['fields_maestro'][$this->_sections['components']['index']]; ?>
');
		$radio_lis->setRadio(true);
		$radio_lis->addOption('','Default 1');
		$radio_lis->addOption('','Default 2');
		$radio_lis->setSelected('');
		$this->addList($radio_lis);
		
<?php endif;  if ($this->_tpl_vars['componente'] == 4): ?>
		$lista_fil = new gvHidraList('fil_<?php echo $this->_tpl_vars['fields_maestro'][$this->_sections['components']['index']]; ?>
');
		$lista_fil->addOption('','Default 1');
		$lista_fil->addOption('','Default 2');
		$lista_fil->setSelected('');
		$this->addList($lista_fil);
		
		$lista_lis = new gvHidraList('lis_<?php echo $this->_tpl_vars['fields_maestro'][$this->_sections['components']['index']]; ?>
');
		$lista_lis->addOption('','Default 1');
		$lista_lis->addOption('','Default 2');
		$lista_lis->setSelected('');
		$this->addList($lista_lis);
		
<?php endif;  endfor; endif; ?>
		/************************ END COMPONENTS ************************/

		//Relacionamos con las clases detalle
		$this->addSlave('<?php echo ((is_array($_tmp=$this->_tpl_vars['classname_detalle'])) ? $this->_run_mod_handler('capitalize', true, $_tmp) : smarty_modifier_capitalize($_tmp)); ?>
', array(<?php unset($this->_sections['pk_maestro']);
$this->_sections['pk_maestro']['name'] = 'pk_maestro';
$this->_sections['pk_maestro']['loop'] = is_array($_loop=$this->_tpl_vars['primaryKeyMaestroArray']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['pk_maestro']['show'] = true;
$this->_sections['pk_maestro']['max'] = $this->_sections['pk_maestro']['loop'];
$this->_sections['pk_maestro']['step'] = 1;
$this->_sections['pk_maestro']['start'] = $this->_sections['pk_maestro']['step'] > 0 ? 0 : $this->_sections['pk_maestro']['loop']-1;
if ($this->_sections['pk_maestro']['show']) {
    $this->_sections['pk_maestro']['total'] = $this->_sections['pk_maestro']['loop'];
    if ($this->_sections['pk_maestro']['total'] == 0)
        $this->_sections['pk_maestro']['show'] = false;
} else
    $this->_sections['pk_maestro']['total'] = 0;
if ($this->_sections['pk_maestro']['show']):

            for ($this->_sections['pk_maestro']['index'] = $this->_sections['pk_maestro']['start'], $this->_sections['pk_maestro']['iteration'] = 1;
                 $this->_sections['pk_maestro']['iteration'] <= $this->_sections['pk_maestro']['total'];
                 $this->_sections['pk_maestro']['index'] += $this->_sections['pk_maestro']['step'], $this->_sections['pk_maestro']['iteration']++):
$this->_sections['pk_maestro']['rownum'] = $this->_sections['pk_maestro']['iteration'];
$this->_sections['pk_maestro']['index_prev'] = $this->_sections['pk_maestro']['index'] - $this->_sections['pk_maestro']['step'];
$this->_sections['pk_maestro']['index_next'] = $this->_sections['pk_maestro']['index'] + $this->_sections['pk_maestro']['step'];
$this->_sections['pk_maestro']['first']      = ($this->_sections['pk_maestro']['iteration'] == 1);
$this->_sections['pk_maestro']['last']       = ($this->_sections['pk_maestro']['iteration'] == $this->_sections['pk_maestro']['total']);
?>'lis_<?php echo $this->_tpl_vars['primaryKeyMaestroArray'][$this->_sections['pk_maestro']['index']]; ?>
',<?php endfor; endif; ?>), array(<?php unset($this->_sections['fk_detalle']);
$this->_sections['fk_detalle']['name'] = 'fk_detalle';
$this->_sections['fk_detalle']['loop'] = is_array($_loop=$this->_tpl_vars['foreignKeyDetalleArray']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['fk_detalle']['show'] = true;
$this->_sections['fk_detalle']['max'] = $this->_sections['fk_detalle']['loop'];
$this->_sections['fk_detalle']['step'] = 1;
$this->_sections['fk_detalle']['start'] = $this->_sections['fk_detalle']['step'] > 0 ? 0 : $this->_sections['fk_detalle']['loop']-1;
if ($this->_sections['fk_detalle']['show']) {
    $this->_sections['fk_detalle']['total'] = $this->_sections['fk_detalle']['loop'];
    if ($this->_sections['fk_detalle']['total'] == 0)
        $this->_sections['fk_detalle']['show'] = false;
} else
    $this->_sections['fk_detalle']['total'] = 0;
if ($this->_sections['fk_detalle']['show']):

            for ($this->_sections['fk_detalle']['index'] = $this->_sections['fk_detalle']['start'], $this->_sections['fk_detalle']['iteration'] = 1;
                 $this->_sections['fk_detalle']['iteration'] <= $this->_sections['fk_detalle']['total'];
                 $this->_sections['fk_detalle']['index'] += $this->_sections['fk_detalle']['step'], $this->_sections['fk_detalle']['iteration']++):
$this->_sections['fk_detalle']['rownum'] = $this->_sections['fk_detalle']['iteration'];
$this->_sections['fk_detalle']['index_prev'] = $this->_sections['fk_detalle']['index'] - $this->_sections['fk_detalle']['step'];
$this->_sections['fk_detalle']['index_next'] = $this->_sections['fk_detalle']['index'] + $this->_sections['fk_detalle']['step'];
$this->_sections['fk_detalle']['first']      = ($this->_sections['fk_detalle']['iteration'] == 1);
$this->_sections['fk_detalle']['last']       = ($this->_sections['fk_detalle']['iteration'] == $this->_sections['fk_detalle']['total']);
?>'lis_<?php echo $this->_tpl_vars['foreignKeyDetalleArray'][$this->_sections['fk_detalle']['index']]; ?>
',<?php endfor; endif; ?>));		

		//Mantener los valores del modo de trabajo FIL tras la busqueda
		$this->keepFilterValuesAfterSearch(true);

	}//End construct

	/************************ CRUD METHODs ************************/

	/**
	* metodo preBuscar
	* 
	* @access public
	* @param object $objDatos
	* 
	* Incorpore aqui la logica para parametrizar la busqueda. Por ejemplo:
	* - Incluir condiciones de filtrado.
	* - Cancelar la accion de buscar. 
	*/	
	public function preBuscar($objDatos) {
		
		return 0;
	}

	/**
	* metodo postBuscar
	* 
	* @access public
	* @param object $objDatos
	* 
	* Incorpore aqui la logica para parametrizar los datos obtenidos. Por ejemplo:
	* - Completar la informacion obtenida.
	* - Cambiar el color de las filas dependiendo de su valor
	*/	
	public function postBuscar($objDatos) {
		
		return 0;
	}

	/**
	* metodo preInsertar
	* 
	* @access public
	* @param object $objDatos
	* 
	* Incorpore aqui la logica para parametrizar los datos a insertar. Por ejemplo:
	* - Calcular el valor de una secuencia.
	* - Cancelar la acción de insercion.
	*/		
	public function preInsertar($objDatos) {
		
		return 0;
	}
	
	/**
	* metodo postInsertar
	* 
	* @access public
	* @param object $objDatos
	* 
	* Incorpore aqui la logica para completar la operacion de insercion. Por ejemplo:
	* - Insertar en una segunda tabla.
	*/		
	public function postInsertar($objDatos) {
		
		return 0;
	}

	/**
	* metodo preModificar
	* 
	* @access public
	* @param object $objDatos
	* 
	* Incorpore aqui la logica para parametrizar la operacion de actualizacion. Por ejemplo:
	* - Calcular valores derivados.
	* - Cancelar la acción de actualizacion.
	*/
	public function preModificar($objDatos) {
		
		return 0;
	}
	
	/**
	* metodo postModificar
	* 
	* @access public
	* @param object $objDatos
	* 
	* Incorpore aqui la logica para completar la operacion de actulizacion. Por ejemplo:
	* - Actualizar en una segunda tabla
	*/	
	public function postModificar($objDatos) {
		
		return 0;
	}
	
	/**
	* metodo preBorrar
	* 
	* @access public
	* @param object $objDatos
	* 
	* Incorpore aqui la logica para parametrizar la operacion de borrado. Por ejemplo:
	* - Cancelar la acción de borrado.
	*/	
	public function preBorrar($objDatos) {
		
		return 0;
	}
	
	/**
	* metodo postBorrar
	* 
	* @access public
	* @param object $objDatos
	* 
	* Incorpore aqui la logica para completar la operacion de borrado. Por ejemplo:
	* - Borrar en una segunda tabla
	*/	
	public function postBorrar($objDatos) {
		
		return 0;
	}
	
	/**
	* metodo preNuevo
	* 
	* @access public
	* @param object $objDatos
	* 
	* Incorpore aqui los valores por defecto antes de insertar.
	*/	
	public function preNuevo($objDatos) {
		
		return 0;
	}
	
	/**
	* metodo preIniciarVentana
	* 
	* @access public
	* @param object $objDatos
	* 
	* Incorpore aqui la logica a ejecutar cuando entra en la ventana. Por ejemplo:
	* - Puede comprobar que el usuario tiene los permisos necesarios.
	*/	
	public function preIniciarVentana($objDatos) {
		
		return 0;
	}
	
	/************************ END CRUD METHODs ************************/
	
	/**
	* metodo accionesParticulares
	* 
	* @access public
	* @param string $str_accion
	* @param object $objDatos
	* 
	* Incorpore aqui la logica de sus acciones particulares. 
	* -En el parametro $str_accion aparece el id de la accion.
	* -En el parametro $objDatos esta la informacion de la peticion. Recuerde que debe fijar la operacion
	* con el metodo setOperacion.
	*/	
	public function accionesParticulares($str_accion, $objDatos) {
        
		throw new Exception('Se ha intentado ejecutar la acción '.$str_accion.' y no está programada.');        
    }
	
}//End <?php echo ((is_array($_tmp=$this->_tpl_vars['classname_maestro'])) ? $this->_run_mod_handler('capitalize', true, $_tmp) : smarty_modifier_capitalize($_tmp)); ?>


<?php echo '?>'; ?>