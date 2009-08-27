<?php
/****************************************************************************
 * DRBImageVerification
 * http://www.dbscripts.net/imageverification/
 * 
 * Copyright © 2007 Don Barnes 
 ****************************************************************************/
session_start();
require_once(dirname(__FILE__) . '/config.php');

function the_credits() {
    echo("<div class=\"credit\">Powered by DRBImageVerification, a free PHP <a href=\"http://www.dbscripts.net/imageverification/\">image verification</a> script</div>\n");
}

function createChallengeString() {
    global $CHALLENGE_STRING_LENGTH;
     
     $challenge_string = "";
     
     // Create string from random characters in list of valid characters
     for($i = 0; $i < $CHALLENGE_STRING_LENGTH; $i++) {
         $challenge_string .= pickNextChar(); 
     }
      //echo "Here".$challenge_string;
     // Store challenge string in session
     @session_start();
      $CHALLENGE_STRING_SESSION_VAR_NAME="challenge_string";$_SESSION[$CHALLENGE_STRING_SESSION_VAR_NAME] = $challenge_string;
     
     return $challenge_string;
 }
 
 function pickNextChar() {
     global $CHALLENGE_STRING_LETTERS;
     return substr($CHALLENGE_STRING_LETTERS, (rand() % strlen($CHALLENGE_STRING_LETTERS)), 1);
 }
 
 function getChallengeString() {
      $CHALLENGE_STRING_SESSION_VAR_NAME="challenge_string";// Check if a valid challenge string does not exist
     @session_start(); 
     if(!isset($_SESSION[$CHALLENGE_STRING_SESSION_VAR_NAME])) {
         return FALSE;
     }
     
     return $_SESSION[$CHALLENGE_STRING_SESSION_VAR_NAME];
 }
 
 function isChallengeAccepted($entered_value) {
      $CHALLENGE_STRING_SESSION_VAR_NAME="challenge_string";
     
     // Get challenge string
     $challenge_string = getChallengeString();
     if($challenge_string === FALSE) { return FALSE; }
 
     // Convert entered value into uppercase, if enabled
     global $CHALLENGE_CONVERT_TO_UPPER;
     if($CHALLENGE_CONVERT_TO_UPPER === TRUE) {
         $entered_value = strtoupper($entered_value);
     }
     
     // Remove from session, so that it cannot be reused
     unset($_SESSION[$CHALLENGE_STRING_SESSION_VAR_NAME]);
     
     // Compare entered value to challenge string in session
    return ($challenge_string === $entered_value);
}
 
 function outputChallengeImage() {
     
     // Create a challenge string
     $challenge_string = getChallengeString();
     if($challenge_string === FALSE) { return FALSE; }
     
     // Set content type
     header("Content-type: image/png");

     // Get character sizes and string sizes
     global $CHALLENGE_STRING_FONT_SIZE;
     global $CHALLENGE_STRING_LENGTH;
    $char_width = imagefontwidth($CHALLENGE_STRING_FONT_SIZE);
    $char_height = imagefontheight($CHALLENGE_STRING_FONT_SIZE);
    $string_width = $CHALLENGE_STRING_LENGTH * $char_width;
    $string_height = 1 * $char_height;
         
    // Create image and get color
    global $CHALLENGE_STRING_PADDING;
    $img_width = $string_width + $CHALLENGE_STRING_PADDING * 2;
    $img_height = $string_height + $CHALLENGE_STRING_PADDING * 2;     
     $img = @imagecreatetruecolor($img_width, $img_height)
       or die("imagecreatetruecolor failed");

    // Pick colors
    global $CHALLENGE_ALTERNATE_COLORS;
    if($CHALLENGE_ALTERNATE_COLORS === FALSE || rand(0, 1) == 0) {
         $background_color = imagecolorallocate($img, 15, 15, 15);
         $text_color = imagecolorallocate($img, 238, 238, 238);
         $bg_text_color = imagecolorallocate($img, 95, 95, 95);
    } else {
         $background_color = imagecolorallocate($img, 238, 238, 238);
         $text_color = imagecolorallocate($img, 15, 15, 15);
         $bg_text_color = imagecolorallocate($img, 191, 191, 191);
    }

     // Fill background
     imagefill($img ,0, 0, $background_color);
     
     // Draw background text pattern
     global $CHALLENGE_BACKGROUND_PATTERN_ENABLED;
     if($CHALLENGE_BACKGROUND_PATTERN_ENABLED === TRUE) {
         global $CHALLENGE_BACKGROUND_STRING_FONT_SIZE;
        $bg_char_width = imagefontwidth($CHALLENGE_BACKGROUND_STRING_FONT_SIZE);
        $bg_char_height = imagefontheight($CHALLENGE_BACKGROUND_STRING_FONT_SIZE);
         for($x = rand(-2, 2); $x < $img_width; $x += $bg_char_width + 1) {
             for($y = rand(-2, 2); $y <  $img_height; $y += $bg_char_height + 1) {
                 imagestring($img, $CHALLENGE_BACKGROUND_STRING_FONT_SIZE, $x, 
                    $y, pickNextChar(), $bg_text_color);
             }
         }
     }

     // Draw text
     $x = $CHALLENGE_STRING_PADDING + rand(-2, 2);
     $y = $CHALLENGE_STRING_PADDING + rand(-2, 2);
     for($i = 0; $i < strlen($challenge_string); $i++) {
        imagestring($img, $CHALLENGE_STRING_FONT_SIZE, $x, 
            $y  + rand(-2, 2), substr($challenge_string, $i, 1), $text_color);
        $x += $char_width;
     }
      
    // Output image
    imagepng($img);
    
    // Release image resources
    imagedestroy($img);
     
 }
 
?>
