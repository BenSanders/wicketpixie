<?php
/**
* The convention for filenames is:
* header.php - included inbetween <head></head> tags
* footer.php - included before the </body> tag
* Please do not use a different file name for these two areas.
**/
define("CUSTOMPATH",TEMPLATEPATH ."/app/custom");
function customcode_add_admin()
{
	add_submenu_page('wicketpixie-admin.php',"WicketPixie Custom Code","Custom Code",'edit_themes',basename(__FILE__),'customcode_admin');
}

/**
* This function checks if the directory set to CUSTOMPATH exists
* or the files within it.
* Arguments:
* $q: The mode; If 1, then we check for the directory;
*     If 2, we check for the file
* $file: A certain file to look for. (not needed if $q == 1)
**/
function checkfs($q,$file = NULL)
{
    clearstatcache();
    if($q == 1) {
        $isdir = file_exists(CUSTOMPATH);
        if($isdir != true) {
            mkdir(CUSTOMPATH,0777);
            return false;
        } else {
            return true;
        }
    } elseif($q == 2) {
        $isfile = file_exists(CUSTOMPATH ."/$file");
        if($isfile != true) {
            touch(CUSTOMPATH ."/$file");
            return false;
        } else {
            return true;
        }
    }
}

/**
* Writes the submitted code to the custom code file.
* Arguments:
* $code: The code to be submitted
* $magick: Whether or not we should strip slashes
**/
function writeto($code,$file,$magick = false)
{
    // Check and make sure everything is created and writable
    checkfs(1);
    checkfs(2,$file);
    
    // Write the submitted code to the file
    file_put_contents(CUSTOMPATH ."/$file",($magick)?$code:stripslashes($code));
}

/**
* This returns the custom code if any. If not, returns an HTML comment.
**/
function fetchcustomcode($file)
{
    if(file_exists(CUSTOMPATH) && file_exists(CUSTOMPATH ."/$file")) {
        return file_get_contents(CUSTOMPATH ."/$file");
    } else {
        return "<!-- No custom code found, add code on the WicketPixie Custom Code admin page. -->";
    }
}
    
/**
* The admin page where the user enters the custom header code.
*/
function customcode_admin()
{
    if ( $_GET['page'] == basename(__FILE__) ) {
        if ( 'add' == $_REQUEST['action'] ) {
            if('header' == $_POST['file']) {
                writeto($_POST['code'],"header.php");
            } elseif('footer' == $_POST['file']) {
                writeto($_POST['code'],"footer.php");
            }
        }			
        elseif ( 'clear' == $_REQUEST['action'] ) {
            if('header' == $_POST['file']) {
                unlink(CUSTOMPATH .'/header.php');
            } elseif('footer' == $_POST['file']) {
                unlink(CUSTOMPATH .'/footer.php');
            }
        }
    }
    ?>
    <?php if ( isset( $_REQUEST['add'] ) ) { ?>
    <div id="message" class="updated fade"><p><strong><?php echo __('Custom Code saved.'); ?></strong></p></div>
    <?php } ?>
        <div class="wrap">
        
            <div id="admin-options">
                <h2><?php _e('Custom Code'); ?></h2>
                <p>Allows you to enter special code (HTML, PHP, JavaScript) which will be included in the site template.</p>
                <h3>Custom Header</h3>
                <p>Enter HTML markup, PHP code, or JavaScript that you would like to appear between the &lt;head&gt; and &lt;/head&gt; tags of your site.</p>
                <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>?page=customcode.php&amp;add=true" class="form-table">
                    <h4>Edit Custom Header file</h4>
                    <p><textarea name="code" id="code" style="border: 1px solid #999999;" cols="80" rows="25" /><?php echo fetchcustomcode("header.php"); ?></textarea></p>
                    <p class="submit">
                        <input name="save" type="submit" value="Save Custom Header" /> 
                        <input type="hidden" name="action" value="add" />
                        <input type="hidden" name="file" value="header" />
                    </p>
                </form>
                <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>?page=customcode.php&amp;clear=true" class="form-table">
                    <h4>Clear custom header</h4>
                    <p>WARNING: This will delete all custom code you have entered for your header, if you want to continue, click 'Clear Custom Header'</p>
                    <p class="submit">
                        <input name="clear" type="submit" value="Clear Custom Header" />
                        <input type="hidden" name="action" value="clear" />
                        <input type="hidden" name="file" value="header" />
                    </p>
                </form>
                <h3>Custom Footer</h3>
                <p>Enter HTML markup, PHP code, or JavaScript that you would like to appear just before the &lt;/bodygt; tag of your site.</p>
                <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>?page=customcode.php&amp;add=true" class="form-table">
                    <h4>Edit Custom Footer file</h4>
                    <p><textarea name="code" id="code" style="border: 1px solid #999999;" cols="80" rows="25" /><?php echo fetchcustomcode("footer.php"); ?></textarea></p>
                    <p class="submit">
                        <input name="save" type="submit" value="Save Custom Footer" /> 
                        <input type="hidden" name="action" value="add" />
                        <input type="hidden" name="file" value="footer" />
                    </p>
                </form>
                <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>?page=customcode.php&amp;clear=true" class="form-table">
                    <h4>Clear custom footer</h4>
                    <p>WARNING: This will delete all custom code you have entered for your footer, if you want to continue, click 'Clear Custom Footer'</p>
                    <p class="submit">
                        <input name="clear" type="submit" value="Clear Custom Footer" />
                        <input type="hidden" name="action" value="clear" />
                        <input type="hidden" name="file" value="footer" />
                    </p>
                </form>
            </div>
            <?php include_once('advert.php'); ?>
<?php
}

/**
* This is called in header.php and displays the custom header code.
**/
function wp_customheader()
{
    echo fetchcustomcode("header.php");
}
/**
* This is called in footer.php and displays the custom footer code.
**/
function wp_customfooter()
{
    echo fetchcustomcode("footer.php");
}

add_action('admin_menu', 'customcode_add_admin');
?>
