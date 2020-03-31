<?php
session_start();
$_SESSION['time'] = time();
?>

<body>

<?php

if (isset($_SESSION['captcha_word'])) {
    if ($_POST['captcha_word'] == $_SESSION['captcha_word']) {
        ?>

        <div>
            <h2>correct</h2>
            <form action=" <?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                <input type="submit" value="refresh the page">
            </form>
        </div>

    <?php

    } else {
        ?>

        <div style="text-align:center;">
            <h1>incorrect!</h1>
        </div>

        <?php
        load_text_to_image();
        enter_captcha_screen();
    }
} else {
    load_text_to_image();
    enter_captcha_screen();
}

function enter_captcha_screen()
{
    ?>
    <div style="text-align:center;">
        <h4>What do you see in the image</h4>
        <div>
            <img src="image<?php echo $_SESSION['time'] ?>.png">
        </div>
        <form action=" <?php echo $_SERVER['PHP_SELF']; ?>" method="POST" / >
	        <input name="captcha_word" type="text" />
	        <input type="submit" value="submit" />
        </form>
        <a href="<?php echo $_SERVER['PHP_SELF']; ?>"></a>
    </div>
	<?php
}

function create_captcha_word() { 
 $captcha_text = ""; //initialize the required variable to keep the data.
 for($i=0; $i<5; $i++) //I want to create 5 charactered captcha word
 {
    //Make a random selection
   switch(rand(1,2))
   {
     case 1: $captcha_text .=chr(rand(48,57));  break; //0-9
     case 2: $captcha_text .=chr(rand(65,90));  break; //A-Z
     //case 3: $captcha_text .=chr(rand(97,122)); break; //a-z
   }
 }
  //load the created word to the SESSION
 $_SESSION['captcha_word'] = $captcha_text ;
  return $captcha_text;
} 

function create_captcha_image()
{
    //Define an image to build.
    $captcha_image= imagecreatetruecolor(200, 50) or die("GD could not initialized");

     //Here we define the background, captcha, line and dot colors.
    $back_color = imagecolorallocate($captcha_image, 255, 255, 255);
    $dot_color = imagecolorallocate($captcha_image, 0, 0, 255);
    $line_color = imagecolorallocate($captcha_image, 64, 64, 64);

     //We fill our created image with the defined background color.
    imagefilledrectangle($captcha_image, 0, 0, 200, 50, $back_color );

     //Here we add some dots to the image
    for ($i = 0; $i < 200; $i++) {
        imagesetpixel($captcha_image, rand() % 200, rand() % 50, $dot_color);
    }

    //Here we add some lines to the image
    for ($i = 0; $i < 1; $i++) {
        imageline($captcha_image, 0, rand() % 50, 200, rand() % 50, $line_color);
    }

return $captcha_image;
}

function load_text_to_image(){
    $word = create_captcha_word();
    global $captcha_image;
    $captcha_image = create_captcha_image();

    //We wrote all of letters inside the image
    $text_color = imagecolorallocate($captcha_image , 0, 0, 0);
    for ($i = 0; $i < 5; $i++) {
        imagestring($captcha_image , 7, 55 + ($i * 30), 20, $word[$i], $text_color);
    }
 
     //clean the global image for refreshing
    $images = glob("*.png");
    foreach ($images as $image_to_delete) {
        @unlink($image_to_delete);
    }
    imagepng($captcha_image , "image" . $_SESSION['time'] . ".png");

}

?>
</body>