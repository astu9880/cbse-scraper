# CBSE-Results-PHP-Scraper

Using the PHP cURL library for webscraping Class 12 exam results from the Central Board of Secondary Education, India.

## cURL

A simple implementation of the cURL library.

```php
$ch = curl_init($url);
$rollno="regno=".$i;	         	//Define variables to be posted
curl_setopt($ch, CURLOPT_POST, 1);         	//Enable posting
curl_setopt($ch, CURLOPT_POSTFIELDS, $rollno); // data to be posted
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_REFERER, 'http://resultsarchives.nic.in/cbseresults/cbseresults2015/class12/cbse122015_all.htm');
$result = curl_exec($ch);         	//Execute cURL request
curl_close($ch);
```
