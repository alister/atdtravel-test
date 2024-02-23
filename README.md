# Dev tests - API test (v2)

## Alister Bulman, 2024-02-23.

Contact: <alister+AttractionTicketsLtd@abulman.co.uk>

### To Run locally:

Requires: PHP 8.2+
Highly suggested: Symfony-cli to run (with a locally installed PHP8.2-FPM server)

```shell
git clone $REPO
cd atdtravel-test/

symfony composer install
symfony serve --allow-http --no-tls --daemon

# Open browser - typically to:   http://127.0.0.1:8000
```

### Tasks

1. Document how you would explain to another developer the approach you would take to build a product search to match the criteria below.
2. Create the product search page described above using the below criteria.
3. Describe how your approach would differ (if at all) between building this from scratch to integrating it within an existing system (e.g Drupal/Symfony)
4. Implement or describe how you would test the product search.


#### Document how you would explain to another developer the approach you would take to build a product search to match the criteria below.

After a few test-runs of the sample CURL command line, and creating a starter Symfony (webapp) project, I first create a 'ProductSearchCommand', with the various options that will be used in the API query.

Within this structure, I create a Symfony HttpClient service for the API. A 'ProductsRepository' class is written as a convenient Symfony service to call the API, via the HttpClient, and the data is deserialised into a hierarchy of objects that can be output, initially to the screen as part of the console command.

Unit tests are written, with sample API queries to provide the fixtures, and the HTTP requests are mocked for the tests. This ensures repeatability and speed, considering the *very* slow response from the endpoint.

Since it is far more typical to show the results on a webpage, instead of the command line, write a web-interface, calling the same queries. 

## Sample output of the search command

Demonstrating the 'search', 'geo', 'offsets' & 'limit' parameters to the http-query. Note the deliberate overlap of the last-2/first-2 rows from the queries.

```
symfony console app:product-search 'orlando' --geo en-ie --limit 5 ; \
symfony console app:product-search 'orlando' --geo en-ie --limit 7 --offset 3

 ------------- -------------------------------------------- --------
  Destination   Title                                        imgSml
 ------------- -------------------------------------------- --------
  Orlando       All-Day Dining Deal at Aquatica Orlando      image
  Orlando       All-Day Dining Deal at SeaWorld Orlando      image
  Orlando       Boggy Creek Orlando 30-Minute Airboat Ride   image
  Orlando       Dine with Orcas at SeaWorld Orlando          image
  Orlando       Gatorland Orlando Admission Ticket           image
 ------------- -------------------------------------------- --------

 ------------- ------------------------------------------------- --------
  Destination   Title                                             imgSml
 ------------- ------------------------------------------------- --------
  Orlando       Dine with Orcas at SeaWorld Orlando               image
  Orlando       Gatorland Orlando Admission Ticket                image
  Orlando       Go City:  Orlando Explorer Pass                   image
  Orlando       Go City: Orlando All-Inclusive Pass               image
  Orlando       Go City: Orlando All-Inclusive Pass               image
  Orlando       Madame Tussauds Orlando & SEA LIFE Combo Ticket   image
  Orlando       Madame Tussauds Orlando Admission Ticket          image
 ------------- ------------------------------------------------- --------
```

### Create the product search page described above using the below criteria.

See the provided code, and how to run the command, and the webpage.

This was my first opportunity to try Twig Components and the TailWind CSS framework. I used a number of techniques for the webpage that I had seen in the SymfonyCasts "30 Days with LAST Stack" (https://symfonycasts.com/screencast/last-stack/last-stack) and the use of the FlexBox CSS, - seen in the Laracasts (https://laracasts.com/series/laravel-8-from-scratch/episodes/31) video course to provide for product-cards on multiple-rows (in that example, it was blogposts).

I don't routinely write HTML & CSS, and so the quality of the page is not guaranteed, merely serving as an indication of the summary-data that has been retrieved.

### Describe how your approach would differ (if at all) between building this from scratch to integrating it within an existing system (e.g Drupal/Symfony)

This code is built within a Symfony (v7.0) structure, so it is Symfony-services first and using the relevant useful tools/components, such as HttpClient, Cache, Console & Serializer, with Twig & AssetMapper for the webpage version of the search results.

To simplify the front-end, the Tailwind CSS framework is not custom-generated locally, but uses the complete version from the CDN at https://cdn.tailwindcss.com/3.4.1 (for a download of 360KB).

###  Implement or describe how you would test the product search.

An end to end test is infeasible due to speed issues of the API response. However, sending a wide variety of queries, both for the title search and various combinations of all four query parameters, as well as potentially differing the ordering of the parameters in the HTTP request would be a good start. Those tests would however be far better done locally, using the HTTP request as a natural system boundary.

Similar requests with good and bad data can be made with the local system (here, the search webpage within this repository).

A PHP Unit test, returning a mocked HTTP response does already exist in the codebase and was used to help create & debug the deserialisation of the JSON response to the local objects that are used for the output.  Not all the data is currently deserialised into the objects, as only a subset is being used for output at this time.



----

The API query was extraordinarily slow, routinely taking 20+ seconds to return, so in my code, I have added a cache for the query's results. This makes return to previously (recently) fetched pages useful.

The HTTP response was also being marked as un-cachable, which stopped my initial attempt to cache at the HTTP request, so it was done at the post-deserialised object level instead. This makes returning to a previously known page effectively instant for the duration of the cache (currently hard-coded as 1 hour).
