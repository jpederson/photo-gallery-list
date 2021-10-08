<?php

// function to get the file extension (type)
function ext( $file ) {
    if ( is_dir( $file ) ) {
        return 'dir';
    } else {
        return str_replace( '7z', 'sevenz', strtolower( pathinfo( $file )['extension'] ) );
    }
}

// function to get the title, from the url
function title() {
    $url = substr( $_SERVER['REQUEST_URI'], 1 );
    if ( empty( $url ) ) $url = 'home/';
    return $url;
}

// function to get human-readable filesize
function human_filesize( $file ) {
    $bytes = filesize( $file );
    $decimals = 1;
    $factor = floor( ( strlen($bytes) - 1 ) / 3 );
    if ( $factor > 0 ) $sz = 'KMGT';
    return sprintf( "%.{$decimals}f", $bytes / pow( 1024, $factor ) ) . @$sz[$factor - 1] . 'B';
}

// get the file list for the current directory
$files = scandir( '.' );

// files to exclude from the files array.
$exclude = array( '.', '..', '.DS_Store', 'index.php', '.git', '.gitmodules', '.gitignore', 'node_modules' );

// search files array and remove anything in the exclude array
foreach ( $exclude as $ex ) {
    if ( ( $key = array_search( $ex, $files ) ) !== false ) {
        unset( $files[$key] );
    }
}

// title bar and tiny stylesheet with all the icons encoded in it.
?><html lang="en"><head><title><?php print str_replace( '/', ' / ', title() ); ?></title><meta name="viewport" content="width=device-width"><style>@import"https://fonts.googleapis.com/css?family=Raleway&display=swap";body{padding:0;margin:0;font-family:Raleway,sans-serif;font-size:100%;line-height:100%}a{color:#007b75;text-decoration:none;background-color:#fff;display:block;margin-bottom:5px;transition:400ms all ease-in-out;padding-left:15px}a:hover{background-color:#eee;color:#001514}@media only screen and (min-width: 850px){.container{border:2px solid #ddd;box-shadow:0 0 25px #e5e5e5;max-width:768px;margin:30px auto}}h1{padding:20px 10px 10px;margin:0;font-size:1.4em;border-bottom:2px solid #eee;color:#666}h1 span{color:#ddd}p{margin:0;padding:10px;color:#999}ul{list-style:none;padding:10px 0 20px;margin:0}ul li{padding:7px 20px 7px 30px;background:url("data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAQAAAC1+jfqAAAABGdBTUEAAK/INwWK6QAAABl0RVh0U29mdHdhcmUAQWRvYmUgSW1hZ2VSZWFkeXHJZTwAAAC4SURBVCjPdZFbDsIgEEWnrsMm7oGGfZrohxvU+Iq1TyjU60Bf1pac4Yc5YS4ZAtGWBMk/drQBOVwJlZrWYkLhsB8UV9K0BUrPGy9cWbng2CtEEUmLGppPjRwpbixUKHBiZRS0p+ZGhvs4irNEvWD8heHpbsyDXznPhYFOyTjJc13olIqzZCHBouE0FRMUjA+s1gTjaRgVFpqRwC8mfoXPPEVPS7LbRaJL2y7bOifRCTEli3U7BMWgLzKlW/CuebZPAAAAAElFTkSuQmCC") left center no-repeat}ul li.jpg,ul li.gif,ul li.jpeg,ul li.png,ul li.raw,ul li.nef,ul li.tif,ul li.tiff,ul li.dwg,ul li.dwf,ul li.dxf{background:url("data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAABGdBTUEAAK/INwWK6QAAABl0RVh0U29mdHdhcmUAQWRvYmUgSW1hZ2VSZWFkeXHJZTwAAAHwSURBVDjLpZM9a1RBFIafM/fevfcmC7uQjWEjUZKAYBHEVEb/gIWFjVVSWEj6gI0/wt8gprPQykIsTP5BQLAIhBVBzRf52Gw22bk7c8YiZslugggZppuZ55z3nfdICIHrrBhg+ePaa1WZPyk0s+6KWwM1khiyhDcvns4uxQAaZOHJo4nRLMtEJPpnxY6Cd10+fNl4DpwBTqymaZrJ8uoBHfZoyTqTYzvkSRMXlP2jnG8bFYbCXWJGePlsEq8iPQmFA2MijEBhtpis7ZCWftC0LZx3xGnK1ESd741hqqUaqgMeAChgjGDDLqXkgMPTJtZ3KJzDhTZpmtK2OSO5IRB6xvQDRAhOsb5Lx1lOu5ZCHV4B6RLUExvh4s+ZntHhDJAxSqs9TCDBqsc6j0iJdqtMuTROFBkIcllCCGcSytFNfm1tU8k2GRo2pOI43h9ie6tOvTJFbORyDsJFQHKD8fw+P9dWqJZ/I96TdEa5Nb1AOavjVfti0dfB+t4iXhWvyh27y9zEbRRobG7z6fgVeqSoKvB5oIMQEODx7FLvIJo55KS9R7b5ldrDReajpC+Z5z7GAHJFXn1exedVbG36ijwOmJgl0kS7lXtjD0DkLyqc70uPnSuIIwk9QCmWd+9XGnOFDzP/M5xxBInhLYBcd5z/AAZv2pOvFcS/AAAAAElFTkSuQmCC") left center no-repeat}ul li span{color:#aaa;font-size:.75em;line-height:1em}</style></head><body><div class="container"><?php

// display a title on the top of the listing
print "<h1>" . str_replace( '/', ' <span>/</span> ', title() ) . "</h1>";

// if the array of files isn't empty
if ( !empty( $files ) ) {

    // open unordered list tag
    print "<ul class='popup-gallery'>";

    // loop through the files array
    foreach ( $files as $file ) {

        // check if the file is an image, and only display images
        if ( ( stristr( $file, '.jpg' ) || stristr( $file, '.jpeg' ) || stristr( $file, '.gif' ) || stristr( $file, '.png' ) || stristr( $file, '.webp' ) ) && $file !== 'preview.gif' ) {
            
            // show the file
            print '<a href="./' . $file . '"><li class="' . ext( $file ) . '">' . $file . ( !is_dir( $file ) ? ' <span>' . human_filesize( $file ) . '</span>' : '' ) . '</li></a>';
        }

    }

    // close unordered list tag
    print "</ul>";

} else {

    // display empty directory message
    print "<p>This folder contains no files.</p>";

}

?></div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/jquery.magnific-popup.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/magnific-popup.min.css" />
<script>
$(document).ready(function() {
    $('.popup-gallery').magnificPopup({
        delegate: 'a',
        type: 'image',
        tLoading: 'Loading image #%curr%...',
        mainClass: 'mfp-img-mobile',
        gallery: {
            enabled: true,
            navigateByImgClick: true,
            preload: [0,1] // Will preload 0 - before current, and 1 after the current image
        },
        image: {
            tError: '<a href="%url%">The image #%curr%</a> could not be loaded.',
        }
    });
});
</script>
</body></html>