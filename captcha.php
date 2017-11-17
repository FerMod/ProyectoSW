<?php
		$numcaptcha = rand(1000, 9999);
		header("Content-type: image/png");
		$image = imagecreate(80, 25);
		$background = imagecolorallocate($image, 47, 79, 79);
		$text = imagecolorallocate($image, 255, 255, 255);
		imagestring($image, 12, 20, 5, $numcaptcha, $text);
		imagepng($image);
?>