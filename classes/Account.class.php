<?php
class Account{
	private $a_no;
	private $userName;
	private $image;
	private $deleteDay;

	public function setNo($a_no){
		$this->a_no=$a_no;
	}
	public function getNo(){
		return $this->a_no;
	}

	public function setUserName($userName){
		$this->userName=$userName;
	}
	public  function getUserName(){
		return $this->userName;
	}
	

	public function setImage($image){
		$this->image=$image;
	}
	public function getImage(){
		return $this->image;
	}

	public function setDeleteDay($deleteDay){
		$this->deleteDay=$deleteDay;
	}
	public function getDeleteDay(){
		return $this->deleteDay;
	}

}