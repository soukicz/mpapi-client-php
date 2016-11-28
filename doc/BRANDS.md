## BRANDS

### Available methods:
**GET**  
This service have only one method, this method provides list of brands.
```
<?php 
...
use MPAPI\Services\Brands;

...

$categories = new Categories($mpapiClient);
...
``` 

#### All BRANDS
Get list of brands.
```
...
$response = $brands->get()->brands();
... 
```

The response contains array of brands:
```
 [
   [
    "title" => "Brand 1 - title",
    "brand_id" => "BRAND 1"
  ],
  [
    "title" => "Brand 2 - title",
    "brand_id" => "BRAND 2"
  ],
  ...
]

```

#### Search in brands
Search brands by phrase in title:
```
...
$response = $categories->get()->searchBrands('BRAND'); 
... 
```

The response contains an array of found brands:
```
 [
   [
    "title" => "Brand 1 - title",
    "category_id" => "BRAND 1"
  ],
  [
    "title" => "Brand 2 - title",
    "category_id" => "BRAND 2"
  ],
  ...
]

```

##### See more:
> **/root/vendor/mallgroup/mpapi-client/Example/BrandsExample.php**