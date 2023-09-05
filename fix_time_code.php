<?php

declare(strict_types=1);

class FixTimeCode {

	protected array $data = array();

	const VIDEO = "https://youtu.be/Nh19f_2fWLc";


	public function __construct() {
		$this->Main();
	}


	protected function Main() {
		$fullPathName = __DIR__."/highlight_in.md";
		printf("\n>> %s\n\n", $fullPathName);
		$this->data = file( $fullPathName);

		$nn = count($this->data);
		for( $i = 0; $i < $nn; $i++) {
			if( $begin = stripos($this->data[$i], "[t=")) {
				if($end = stripos($this->data[$i], "s]")) {
					$old_tag = substr($this->data[$i], $begin, $end-$begin+2);
					$tc      = substr($this->data[$i], $begin+3, $end-$begin-3);
					if( stripos($tc, ":")) {
						$x   = explode(":", $tc);
						$sec = (int)$x[0]*3600 + (int)$x[1]*60 + (int)$x[2];
						if( (int)$x[0] <= 0) {
							$new_tc = $x[1].":".$x[2];
						} else {
							$new_tc = $tc;
						}
					} else {
						$sec    = intval($tc);
						$new_tc = ( $sec < 3600)? gmdate('i:s', ($sec)): gmdate('H:i:s', ($sec));
					}
					$new_tag = sprintf("[%s](%s?t=%d)", $new_tc, self::VIDEO, $sec);
					printf("(%s)\t>%s<\t>%s<\t%s\n", $i, $sec, $old_tag, $new_tag);

					$tmp = str_replace($old_tag, $new_tag, $this->data[$i]);
					$tmp = str_replace("Section Overview: ", "**Section Overview:** ", $tmp);
					$this->data[$i] = $tmp;
				}
			}
		}

		$this->GenerateMd();

	}


	protected function GenerateMd() {

		$fd = fopen( __DIR__."/highlight_out.md", "w");
		foreach($this->data as $row) {
			fprintf( $fd, "%s", $row);
		}
		fclose( $fd);

	}

}

$sd = new FixTimeCode();

?>
