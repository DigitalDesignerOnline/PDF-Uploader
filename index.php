<?php
/*
 * Plugin Name: Secure PDF Uploader
 * Plugin URL: http://digitaldesigneronline.com/members/plugins/secure-pdf-uploader/
 * Description: Provides a easy upload interface for the PDF Watermarker plugin.
 * Version: 1.0
 * Author: Chad Warford - Fullstack Developer
 * Author URI: http://onlinelifesaver.com
 */
 
 // Begin Registering Admin Menu Items
add_action( 'admin_menu', 'pdf_upload_admin_menu' );

function pdf_upload_admin_menu() {
    add_menu_page( 'Secure PDFs', 'Secure PDF', 'manage_options', 'PDF-Uploader/pdfupload.php', 'pdfupload_admin_page', 'dashicons-media-text', 80  );
}
// End Adding Admin Menu Items

// Begin Adding Support Page Content
function pdfupload_admin_page(){
?>
    <style>
        <?php include '../wp-content/plugins/PDF-Uploader/support-files/stylesheet.css'; ?>
    </style>
    <div class="wrap">
    
    <div id="contact">



        <div style="text-align:center;">
        
        <img src="http://digitaldesigneronline.com/wp-content/uploads/2017/06/ddo-logo-3d.png" alt="logo" style="display:block;margin:auto;text-align:center;">
        <h1>Secure PDF File Upload</h1>
        <p>Adding a watermark to your PDF files is as easy as uploading the file using the form below.<br>
        Upon successful submission of your PDF file you will receive the uploaded PDF files URL which you can then use when linking to the PDF within any page or post you desire.</p>
        <p>The PDF file when linked to using the URL provided will automatically acquire a watermark when loaded within the browser of the specific logged in user as well as the current date and time.</p>
        <form action="" method="post" enctype="multipart/form-data">
    <h3>Select image to upload:</h3><br>
    <input type="file" name="fileToUpload" id="fileToUpload">
    <input type="submit" value="Upload PDF File" name="submitUpload">
</form>

<?php

    $target_dir = "../wp-content/plugins/chadpdf/engine/";
    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
    $uploadOk = 1;
    $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);

    if(isset($_POST["submitUpload"])) {
        if (file_exists($target_file)) {
        echo "Sorry, file already exists.";
        $uploadOk = 0;
    }
    // Check file size
    if ($_FILES["fileToUpload"]["size"] > 500000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }
    // Allow certain file formats
    if($imageFileType != "pdf") {
        echo "Sorry, only PDF files are allowed.";
        $uploadOk = 0;
    }
    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    // if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            echo "File uploaded successfully use the following URL when linking to your PDF <br> https://digitaldesigneronline.com/wp-content/plugins/chadpdf/engine/simple1.php?filename=" . basename( $_FILES["fileToUpload"]["name"]). "";
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
    }
    // Check if file already exists



    function url_to_path_test($url){
        $url=str_replace(rtrim(get_site_url(),'/').'/', ABSPATH, $url);
        return $url;
    }

    /**
     * FILE DELETION HANDLER
     * I moved it here as it needs to be above the form   
     */
    function delete_file($file) {
        
        if(!unlink( get_home_path() . $file  )) {
            echo "Sorry! your file cannot be deleted. Please try again later";
        }
        else {
            echo "File deleted successfully!";
        }

        
            
    }
    if(isset($_POST['submit'])){

        if(!empty($_POST['file'])) { // check if the checkbox was checked.
            
            foreach($_POST['file'] as $file) {
                // call delete function here.
                delete_file($file);
            }
        }
        else{
            echo "No file selected. you must select at one file to delete.";
        }
    }


?>


    </div>
<?php
if ($handle = opendir('../wp-content/plugins/chadpdf/engine/')) {
    while (false !== ($file = readdir($handle)))
    {
        if ($file != "." && $file != ".." && strtolower(substr($file, strrpos($file, '.') + 1)) == 'pdf')
        {
            $thelist .= '<tr><td><input type="checkbox" name="file[]" value="/wp-content/plugins/chadpdf/engine/'.$file.'"></td><td><a href="http://digitaldesigneronline.com/wp-content/plugins/chadpdf/engine/simple1.php?filename='.$file.'" target="_blank" style="font-size:18px;">'.$file.'</a></td></tr>';
        }
    }
    closedir($handle);
}
?>
<form id="deletionForm" method="post" action="">
<table>
    <?=$thelist?>
</table>
<input type="submit" name="submit" value="Delete Selection">
<script type="text/javascript">
    (function($){
        $("#deletionForm").submit(function(){
            var str = $(this).serialize();
            $.ajax('delete.php', str, function(result){
                alert(result);
            })
            return(false);
        });
    })
</script>
</form>
    </div>

    <?php
}
// End Adding Secure PDF Upload Page Content

