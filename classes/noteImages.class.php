<?php
class noteImages
{
	private $image_no;
	private $image_name;
	private $a_no;

	public function setImageNo($image_no) {
		$this->image_no = $image_no;
	}
	public function getImageNo() {
		return $this->image_no;
	}

	public function setImageName($image_name) {
		$this->image_name = $image_name;
	}
	public function getImageName() {
		return $this->image_name;
	}

	public function setNo($a_no){
		$this->a_no=$a_no;
	}
	public function getNo(){
		return $this->a_no;
	}
}