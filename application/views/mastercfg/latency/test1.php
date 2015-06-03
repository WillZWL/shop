<?php
Class Abc
{
	public function Abc()
	{
		//echo "no param";
	}
	
	public function hi(C $a)
	{
		echo "a";
		
		$a->ccc();
		$a->execute();
		$a->hehe();
		//throw new Exception("hihi");
	}
	
	/*public function hi(B $b)
	{
		echo "b";
	}*/
}

interface c
{
	public function ccc();
}

Class A
{
	public function execute()
	{
		echo "A";
	}
}

Class AB extends A implements C
{
	public function execute()
	{
		echo "AB";
	}
	
	public function hehe()
	{
		echo "ahahahaha||";
	}
	
	public function ccc()
	{
		echo "this is ccc";
	}
}

?>