<?php
class Password{
	private $hash;
	private $share_hash;

	public function setHash($hash){
		$this->hash=$hash;
	}
	public function getHash(){
		return $this->hash;
	}

	public function setShareHash($share_hash){
		$this->share_hash=$share_hash;
	}
	public function getShareHash(){
		return $this->share_hash;
	}
}