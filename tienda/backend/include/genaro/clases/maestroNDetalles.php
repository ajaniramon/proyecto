<?php

class maestroNDetalles 
{
	private $numeroDetalles;
	
	private $action;
	
	private $plantilla;
	
	private $mappings;
	
	private $include;
	
	private $view;
	
	function getNumeroDetalles()
	{
		return $this->numeroDetalles;
	}
	
	function getAction()
	{
		return $this->action;
	}
	
	function getPlantilla()
	{
		return $this->plantilla;
	}
	
	function getMappings()
	{
		return $this->mappings;
	}
	
	function getInclude()
	{
		return $this->include;
	}
	
	function getView()
	{
		return $this->view;
	}
	
	function setNumeroDetalles($numeroDetalles)
	{
		$this->numeroDetalles = $numeroDetalles;
	}
	
	function setAction($action)
	{
		$this->action = $action;
	}
	
	function setPlantilla($plantilla)
	{
		$this->plantilla = $plantilla;
	}
	
	function setMappings($mappings)
	{
		$this->mappings = $mappings;
	}
	
	function setInclude($include)
	{
		$this->include = $include;
	}
	
	function setView($view)
	{
		$this->view = $view;
	}

	function rellenaAction($patron) 
	{
		$this->action .= $patron;
	}
	
	function rellenaPlantilla($plantilla) 
	{
		$this->plantilla .= $plantilla;
	}
	
	function rellenaMappings($mappings) 
	{
		$this->mappings .= $mappings;
	}
	
	function rellenaInclude($include) 
	{
		$this->include .= $include;
	}
	
	function rellenaView($view) 
	{
		$this->view .= $view;
	}
}
?>