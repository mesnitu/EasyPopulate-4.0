
# EP4Bookx v0.9.9 -rc2 - A EasyPopulate 4.0 fork
 
To import Bookx fields by CSV - tested with **Zencart 1.5.5** and **EP4  v4.0.34a**

 * @version  0.9.9 rc2 - **Still in development, make your changes in a local environment**
 * @see Bookx module for ZenCart
 * Contribution by: @mesnitu
 * Special thanks to **@joaosantacruz** for putting me in the right track when a foreach was a issue.
 * Special thanks to **@mc12345678** the EP4 maintainer, for his patient and valuable experience advises and for making the key class that gave independence not only for this module, but all other future features that might come along.
 

- [Supported Bookx Fields](#supported-bookx-fields)
- [Installation](#installation)
- [How To Use](#how-to-use)
- [Multiple Authors and Genres](#multiple-Authors-and-Genres)
- [The ISBN Field](#the-isbn-Field)
- [Reports](#reports)
- [Defaults Names](#defaults-names)
- [Multiple Authors / Genres](#multiple-authors-and-genres)
- [Manipulate Images](#manipulate-images)
- [create an anchor](#Should-Know)
- [Removing Books with EP4Bookx](#removing-books-with-ep4ookx)


## Quick review : 
[Book X](https://sourceforge.net/p/zencartbookx) it's an impressive ZenCart mod made by @philou that introduces a new product type for books to the Zen Cart shop system. 

**EP4Bookx** is a fork of Easy Populate 4.0 (v0.33) to support Bookx fields. It will not work under previous EP4 versions.
This is an attempt to give a book shop a quick start to get up and running, but also, to make changes when it comes to a large number of books.

In sum, use the power of Easy Populate 4.0 with BookX product type.

It gives the possibility to flexibly export / import using customize layouts, plus the ability to generate fields reports and the use of default fields names assignment, that could speed up the process of making the csv file, or just to have a fallback name in case of empty one.
For now, this are the supported fields (just the names).
  

### Supported Bookx Fields

* bookx_author_name
* bookx_author_description
* bookx_author_image
* bookx_author_type
* bookx_subtitle    
* bookx_genre_name
* bookx_publisher_name     
* bookx_series_name       
* bookx_imprint
* bookx_binding
* bookx_printing
* bookx_condition
* bookx_isbn
* bookx_size
* bookx_volume
* bookx_pages
* bookx_publishing_date

Besides this Bookx fields, the EP4Bookx export file has others fields available belonging to the normal product type, such as categories , weight, metatags, manufacturers, special price, date available. Except for categories ( mandatory for new products ), they are configurable whether to be present in the export or not.

#### Note on 0.9.9rc2 

Ep4Bookx is one of the most widely used scripts!...Well, at least for me :simple_smile:
So I've also added the ability to manipulate products (book) images and author images, plus rewards points fields (individual products), in sum, some stuff that I use on a daily base.
They are all optional. 

### Installation

* This is a full EP4 (v0.33) package plus the EP4BookX files. Download and install EasyPopulate as you would.
* Enable Bookx fields in EP configuration page.

  
### How To Use

EP4Bookx brings some flexibility to the file and some features, but the main key, is to be as much flexible as possible.

In EP4 configuration page, bellow the enable **Enable Products Bookx** you'll see a enable fields configuration. 
You can enable it there, but also in the **easypopulate_4 page**, where you'll find a quick enable / disable link.

This will bring the fields configuration section, where you can make your changes.

By default, **all fields names are enable, no reports, no default names are set**. 
If no custom layout is created, this is the export configuration
If this configuration suits your needs, you may disable this configuration table and simply use the Bookx default export link.

If not, you can create customized export layouts, selecting bellow which fields you need for your csv file. 

**Only the selected fields and default names** are going to be exported / imported, so it might reduce the amount of database queries in the process, improving the performance by removing unnecessary fields, besides it will present a more clean file only with fields that you've chosen.

> ie: You can have a customized full fields export file for one reason, and some minimize customize fields for just consultation.
 
There are no limit to the number of customized layouts, a layout name is required, It will save as *layout_your_name*.
You can delete the customized layouts.
If you delete them all, the default configuration is reloaded again.

**Take Note** - The enable / disable fields, are for exporting. And the import, will always read your last customize configuration layout, looking for default names and fields to report.
This is a simple way of taking some control over the import/export. Possible it could get more precise on witch customize layouts one should use on import, but I'm doing this base on my personal needs. I'll need a customize layout to work on import, and have the possibility to download some other customize for consultation.  

### The ISBN Field

It's mandatory one. **It must be present in the csv file, but it's not required to be filled**.
If for some reason it's not there, nothing ( related to Bookx fields, will be imported).

### Reports
 
Reports are there if you want.  They will report (at **import**), if a book field was empty.
In the end of import process, you will see a table reporting all those missing fields, with a edit link to the admin products page.   

### Defaults Names

You can assign default names to some fields. If so, if a empty field in your csv file, as a default name assign, it will populate those fields with those names.
An example, it's the author type, or the binding. For me 98% are writers. So I just care with the 2% that are not.

> The default names are not (yet) used on export, **only on import**. 


The **Import**, doesn't rely on the enable / disable configuration. Only on the reports and defaults fields. If the field is not in the file, EP4Bookx does not process it.  
After your done creating a customized layout, you may disable this fields configuration options, since all your customized layouts will be listed has normal download links.

I've done so, cause in my personal use, there are a lot of books that actually goes to a default values, so I don't have to write then down, but mainly, because if genre or author is empty, it wont be on BookX filter.


### Multiple Authors and Genres 

Same as EP4 categories.
> Note: Probably this delimiter will be on the admin panel in near EP4 versions
 
So use the `^` as the delimiter.  
For Genres that's it.
 
#### For Authors 
 
Let's say we have a book with 3 Authors, two writers and one Illustrator.

If **you use** the author type in a normal way, for each author, a author type must or should be set manually:

| v_bookx_author_name | v_bookx_author_type |
----------------------|----------------------
Author A^Author B^Author C | Writer^Illustrator^Writer

Now, using a customized author type field name ( ie: Writer), all empty fields are populated, with that type.

| v_bookx_author_name | v_bookx_author_type |
----------------------|----------------------
 Author A^Author B^Author C | Writer^Illustrator

>**It Would assign Writer to Author C.** 

Another example: If all 3 Authors were writers, with a customized author type field, all 3 would have "writer".

| v_bookx_author_name   | v_bookx_author_type |
----------------------|----------------------
  Author A^Author B^Author C |                |
         
But things can also go this way:
Let's say you also have a customized author name (ie: Various ). Then both fields are populated:

| v_bookx_author_name | v_bookx_author_type |
----------------------|----------------------
|                      |                     |

> Would populate has v_bookx_author_name = Various and v_bookx_author_type =  Writer

The same applies to all fields that can have a default name associated.
**Note** only works when default values are assign.

### Should Know

The export does not report or use default names.

The Import, does not rely on enable / disable configuration. Only in reports and defaults fields. 
If that field is not on the file, EP4 does not process it. 


### Manipulating Images 

There's a new file in town at **/admin/includes/extra_configures/ep4book_extra_configures.php**, where some extra configurations can be made. The purpose is to reduce the amount of work and time, preparing books and authors images.
 
> **Note** In some other release, I intend to move this configurations to the admin panel. 

In this files all starts with setting to true or false:

``` 
        define('EP4BOOKX_MANIPULATE_IMAGES', true); 
```

Set the authors folder name, image prefix ( could be empty), image extension ( for now, only deals with one type), image width and height

``` 
     
     //Temporary images folder to be resized / moved
     define('EP4BOOKX_IMAGES_SRC_TEMP_FOLDER', DIR_FS_CATALOG . DIR_WS_IMAGES . 'temp/');
     
     // Add a prefix to image or leavet blank ''. Image type to look for
     define('EP4BOOKX_MANIPULATE_IMAGES_PREFIX', 'prefix_');
     define('EP4BOOKX_MANIPULATE_IMAGES_EXTENSION', '.jpg');
     
     // Authors Image Folder. Just the name
     define('EP4BOOKX_AUTHORS_IMAGE_FOLDER', 'authors');
     define('EP4BOOKX_AUTHORS_IMAGE_FOLDER_PATH', DIR_FS_CATALOG . DIR_WS_IMAGES . EP4BOOKX_AUTHORS_IMAGE_FOLDER . '/');
     
     // For resize images
     define('EP4BOOKX_AUTHORS_IMAGE_WIDTH','400');
     define('EP4BOOKX_AUTHORS_IMAGE_HEIGHT','400');
     
     define('EP4BOOKX_BOOK_IMAGE_WIDTH','1024');
     define('EP4BOOKX_BOOK_IMAGE_HEIGHT','1024');
```

#### Images How to 

> Still in early testing but working

 * `Books Images` can be downloaded or placed at the `images/temporary folder` to be renamed, resized and moved to the destination folder.
 * `Author Images` for now, can only be downloaded. For multiple authors use the `^` categorie delimiter. 

The temporary book images **must have the model number** ( maybe in another release this could be a option adding the use of ISBN). 
In `v_products_image`, use number `9` as image name. If you leave this empty or use some other path, it will be process as EP4 default way.
 
* Downloading 
    * Simple paste the image url. 
* Local : Use number 9 
    * It will look in your `temp` folder for the model, rename to  `yourPrefix_the_book_title_9789895623214.jpg`, resize and move to destination folder.

The `Destination` folder will be the capitalize **Manufacturer/** name ( maybe in another release the use the Publisher name). example: images/ThePublisher/the_book_title_9789895623214.jpg

**Important Note** To clean the book title and Manufacturer, I'm using the Ceon URI class, since I'm using this module. You can change this function or to use some other way of cleaning the names. Look at extra functions in `easypopulate_4_bookx_functions` for.

```function cleanImageName($post_name, $type = null) {```

The images are processed after all data as been imported to the database.

>Caution: There are some downloaded images that are not processed. A report is generated. I still didn't had the time to check why this occurs. Ideally in the future, would like to use Image Handler module to manipulate images, trying not to duplicate the same functionalitys in several files. Still, it's simple and much faster to ajust a few missing images, than to be downloading, resizing, renaming,and moving them online, etc...

 
### Removing Books with EP4Bookx

EP4Bookx uses status 10 (same procedure as status 9 in EP4), but changed to remove BookX associated fields.

So you would place **v_status** to `10`, to remove books.

### Know Issues 

If you've never worked with this csv importers, be aware that "Author A" it's different than "Author  A ", or even more spaces of garbage, that sometimes are present in excel, calc, csv files.

**NOTE** While EP4 shines for style simplicity, EP4Bookx comes with some stylesheets and jquery. Has of EP4Bookx 0.9.9rc2, it's using the already present zencart bootstrap

You will find a /tpl folder, where all related style occurs, where you can change to your desire style. 

## Todo  

 - [X]  export /import with support for languages.
 - [X]  export /import assigned multiple authors.
 - [X]  export /import assigned multiple genres.
 - [X]  Create a separated file to deal only with bookx fields.
 - [ ]  ~~Create a separated file to deal only with authors description.~~
 - [X]  Improve the queries for better performance.
 - [ ]  ~~Map Fields~~
 - [ ]  Check for duplicated ISBN
 - [ ]  Improve the GD functionality 
 - [ ]  Create a upload for images related to the new import process
 - [ ]  Delete temp images
 - [ ]  Use the proper $zco_notifiers to be able to export using EP4 Filters.
 - [ ]  When all set, simplify the read me file
