<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
if ( ! function_exists('segoecaptcha'))
{
	function segoecaptcha($text_captcha){
		
		// persiapkan gambar
		$im = imagecreatetruecolor(120, 30); 
		
		// Create some colors
		$bg = imagecolorallocate($im, 223, 223, 223);
		imagefilledrectangle($im, 0, 0, 399, 29, $bg);
		
		// simpan variabel warna warna tulisan
		$warna = ImageColorAllocate($im, 0, 0, 0);
		$warna2 = ImageColorAllocate($im, 62, 188, 170);
		
		// tentukan font
		$font = './assets/font/biko.otf';	
		
		// ukuran font
		$ukuran_font = 14;
		$tulisan = $text_captcha;

		$start_x = 11; 
		$start_y = 21; 
		
		// gabungkan gambar
		Imagettftext($im, $ukuran_font, 0, 22, 22, $warna2, $font, $tulisan); 
		imagettftext($im, $ukuran_font, 0, 21, 21, $warna, $font, $tulisan); 

		// bikin gambar daru dari penggabungan itu
		header('Content-type: image/png');
		imagepng($im);
		imagedestroy($im);

	}
}
