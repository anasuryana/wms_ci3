<?php

class RCVMEGA_mod extends CI_Model {
	private $TABLENAME = "?";
	public function __construct()
    {
        $this->load->database();
    }	
    public function selectbydo($pid)
	{
        $DBIuse = $this->load->database('megadb',TRUE);
        $DBIuse->from($this->TABLENAME.' a');        
		$query = $DBIuse->get();
		return $query->result();
	}
}