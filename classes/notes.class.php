<?php
class Notes{
	private $n_no;
	private $n_title;
	private $n_body;
	private $a_no;
	private $subject_no;
	private $created;
	private $deleted;
	private $deletes;
	private $share;

	public function setN_No($n_no){
		$this->n_no=$n_no;
	}
	public function getN_No(){
		return $this->n_no;
	}

	public function setTitle($n_title){
		$this->n_title=$n_title;
	}
	public function getTitle(){
		return $this->n_title;
	}

	public function setBody($n_body){
		$this->n_body=$n_body;
	}
	public function getBody(){
		return $this->n_body;
	}


	public function setNo($a_no){
		$this->a_no=$a_no;
	}
	public function getNo(){
		return $this->a_no;
	}

	public function setSubjectNo($subject_no){
		$this->subject_no=$subject_no;
	}
	public function getSubjectNo(){
		return $this->subject_no;
	}

	public function setCreated($created) {
		$this->created=$created;
	}
	public function getCreated() {
		return $this->created;
	}

	public function setDeleted($deleted) {
		$this->deleted=$deleted;
	}
	public function getDeleted() {
		return $this->deleted;
	}

	public function setDeletes($deletes) {
		$this->deletes=$deletes;
	}
	public function getDeletes() {
		return $this->deletes;
	}

	public function setShare($share) {
		$this->share=$share;
	}
	public function getShare() {
		return $this->share;
	}

	public function getCreatedStr(){
		$createdstr="";
		if(!empty($this->created)){
			$createdstr=date("Y年m月d日 H時i分s秒",strtotime($this->created));
		}
		return $createdstr;
	}

	public function getDeletedStr(){
		$deletedstr="";
		if(!empty($this->deleted)){
			$deletedstr=date("Y年m月d日 H時i分s秒",strtotime($this->deleted));
		}
		return $deletedstr;
	}

	public function getDeletesStr(){
		$deletesstr="";
		if(!empty($this->deletes)){
			$deletesstr=date("Y年m月d日 H時i分s秒",strtotime($this->deletes));
		}
		return $deletesstr;
	}
}