# Easy 2 Gallery 1.6-rc1

A Multilanguage image gallery snippet and module (for MODx Evolution 1.1 and above) that offers features like image browsing, automatic thumbnail creation, size adjusting, watermarking, adding comments, etc..

## Public Veiw:

![Public Veiw 01](https://raw.githubusercontent.com/risingisland/Easy-2-Gallery/1.6-rc1/Home-My-Evolution-Site-01_.png)

![Public Veiw 02](https://raw.githubusercontent.com/risingisland/Easy-2-Gallery/1.6-rc1/Home-My-Evolution-Site-02_.png)

## Manager Veiw:


### Manage images...

![Manager Veiw Files](https://raw.githubusercontent.com/risingisland/Easy-2-Gallery/1.6-rc1/Easy-2-Gallery-1.6-rc1-Files_.png)

### Adjust Image and Thumb Settings...

![Manager Veiw Settings](https://raw.githubusercontent.com/risingisland/Easy-2-Gallery/1.6-rc1/Easy-2-Gallery-1.6-rc1-Settings_.png)

### Available Viewers...

![Manager Veiw Viewers](https://raw.githubusercontent.com/risingisland/Easy-2-Gallery/1.6-rc1/Easy-2-Gallery-1.6-rc1-Viewer_.png)

### Easily translate to your own lang...

![Manager Veiw Translate](https://raw.githubusercontent.com/risingisland/Easy-2-Gallery/1.6-rc1/Easy-2-Gallery-1.6-rc1-Help_.png)


**Installation**

1. Install with  Package Manager Module or Store modules.
   Or, for manual installation, unpack archive in root folder of you modx installation
2. If this is your fresh installation: create a new folder 'gallery', the default path is assets/images/gallery.
   You can change this from the default.config.easy2gallery.php (fresh installation) from this value:
   
   'dir' => 'assets/images/gallery/',
   
   If this is an upgrade, this module will use the existing config.easy2gallery.php values.
3. Create new module, name it anything (Eg: Easy 2 Gallery), type:
```
###########
$o = include_once(MODX_BASE_PATH.'assets/modules/easy2/index.php');
return $o;
###########
```
And click save.

4. Refresh modx manager, then open the module tab.
5. Install following instructions.
6. If install is successful, remove install folder, manually or by clicking delete button. module won't work if this folder exists.



**Upgrade**

1. Unpack archive in root folder of you modx installation
2. If there is one (upgrade from below of E2G 1.4.0-rc4), COPY your existing
            config.easy2gallery.php 
   file to 
            assets/modules/easy2/includes/configs/
   folder.
2. Open the module tab, upgrade following instructions
3. If upgrade is successful, remove install folder, manually or by clicking delete button. module won't work if this folder exists.
4. **_Note:_** As of v1.5.5, the directory "assets/libs" has been changed to "assets/modules/easy2/libs".
   You will need to update the paths for any Viewer, Slideshows and Plugins that are in use via their respective menu.  
5. CLEAR MODX CACHE


**NEW COMMENTS PROCESSING**

Comments functionality has been removed from the class file and now has its own processor.
A new view processor snippet shows the comments on the landing page, and in the popup window.
See comments.readme.txt for full usage information.

***Installation:***

1. Create a new snippet, for this example named " easy2_Landingpage_Comments "
   category: same as easy2gallery
   snippet code:
   ```
   define( 'E2G_MODE', 'true' );
   $e2g_path =MODX_BASE_PATH.'assets/modules/easy2/';
   include $e2g_path.'comments_processor.easy2gallery.php';
   ```

2. Create a new snippet, for this example named " easy2_View_Processor "
   Category: same as easy2gallery
   Snippet code:
   ```
   define( 'E2G_MODE', 'true' );
   $e2g_path =MODX_BASE_PATH.'assets/modules/easy2/';
   include $e2g_path.'view_processor.easy2gallery.php';

   return $output;
   ```

3. You need a landing page, if you don't have one set up yet see the help section in the module for that info.
   
   Content of the landing page:
    ```  
   [!easy2? &landingpage=`47` &lp_img_src=`generated` &lp_w=`450` &lp_h=`450`!]
   ```
   ```
   [!easy2_Landingpage_Comments?
   &ecl_page=`2`
   &captcha=`1`
   &comments_lang=``
   &approved=`1`
   &delay=`30`
   !]
   ```

4. Create a new document for the view processor.
   Doc settings:
     - blank template
     - all other settings off
     - published
   
   Doc content:
   ```
   [!easy2_View_Processor?
   &captcha=`1`
   &captcha_tpl=`captcha_tpl`
   &comments_lang=``
   &approved=`1`
   &delay=`90`
   !]
   ```

5. Open assets/modules/easy2/plugin.easy2gallery.php file in a text editor or
   File manager editor at the top find this line:
   ```
      $e2g_vpl_id = 48;
   ```
   Change the 48 to the id of your view processor document.
   
   Make sure to check the following events on the System Events tab:
      OnWebPageInit
      OnWebPageComplete

   See comments.readme.txt for full usage information.
