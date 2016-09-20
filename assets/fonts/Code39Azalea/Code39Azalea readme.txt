Code39Azalea

Code39Azalea is a barcode font that creates Code 39 (Code 3 of 9) barcodes. It's an OpenType font in both TrueType (.ttf) and PostScript (.otf) format. It can be used on Windows, OS X, and Linux computers, and both in print and as a web font.

Code39Azalea is numeric and therefore prints Code 39 barcodes that contain only numbers. Code39Azalea supports a subset of the full Code 39 character set.

The font includes the digits 0-9 and the asterisk, which is the Code 39 start and stop bar. Create a barcode by adding an asterisk before and after your numeric string. 
  *314159*  formatted into Code39Azalea will scan as  314159

The point size you use determines the height of the bars. Format text at 72 points and the bars will be 1" tall, format at 36 points 1/2", etc.

Install this font like any other TrueType or PostScript font. It will then be available within any Windows or OS X application. The web fonts are hosted on azalea.com and are free to use under a Creative Commons CC-ND license (CC BY-ND 3.0). This means you are encouraged to share, copy, and distribute Code39Azalea including commercial use but you must attribute Code39Azalea to Azalea Software, Inc. and you may not alter, transform, or build upon this work. http://creativecommons.org/licenses/by-nd/3.0/deed.en_US

Here's a function that converts your input into the equivalent Code 39 symbol:

  Function AzaleaCode39(ByVal yourNumericInput as String) as String
  ' Code39Azalea 28nov12 Copyright 2012 Jerry Whiting.  Azalea Software, Inc.  www.azalea.com/code-39/
  ' Your input must be a numeric string using the digits 0-9.
  ' Format the output, AzaleaCode39, using the Code39Azalea font.
      AzaleaCode39 = "*" + yourNumericInput + "*"
  ' (Yes, there are more comments than code here.)
  End Function


Code39Azalea is also a web font using CSS3 @font-face. You can host the TTF, EOT, WOFF, and SVG on your server:
<style> 
@font-face
{
font-family: Code39AzaleaFont;
src: url('Code39Azalea.eot') format('embedded-opentype'), /* IE9 Compat Modes */
   url('Code39Azalea.woff') format('woff'), /* Modern Browsers */
   url('Code39Azalea.ttf') format('truetype'), /* Safari, Android, iOS */
   url('Code39Azalea.svg#Code39Azalea') format('svg'); /* Legacy iOS */
font-weight: normal;
font-style: normal;
}
</style>

Within your HTML apply the font using font-size to set the height of the bars:
<div style="font-family:Code39AzaleaFont; font-size:72px;">
*314159265*
</div>

Code39Azalea hosted on Azalea Software's server and you can use it by linking to a CSS3 file:
<link rel="stylesheet" href="http://azalea.com/web-fonts/Code39Azalea.min.css">
If you do, you must edit your .htaccess file to accommodate Firefox:
 To accommodate Firefox, add this to your .htaccess file:
AddType font/ttf .ttf
AddType font/eot .eot
AddType font/otf .otf
AddType font/woff .woff

<FilesMatch "\.(ttf|otf|eot|woff)$">
    <IfModule mod_headers.c>
        Header set Access-Control-Allow-Origin "*"
    </IfModule>
</FilesMatch>

Refer to the Code39Azalea.html file for an example of how to use Code39Azalea as a web font.


This demo font is a subset of our C39Tools font package: www.azalea.com/code-39/ The full version of C39Tools contains twenty-seven different Code 39 barcode fonts, an OCR-B font for the optional human-readable digits, sample code for applications like Crystal Reports, Excel, Access, Visual Basic, C/C++, etc, and complete documentation. C39Tools is available for Windows, the Macintosh, *NIX, and other platforms.

Azalea Software, Inc.  ¤  www.azalea.com  ¤  1.206.341.9500  ¤  azalea@azalea.com
