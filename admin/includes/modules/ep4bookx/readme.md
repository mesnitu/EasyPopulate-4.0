
# EP4Bookx v0.9.9 - A EasyPopulate 4.0 fork
 
To import Bookx fields by CSV - tested with **Zencart 1.5.4** and **EP4 v4.0.33**

 * @version  0.9.9 - **Still in development, make your changes in a local environment**
 * @see Bookx module for ZenCart
 * Contribution by: @mesnitu
 * Special thanks to **@joaosantacruz** for putting me in the right track when a foreach was a issue.
 * Special thanks to **@mc12345678** the EP4 maintainer, for his patient and valuable experience advises and for making the key class that gave independence not only for this module, but all other future features that might come along.
 

## Quick review : 
[Book X](https://sourceforge.net/p/zencartbookx) it's an impressive ZenCart mod made by @philou that introduces a new product type for books to the Zen Cart shop system. 

**EP4Bookx** is a fork of Easy Populate 4.0 (v0.33) to support Bookx fields. It will not work under previous EP4 versions.
This is an attempt to give a book shop a quick start to get up and running, but also, to make changes when it comes to a large number of books.

In sum, use the power of Easy Populate 4.0 with BookX product type.

It gives the possibility to flexibly export / import with using customize layouts, plus the ability to generate fields reports and the use of default fields names assignment, that could speed up the process of making the csv file, or just to have a fallback name in case of empty one.
For now, this are the supported fields (just the names).
  

### Supported Bookx Fields

* bookx_author_name 
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

### Installation

* This is a full EP4 (v0.33) package plus the EP4BookX files. Download and install EasyPopulate as you would.
* Enable Bookx fields in EP configuration page.

>Note: It's quite possible that in the future EP4Bookx becomes a separated package (a copy / paste ) EP4 featured, as now it fully integrates with EP4, without changing his core files. It's quite normal that EP4 evolution will go much faster, and for me it's not going to be easy to always keep track of new EP4 versions. 
  
### Use it (still in idea stage)

EP4Bookx brings some flexibility to the file and some features, but the main key, is to be as much flexible as possible.

In EP4 configuration page, bellow the enable Bookx data you'll see a enable fields configuration. 
You can enable it there, but also in the **easypopulate_4 page**, where you'll find a quick enable / disable link.

This will bring the fields configuration section, where you can make your changes.

By default, **all fields names are enable, no reports, no default names are set**. 
If no customize layout is created, this is the export configuration. 
If this configuration suits your needs, you may disable this configuration table and simply use the Bookx default export link.

If not, you can create customize export layouts, selecting bellow witch fields you need for your csv file. 

**Only the selected fields and default names** are going to be exported / imported, so it might reduce the amount of database querys in the process improving the performance by removing unnecessary fields, besides it will present a more clean file only with fields that you've choosen.

> ie: You can have a customized full fields export file for one reason, and some minimize customize fields for just consultation.
 
There are no limit to the number of customized layouts, a layout name is required It will save as *layout_your_name*.
You can delete the customize layouts.
If you delete them all, the default configuration is reloaded again.

**Take Note** - The enable / disable fields, are for exporting. And the import, will always read your last customize configuration layout, looking for default names and fields to report.
This is a simple way of taking some control over the import/export. Possible it could get more precise on witch customize layouts one should use on import, but I'm doing this base on my personal needs. I'll need a customize layout to work on import, and have the possibility to download some other customize for consultation.  

### The ISBN Field

It's mandatory one. The only one. **He must be present in the csv file, but it's not required to be filled**.
If for some reason it's not there, nothing ( related to Bookx fields, will be imported).

### Reports
 
Reports are there if you want them too. 
For me are a precious tool (ie: for isbn, or publishers), they will report (at import), if this book field was empty.
In the end of import process, will see a table reporting all those missing fields, with a edit link to the admin products edit page.   

### Defaults Names

You can assign default names to some fields. If so, if a empty field as a default name assign, it will populate those fields with those names.
An example, it's the author type, or the binding. For me 98% are writers. So I just care with the 2% that are not.

> The default names are not used on export, **only on import**. 


On Import, doesn't rely on enable / disable configuration. Only on the reports and defaults fields. If the field is not in the file, EP4 doesn't processe it.  
After your done, you may disable this configuration options, since all your customize layouts will be listed has normal download links. Disabling it, will also not load the jquery used for this configuration, so probably will also increase the import / export process. It really depends on your server.

I've done so, cause in my personal use, there are a lot of books that actually goes to default values, so I don't have to write then down, but mainly, because if genre or author is empty, it wont be on BookX filter. 


### Multiple Authors and Genres 

Same as EP4 categories.
> Note: Probably this delimiter will be on the admin panel in near EP4 versions
 
So use the ** ^ ** as the delimiter.  
For Genres that's it.
 
#### For Authors 
 
Let's say we have a book with 3 Authors, two writers and one Illustrator.

If **you use** the author type in a normal way:

For each author, a default type must, or should be set manually:
 
|**v_bookx_author_name**  || **Author A^Author B^Author C** |

|**v_bookx_author_type**  || **Writer^Illustrator^Writer**  |

Now, using a default author type name ( ie: Writer), all empty fields are populated, with that type.

| **v_bookx_author_name**    ||**Author A^Author B^Author C** |

| **v_bookx_author_type**  ||  **Writer^Illustrator** |

>**It Would assign Writer to Author C.** 

If all 3 Authors were writers, with a default type on, all 3 would have "writer".


But things can also go this way:
Let's say you also have a default author name (ie: Various ). Then both fields are populated:

| **v_bookx_author_name**  || Various 
| **v_bookx_author_type**  || Writer

The same applys to all fields that can have a default name associated. 
**Note** only works with default values assign. 

### Should Know

The export doesn't report or use default names.

The Import, doesn't rely on enable / disable configuration. Only in reports and defaults fields. 
If that field is not on the file, EP4 doesn't processe it.  

>**Note** After your done, you may disable or should the configuration options, since all your customize layouts will be listed has normal download links. Disabling it, will also not load the jquery used for this configuration, so probably will also increase the import / export process. It really depends on your server.


### Removing Books with EP4

EP4Bookx uses status 10 (same procedure as stauts 9 in EP4), but changed to remove BookX associated fields.

So you would place **v_status** as 10, to remove books.

### Know Issues 

If you've never worked with this csv importers , be aware that "Author A" it's different than "Autor A ", or even more spaces of garbage, that sometimes are present in excel, calc, csv files.

**NOTE** While EP4 shines for style simplicity, EP4Bookx comes with some colors and stylesheet and jquery. 
I've made this specially for my personal use because I need it, however, I've try to separated the code from the html and css. 
You'll find a tpl folder, where all related style occurs, where you can change to your desire style. 

## Todo  

 - [X]  export /import with support for languages.
 - [X]  export /import assigned multiple authors.
 - [X]  export /import assigned multiple genres.
 - [X]  Create a separated file to deal only with bookx fields.
 - [ ]  Create a separated file to deal only with authors description.
 - [ ]  Improve the querys for better performance.
 - [ ]  Map Fields
 - [ ]  Check for duplicated ISBN