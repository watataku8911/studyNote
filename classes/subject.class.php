<?php
class Subject{
	private $subjectNo;
	private $subjectName;

	public function setSubjectNo($subjectNo){
		$this->subjectNo=$subjectNo;
	}
	public function getSubjectNo(){
		return $this->subjectNo;
	}

	public function setSubjectName($subjectName){
		$this->subjectName=$subjectName;
	}
	public function getSubjectName(){
		return $this->subjectName;
	}

}