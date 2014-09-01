<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Lib_tanggal {

    public function tgl($date)
    {
		$d = substr($date ,8,2);
		$m = substr($date ,5,2);
		$t = substr($date ,0,4);
		
		if ($m == '01') {
			$bln = 'Januari';
		} elseif ($m == '02') {
			$bln = 'Februari';
		} elseif ($m == '03') {
			$bln = 'Maret';
		} elseif ($m == '04') {
			$bln = 'April';
		} elseif ($m == '05') {
			$bln = 'Mei';
		} elseif ($m == '06') {
			$bln = 'Juni';
		} elseif ($m == '07') {
			$bln = 'Juli';
		} elseif ($m == '08') {
			$bln = 'Agustus';
		} elseif ($m == '09') {
			$bln = 'September';
		} elseif ($m == '10') {
			$bln = 'Oktober';
		} elseif ($m == '11') {
			$bln = 'November';
		} elseif ($m == '12') {
			$bln = 'Desember';
		} else {
			$bln = '';
		}
		
		$date = $d.' '.$bln.' '.$t;
		return $date;
    }
}

/* End of file Lib_tanggal.php */