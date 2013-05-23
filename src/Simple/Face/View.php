<?php

namespace Simple\Face;

interface View {
	public function setLayout();
	public function getLayout();
	public function get();
	public function set();
	public function render();

}