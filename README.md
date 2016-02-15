
#EP4Bookx it's a -> EasyPopulate 4.0 fork 
To import Bookx fields by CSV - tested with **Zencart 1.5.4** and **EP4 v4.0.30**

 * @version  0.9.9 - **Still in development, make your changes in a local environment**
 * @see Bookx module for ZenCart
 * Contribution by: @mesnitu
 * Special thanks to @joaosantacruz for putting me in the right track

### Note : Still under heavy tests 
>This are some initial files, that already import BookX fields, but isn't yet suitable in a production enviroment. A lot of testings and ideas going on.
~~It still **only supports the default language**~~
I've tested this with 2129 books. Probably you'll have to adjust the EP4 split records, depending on the server's config and max_execution time. 

### Quick review : 
[Book X](https://sourceforge.net/p/zencartbookx) it's an impressive ZenCart mod made by @philou that introduces a new product type for books to the Zen Cart shop system. 

**EP4Bookx** is a fork of Easy Populate 4.0 (v0.30) to support Bookx fields.
This is an attempt to give a book shop a quick start to get up and running, but also, to make changes when it comes to a large number of books. 
In sum, use the power of Easy Populate 4.0 with BookX. 

For now, this are the supported fields (just the names, no description yet)
#### Supported Fields
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

### Installation

* This is a full EP4 package plus the EP4BookX files. Download and install EasyPopulate as you would.
* Enable Bookx fields in EP configuration page.
  
### Use it (still in idea stage)

~~For now , in EP4 configuration page, you may assign a default genre. ~~
In EP4 language file, you'll find the configurations for default values, or the reports you want to generate. 
ie:
```php 
$report_bookx_subtitle = false;  // no report on missing subtitle
$report_bookx_genre_name = true; // reports all books with missing genres

$bookx_default_author_name = 'Various'; // Default values for empty author names
$bookx_default_binding = ''; // No default value, no binding assign to the book if empty 

```
I've done so, cause in my personal use, there are a lot of books that actually goes to default values, so I don't have to write then down, but mainly, because if genre or author is empty, it wont be on BookX filter. 

>Note: At this stage, not yet decided if the configurations should be on this file or in the EP4 admin interface, because, usually, will have to translate or check this file anyway.

If you already know EP4, the procedures are the same. You'll download the **Complete Products (with Metatags)** , or upload a new one ( also a complete products file ).

#### Multiple Authors and Genres 
Same thing as EP4 categories. Use the **^** as the delimiter. 
For Genres that's it. 
**For Authors** -   
Let's say we have a book with 3 Authors, two writers and one Illustrator.
If you use a default author type (ie: writer)
For each author, a default type must be set 
| v_bookx_author_name  | v_bookx_author_type |
| --------------------------- | ------------------------- |
| Author A^Author B^Author C  | Writer^Illustrator^Writer  |

But, if you use a default author type, or a default author name. If all are empty, they will have Various as Authors and Writer as type. But things can also go this way:

| v_bookx_author_name  | v_bookx_author_type |
| --------------------------- | ------------------------- |
| Author A^Author B           | Writer^Illustrator         |
>Would assign Writer to Author C. Or this :

| v_bookx_author_name  | v_bookx_author_type |
| --------------------------- | ------------------------- |
| Author A^Author B^Author C  |                           |
> Would assign Writer to all this 3 authors 

**Note** only works with default values assign. 

At upload, if empty fields are found and the reports for those fields are set to true, ( authors, publishers, etc... ) a report is generated (still working on it ), with edit link to the book.
I found that there's no need to skip this empty fields, but instead, insert them, and have the possibility to have a more visual way to know witch fields are missing and were.

#### Removing Books with EPBookx
EP4Bookx uses status 10 (same as 9 in EP4), but changed to remove BookX associated fields with the products.
So you would do v_status 10, to remove books.

### Know Issues 
If you've never worked with this csv importers , be aware that "Author A" it's different than "Autor A ", or even more spaces of garbage, that sometimes are present in excel, calc, csv files.

 ## Todo  
 - [X]  export /import with support for languages.
 - [X]  export /import assigned multiple authors.
 - [X]  export /import assigned multiple genres.
 - [ ]  Create a separated file to deal only with bookx fields.
 - [ ]  Create a separated file to deal only with authors description.
  -[ ]  Improve the querys for better performance. 
