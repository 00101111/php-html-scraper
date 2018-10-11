<?php
class PesDB {
	public $pes_year = null;

	public $page_link = null;
	public $page_number = null;
	public $page_content = null;
	public $parse_html = array();

	public $player_data = array();

	public function __construct($pes_year = "2019") {
		$this->pes_year = $pes_year;;
	}

	public function setPageNumber($page_number) {
		$this->page_number = $page_number;
	}

	public function setPageLink($page_number) {
		$this->setPageNumber($page_number);
		$this->page_link = "https://pesdb.net/pes".$this->pes_year."/?page=".$this->page_number;
	}

	public function setPageContent() {
		$this->page_content = file_get_contents($this->page_link);
	}

	public function parsePageContent() {
		$this->parse_html = [
			'@<tr><td class="(.*?)"><div title="(.*?)">(.*?)</div></td>@si',
			'@<td class="left"><a href="./\?id=(.*?)">(.*?)</a></td>@si',
			'@<td class="left"><a href="(.*?)nationality(.*?)" rel="nofollow">(.*?)</a></td>@si',
			'@<td class="selected(.*?)"><img src="images/(.*?).png" alt="" style="(.*?)" />(.*?)</td>@si'
		];
		for($i = 0; $i < 4; $i++) {
			preg_match_all($this->parse_html[$i],$this->page_content,$this->parse_html[$i]);
		}
	}

	public function setPlayerData($name, $value) {
		$this->player_data[$name] = $value;
	}

	public function setPage($page_number) {
		$this->setPageLink($page_number);
		$this->setPageContent();
	}

	public function printData($page_number) {
		$this->setPage($page_number);
		echo "\n\n/ PAGE ".$this->page_number." -------------------------------->";
		$this->parsePageContent();
		for ($i=0; $i <= 31; $i++) {
			$this->setPlayerData("ID", $this->parse_html[1][1][$i]);
			$this->setPlayerData("Position", $this->parse_html[0][3][$i]);
			$this->setPlayerData("Player Name", $this->parse_html[1][2][$i]);
			$this->setPlayerData("Nationality", $this->parse_html[2][3][$i]);
			$this->setPlayerData("Overall Rating", $this->parse_html[3][4][$i]);
			foreach($this->player_data as $key => $val) {
				echo "\n".$key.": ".$val."\t";
			}
			
		}
		echo "\n<-------------------------------- PAGE ".$this->page_number." /\n";
	}
}
?>
