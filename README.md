# PHP PesDB Scraper

PHP PesDB Scraper Class helps you to scrape pesdb data from html source.

### Example

```php
require_once("pesdb.class.php");

$pes_year = "2019";
$max_page_number = 400;

$pes = new PesDB($pes_year);
for ($i = 1; $i < $max_page_number; $i++) {
	$pes->printData($i);
}
```