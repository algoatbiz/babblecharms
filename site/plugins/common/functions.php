<?php

function ktNoPar($string) {
	return preg_replace('!^<p>(.*?)</p>$!i', '$1', kirbytext($string));
}