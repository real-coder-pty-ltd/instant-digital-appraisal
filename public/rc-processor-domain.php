<?php

$uniqueid = uniqid();

// Check if the address is set in the POST or GET request
if (isset($_POST['address']) || isset($_GET['address'])) {
    $location = isset($_POST['address']) ? $_POST['address'] : $_GET['address'];
} else {
    echo 'No Address found. Exiting.';
    return;
}

// Dynamically determine the path to wp-load.php
require_once dirname(dirname(dirname(dirname(dirname(__FILE__))))).'/wp/wp-load.php';

$location = '102B/8 Cowper Street, Parramatta NSW 2150';
$extracted_property_id = 'FO-2157-MZ';
// $latitude = '-33.82167';
// $longitude = '151.00708';
// $state = 'NSW';
// $state = 
// $suburb = 'Parramatta';
$postcode = '2150';

// $fetched_property_suggest = json_encode(rc_domain_fetch_property_suggest($location));
$fetched_property_suggest = '
[
  {
    "address": "102B/8 Cowper Street, Parramatta NSW 2150",
    "addressComponents": {
      "unitNumber": "102B",
      "streetNumber": "8",
      "streetName": "Cowper",
      "streetType": "St",
      "streetTypeLong": "Street",
      "suburb": "Parramatta",
      "postCode": "2150",
      "state": "NSW"
    },
    "id": "FO-2157-MZ",
    "relativeScore": 100
  },
  {
    "address": "102A/8 Cowper Street, Parramatta NSW 2150",
    "addressComponents": {
      "unitNumber": "102A",
      "streetNumber": "8",
      "streetName": "Cowper",
      "streetType": "St",
      "streetTypeLong": "Street",
      "suburb": "Parramatta",
      "postCode": "2150",
      "state": "NSW"
    },
    "id": "MF-2642-YN",
    "relativeScore": 33
  },
  {
    "address": "102/25 Cowper Street, Parramatta NSW 2150",
    "addressComponents": {
      "unitNumber": "102",
      "streetNumber": "25",
      "streetName": "Cowper",
      "streetType": "St",
      "streetTypeLong": "Street",
      "suburb": "Parramatta",
      "postCode": "2150",
      "state": "NSW"
    },
    "id": "WC-2856-VO",
    "relativeScore": 30
  },
  {
    "address": "102/30 Cowper Street, Parramatta NSW 2150",
    "addressComponents": {
      "unitNumber": "102",
      "streetNumber": "30",
      "streetName": "Cowper",
      "streetType": "St",
      "streetTypeLong": "Street",
      "suburb": "Parramatta",
      "postCode": "2150",
      "state": "NSW"
    },
    "id": "JL-0769-CL",
    "relativeScore": 30
  },
  {
    "address": "102/36-46 Cowper Street, Parramatta NSW 2150",
    "addressComponents": {
      "unitNumber": "102",
      "streetNumber": "36-46",
      "streetName": "Cowper",
      "streetType": "St",
      "streetTypeLong": "Street",
      "suburb": "Parramatta",
      "postCode": "2150",
      "state": "NSW"
    },
    "id": "RA-3160-QH",
    "relativeScore": 30
  },
  {
    "address": "102B/3 Broughton Street, Parramatta NSW 2150",
    "addressComponents": {
      "unitNumber": "102B",
      "streetNumber": "3",
      "streetName": "Broughton",
      "streetType": "St",
      "streetTypeLong": "Street",
      "suburb": "Parramatta",
      "postCode": "2150",
      "state": "NSW"
    },
    "id": "GI-0471-BV",
    "relativeScore": 29
  },
  {
    "address": "1002B/8 Cowper Street, Parramatta NSW 2150",
    "addressComponents": {
      "unitNumber": "1002B",
      "streetNumber": "8",
      "streetName": "Cowper",
      "streetType": "St",
      "streetTypeLong": "Street",
      "suburb": "Parramatta",
      "postCode": "2150",
      "state": "NSW"
    },
    "id": "TU-4894-TE",
    "relativeScore": 28
  },
  {
    "address": "105B/8 Cowper Street, Parramatta NSW 2150",
    "addressComponents": {
      "unitNumber": "105B",
      "streetNumber": "8",
      "streetName": "Cowper",
      "streetType": "St",
      "streetTypeLong": "Street",
      "suburb": "Parramatta",
      "postCode": "2150",
      "state": "NSW"
    },
    "id": "GO-7943-VN",
    "relativeScore": 28
  },
  {
    "address": "1103A/8 Cowper Street, Parramatta NSW 2150",
    "addressComponents": {
      "unitNumber": "1103A",
      "streetNumber": "8",
      "streetName": "Cowper",
      "streetType": "St",
      "streetTypeLong": "Street",
      "suburb": "Parramatta",
      "postCode": "2150",
      "state": "NSW"
    },
    "id": "PN-9281-QE",
    "relativeScore": 28
  },
  {
    "address": "1105A/8 Cowper Street, Parramatta NSW 2150",
    "addressComponents": {
      "unitNumber": "1105A",
      "streetNumber": "8",
      "streetName": "Cowper",
      "streetType": "St",
      "streetTypeLong": "Street",
      "suburb": "Parramatta",
      "postCode": "2150",
      "state": "NSW"
    },
    "id": "GI-5835-EL",
    "relativeScore": 28
  },
  {
    "address": "1106B/8 Cowper Street, Parramatta NSW 2150",
    "addressComponents": {
      "unitNumber": "1106B",
      "streetNumber": "8",
      "streetName": "Cowper",
      "streetType": "St",
      "streetTypeLong": "Street",
      "suburb": "Parramatta",
      "postCode": "2150",
      "state": "NSW"
    },
    "id": "OW-8160-RL",
    "relativeScore": 28
  },
  {
    "address": "1401A/8 Cowper Street, Parramatta NSW 2150",
    "addressComponents": {
      "unitNumber": "1401A",
      "streetNumber": "8",
      "streetName": "Cowper",
      "streetType": "St",
      "streetTypeLong": "Street",
      "suburb": "Parramatta",
      "postCode": "2150",
      "state": "NSW"
    },
    "id": "SL-1110-GO",
    "relativeScore": 28
  },
  {
    "address": "1402A/8 Cowper Street, Parramatta NSW 2150",
    "addressComponents": {
      "unitNumber": "1402A",
      "streetNumber": "8",
      "streetName": "Cowper",
      "streetType": "St",
      "streetTypeLong": "Street",
      "suburb": "Parramatta",
      "postCode": "2150",
      "state": "NSW"
    },
    "id": "WA-3027-FS",
    "relativeScore": 28
  },
  {
    "address": "1403B/8 Cowper Street, Parramatta NSW 2150",
    "addressComponents": {
      "unitNumber": "1403B",
      "streetNumber": "8",
      "streetName": "Cowper",
      "streetType": "St",
      "streetTypeLong": "Street",
      "suburb": "Parramatta",
      "postCode": "2150",
      "state": "NSW"
    },
    "id": "MJ-6547-OV",
    "relativeScore": 28
  },
  {
    "address": "1503A/8 Cowper Street, Parramatta NSW 2150",
    "addressComponents": {
      "unitNumber": "1503A",
      "streetNumber": "8",
      "streetName": "Cowper",
      "streetType": "St",
      "streetTypeLong": "Street",
      "suburb": "Parramatta",
      "postCode": "2150",
      "state": "NSW"
    },
    "id": "OA-5458-MY",
    "relativeScore": 28
  },
  {
    "address": "1601A/8 Cowper Street, Parramatta NSW 2150",
    "addressComponents": {
      "unitNumber": "1601A",
      "streetNumber": "8",
      "streetName": "Cowper",
      "streetType": "St",
      "streetTypeLong": "Street",
      "suburb": "Parramatta",
      "postCode": "2150",
      "state": "NSW"
    },
    "id": "YQ-3118-DL",
    "relativeScore": 28
  },
  {
    "address": "1603B/8 Cowper Street, Parramatta NSW 2150",
    "addressComponents": {
      "unitNumber": "1603B",
      "streetNumber": "8",
      "streetName": "Cowper",
      "streetType": "St",
      "streetTypeLong": "Street",
      "suburb": "Parramatta",
      "postCode": "2150",
      "state": "NSW"
    },
    "id": "PO-1648-GY",
    "relativeScore": 28
  },
  {
    "address": "201A/8 Cowper Street, Parramatta NSW 2150",
    "addressComponents": {
      "unitNumber": "201A",
      "streetNumber": "8",
      "streetName": "Cowper",
      "streetType": "St",
      "streetTypeLong": "Street",
      "suburb": "Parramatta",
      "postCode": "2150",
      "state": "NSW"
    },
    "id": "YH-5024-YD",
    "relativeScore": 28
  },
  {
    "address": "201B/8 Cowper Street, Parramatta NSW 2150",
    "addressComponents": {
      "unitNumber": "201B",
      "streetNumber": "8",
      "streetName": "Cowper",
      "streetType": "St",
      "streetTypeLong": "Street",
      "suburb": "Parramatta",
      "postCode": "2150",
      "state": "NSW"
    },
    "id": "OM-4352-DH",
    "relativeScore": 28
  },
  {
    "address": "2B/8 Cowper Street, Parramatta NSW 2150",
    "addressComponents": {
      "unitNumber": "2B",
      "streetNumber": "8",
      "streetName": "Cowper",
      "streetType": "St",
      "streetTypeLong": "Street",
      "suburb": "Parramatta",
      "postCode": "2150",
      "state": "NSW"
    },
    "id": "YM-1682-WA",
    "relativeScore": 28
  }
]';
$extracted_property_suggest = rc_domain_extract_property_suggest($fetched_property_suggest);

// $fetched_property = json_encode(rc_domain_fetch_property($property_id));
$fetched_property = '
{
  "canonicalUrl": "https://www.domain.com.au/property-profile/102b-8-cowper-street-parramatta-nsw-2150",
  "history": {
    "sales": [
      {
        "agency": "Belle Property Parramatta",
        "agencyId": 5927,
        "date": "2019-10-10",
        "daysOnMarket": 23,
        "documentedAsSold": true,
        "price": 755000,
        "reportedAsSold": true,
        "suppressPrice": false,
        "suppressDetails": false,
        "type": "Private Treaty - Sold",
        "url": "https://www.domain.com.au/2015653781",
        "first": {
          "advertisedDate": "2019-09-17",
          "advertisedPrice": 680000,
          "agency": "Belle Property Parramatta",
          "agencyId": 5927,
          "source": "Domain",
          "suppressDetails": false,
          "suppressPrice": false,
          "type": "Private Treaty - For Sale"
        },
        "id": 143723707921577800,
        "last": {
          "advertisedDate": "2019-10-10",
          "agency": "Belle Property Parramatta",
          "agencyId": 5927,
          "source": "Domain",
          "suppressDetails": false,
          "suppressPrice": true,
          "type": "Auction - For Sale"
        }
      },
      {
        "date": "2008-03-05",
        "documentedAsSold": true,
        "price": 481650,
        "reportedAsSold": false,
        "suppressPrice": false,
        "suppressDetails": false,
        "type": "Private Treaty - Sold",
        "first": {
          "suppressDetails": false
        },
        "id": 139510707921577800,
        "last": {
          "suppressDetails": false
        }
      }
    ],
    "rentals": [
      {
        "first": {
          "advertisedDate": "2024-11-07",
          "agency": "Savaa Properties",
          "agencyId": 26871,
          "source": "Domain",
          "suppressDetails": false,
          "suppressPrice": true,
          "type": "For Rent - Lease"
        },
        "id": 245601707921577800,
        "last": {
          "advertisedDate": "2024-11-09",
          "agency": "Savaa Properties",
          "agencyId": 26871,
          "source": "Domain",
          "suppressDetails": false,
          "suppressPrice": true,
          "type": "For Rent - Lease"
        }
      },
      {
        "first": {
          "advertisedDate": "2018-01-29",
          "advertisedPrice": 620,
          "agency": "Belle Property Parramatta",
          "agencyId": 5927,
          "source": "Domain",
          "suppressDetails": false,
          "suppressPrice": false,
          "type": "For Rent - Lease"
        },
        "id": 243127707921577800,
        "last": {
          "advertisedDate": "2018-02-14",
          "agency": "Belle Property Parramatta",
          "agencyId": 5927,
          "source": "Domain",
          "suppressDetails": false,
          "suppressPrice": true,
          "type": "For Rent - Lease"
        }
      },
      {
        "first": {
          "advertisedDate": "2015-12-15",
          "agency": "Belle Property Parramatta",
          "agencyId": 5927,
          "source": "Domain",
          "suppressDetails": false,
          "suppressPrice": true,
          "type": "For Rent - Lease"
        },
        "id": 242351707921577800,
        "last": {
          "advertisedDate": "2016-01-10",
          "agency": "Belle Property Parramatta",
          "agencyId": 5927,
          "source": "Domain",
          "suppressDetails": false,
          "suppressPrice": true,
          "type": "For Rent - Lease"
        }
      }
    ]
  },
  "onMarketTypes": [
    "Rent"
  ],
  "status": "OnMarket",
  "address": "102B/8 Cowper Street, Parramatta NSW 2150",
  "addressCoordinate": {
    "lat": -33.82167,
    "lon": 151.00708
  },
  "addressId": 38058172,
  "adverts": [
    {
      "onMarketTypes": [
        "Rent"
      ],
      "advertId": 17289951,
      "url": "https://www.domain.com.au/17289951"
    }
  ],
  "bathrooms": 2,
  "bedrooms": 3,
  "carSpaces": 2,
  "created": "2023-04-11T15:59:01.000Z",
  "features": [
    "Aircondition",
    "Balcony",
    "Garden",
    "Gas",
    "Parking"
  ],
  "flatNumber": "102B",
  "id": "FO-2157-MZ",
  "internalArea": 142,
  "isResidential": true,
  "lotNumber": "134",
  "photos": [
    {
      "imageType": "Property",
      "advertId": 17289951,
      "date": "2024-11-07T00:00:00Z",
      "fullUrl": "https://bucket-api.domain.com.au/v1/bucket/image/17289951_7_1_241107_123455-w1106-h1067",
      "rank": 1
    },
    {
      "imageType": "Property",
      "advertId": 17289951,
      "date": "2024-11-07T00:00:00Z",
      "fullUrl": "https://bucket-api.domain.com.au/v1/bucket/image/17289951_4_1_241107_123453-w1600-h1200",
      "rank": 2
    },
    {
      "imageType": "Property",
      "advertId": 17289951,
      "date": "2024-11-07T00:00:00Z",
      "fullUrl": "https://bucket-api.domain.com.au/v1/bucket/image/17289951_5_1_241107_123453-w4000-h1848",
      "rank": 3
    },
    {
      "imageType": "Property",
      "advertId": 17289951,
      "date": "2024-11-07T00:00:00Z",
      "fullUrl": "https://bucket-api.domain.com.au/v1/bucket/image/17289951_6_1_241107_123453-w4000-h1848",
      "rank": 4
    },
    {
      "imageType": "Property",
      "advertId": 17289951,
      "date": "2024-11-07T00:00:00Z",
      "fullUrl": "https://bucket-api.domain.com.au/v1/bucket/image/17289951_2_1_241107_123453-w978-h610",
      "rank": 5
    },
    {
      "imageType": "Property",
      "advertId": 17289951,
      "date": "2024-11-07T00:00:00Z",
      "fullUrl": "https://bucket-api.domain.com.au/v1/bucket/image/17289951_3_1_241107_123453-w980-h622",
      "rank": 6
    },
    {
      "imageType": "Property",
      "advertId": 17289951,
      "date": "2024-11-07T00:00:00Z",
      "fullUrl": "https://bucket-api.domain.com.au/v1/bucket/image/17289951_1_1_241107_123453-w982-h605",
      "rank": 7
    },
    {
      "imageType": "Property",
      "advertId": 2015653781,
      "date": "2019-09-17T00:00:00Z",
      "fullUrl": "https://bucket-api.domain.com.au/v1/bucket/image/2015653781_1_1_190917_070901-w1799-h1200",
      "rank": 1
    },
    {
      "imageType": "Property",
      "advertId": 2015653781,
      "date": "2019-09-17T00:00:00Z",
      "fullUrl": "https://bucket-api.domain.com.au/v1/bucket/image/2015653781_2_1_190917_070901-w1800-h1200",
      "rank": 2
    },
    {
      "imageType": "Property",
      "advertId": 2015653781,
      "date": "2019-09-17T00:00:00Z",
      "fullUrl": "https://bucket-api.domain.com.au/v1/bucket/image/2015653781_3_1_190917_070901-w1800-h1200",
      "rank": 3
    },
    {
      "imageType": "Property",
      "advertId": 2015653781,
      "date": "2019-09-17T00:00:00Z",
      "fullUrl": "https://bucket-api.domain.com.au/v1/bucket/image/2015653781_4_1_190917_070901-w1800-h1200",
      "rank": 4
    },
    {
      "imageType": "Property",
      "advertId": 2015653781,
      "date": "2019-09-17T00:00:00Z",
      "fullUrl": "https://bucket-api.domain.com.au/v1/bucket/image/2015653781_5_1_190917_070901-w1800-h1200",
      "rank": 5
    },
    {
      "imageType": "Property",
      "advertId": 2015653781,
      "date": "2019-09-17T00:00:00Z",
      "fullUrl": "https://bucket-api.domain.com.au/v1/bucket/image/2015653781_6_1_190917_070901-w1800-h1200",
      "rank": 6
    },
    {
      "imageType": "Property",
      "advertId": 2015653781,
      "date": "2019-09-17T00:00:00Z",
      "fullUrl": "https://bucket-api.domain.com.au/v1/bucket/image/2015653781_7_1_190917_070901-w1799-h1200",
      "rank": 7
    },
    {
      "imageType": "Property",
      "advertId": 2015653781,
      "date": "2019-09-17T00:00:00Z",
      "fullUrl": "https://bucket-api.domain.com.au/v1/bucket/image/2015653781_8_1_190917_070901-w1799-h1200",
      "rank": 8
    },
    {
      "imageType": "FloorPlan",
      "advertId": 2015653781,
      "date": "2019-09-17T00:00:00Z",
      "fullUrl": "https://bucket-api.domain.com.au/v1/bucket/image/2015653781_9_3_190917_070901-w1600-h1200",
      "rank": 9
    },
    {
      "imageType": "FloorPlan",
      "advertId": 2015653781,
      "date": "2019-09-17T00:00:00Z",
      "fullUrl": "https://bucket-api.domain.com.au/v1/bucket/image/2015653781_10_3_190917_070901-w1600-h1200",
      "rank": 10
    },
    {
      "imageType": "Property",
      "advertId": 11948981,
      "date": "2018-01-29T00:00:00Z",
      "fullUrl": "https://bucket-api.domain.com.au/v1/bucket/image/11948981_1_pi_180208_014552-w1000-h667",
      "rank": 1
    },
    {
      "imageType": "Property",
      "advertId": 11948981,
      "date": "2018-01-29T00:00:00Z",
      "fullUrl": "https://bucket-api.domain.com.au/v1/bucket/image/11948981_2_pi_180208_014553-w1024-h683",
      "rank": 2
    },
    {
      "imageType": "Property",
      "advertId": 11948981,
      "date": "2018-01-29T00:00:00Z",
      "fullUrl": "https://bucket-api.domain.com.au/v1/bucket/image/11948981_3_pi_180208_014552-w960-h683",
      "rank": 3
    },
    {
      "imageType": "Property",
      "advertId": 11948981,
      "date": "2018-01-29T00:00:00Z",
      "fullUrl": "https://bucket-api.domain.com.au/v1/bucket/image/11948981_4_pi_180208_014553-w1024-h683",
      "rank": 4
    },
    {
      "imageType": "Property",
      "advertId": 11948981,
      "date": "2018-01-29T00:00:00Z",
      "fullUrl": "https://bucket-api.domain.com.au/v1/bucket/image/11948981_5_pi_180208_014552-w1024-h683",
      "rank": 5
    },
    {
      "imageType": "Property",
      "advertId": 11948981,
      "date": "2018-01-29T00:00:00Z",
      "fullUrl": "https://bucket-api.domain.com.au/v1/bucket/image/11948981_6_pi_180208_014552-w1024-h683",
      "rank": 6
    },
    {
      "imageType": "Property",
      "advertId": 11948981,
      "date": "2018-01-29T00:00:00Z",
      "fullUrl": "https://bucket-api.domain.com.au/v1/bucket/image/11948981_7_pi_180208_014552-w1024-h683",
      "rank": 7
    },
    {
      "imageType": "Property",
      "advertId": 11948981,
      "date": "2018-01-29T00:00:00Z",
      "fullUrl": "https://bucket-api.domain.com.au/v1/bucket/image/11948981_8_pi_180208_014554-w1000-h667",
      "rank": 8
    },
    {
      "imageType": "Property",
      "advertId": 11948981,
      "date": "2018-01-29T00:00:00Z",
      "fullUrl": "https://bucket-api.domain.com.au/v1/bucket/image/11948981_9_pi_180208_014554-w4134-h2756",
      "rank": 9
    },
    {
      "imageType": "Property",
      "advertId": 10238795,
      "date": "2015-12-14T00:00:00Z",
      "fullUrl": "https://bucket-api.domain.com.au/v1/bucket/image/w800-h600-10238795_1_pi_151214_052933",
      "rank": 1
    },
    {
      "imageType": "Property",
      "advertId": 10238795,
      "date": "2015-12-14T00:00:00Z",
      "fullUrl": "https://bucket-api.domain.com.au/v1/bucket/image/w800-h600-10238795_2_pi_151214_052933",
      "rank": 2
    },
    {
      "imageType": "Property",
      "advertId": 10238795,
      "date": "2015-12-14T00:00:00Z",
      "fullUrl": "https://bucket-api.domain.com.au/v1/bucket/image/w800-h600-10238795_3_pi_151214_052933",
      "rank": 3
    },
    {
      "imageType": "Property",
      "advertId": 10238795,
      "date": "2015-12-14T00:00:00Z",
      "fullUrl": "https://bucket-api.domain.com.au/v1/bucket/image/w800-h600-10238795_4_pi_151214_052933",
      "rank": 4
    }
  ],
  "planNumber": "SP82004",
  "postcode": "2150",
  "propertyCategory": "Residential",
  "propertyType": "Apartment",
  "state": "NSW",
  "streetAddress": "8 Cowper Street",
  "streetName": "Cowper",
  "streetNumber": "8",
  "streetType": "St",
  "streetTypeLong": "Street",
  "suburb": "Parramatta",
  "suburbId": 35042,
  "updated": "2024-11-08T08:22:13.973Z",
  "urlSlug": "102b-8-cowper-street-parramatta-nsw-2150",
  "urlSlugShort": "102b-8-cowper-st-parramatta-nsw-2150",
  "gnafIds": [
    {
      "gnafPID": "GANSW717872184"
    }
  ],
  "areaSize": 4415
}';
$extracted_property = rc_domain_extract_property($fetched_property);
$latitude = $extracted_property['address_data']['address_coordinates']['lat'];
$longitude = $extracted_property['address_data']['address_coordinates']['lon'];
$state = $extracted_property['address_data']['state'];
$suburb = $extracted_property['address_data']['suburb'];
$postcode = $extracted_property['address_data']['postcode'];

// $fetched_property_price_estimate = json_encode(rc_domain_fetch_property_price_estimate($property_id));
$fetched_property_price_estimate = '
{
  "date": "2024-10-29T10:37:19.760+11:00",
  "lowerPrice": 750000,
  "midPrice": 870000,
  "priceConfidence": "high",
  "source": "APM",
  "upperPrice": 990000,
  "history": [
    {
      "confidence": "high",
      "date": "2024-10-29T10:37:19.760+11:00",
      "lowerPrice": 750000,
      "midPrice": 870000,
      "source": "APM",
      "upperPrice": 990000
    }
  ]
}';
$extracted_property_price_estimate = rc_domain_extract_property_price_estimate($fetched_property_price_estimate);

// $fetched_schools = json_encode(rc_domain_fetch_schools($latitude, $longitude));
$fetched_schools = '
[
    {
      "distance": 405.8402319637859,
      "school": {
        "schoolSector": "Catholic",
        "schoolType": "Primary",
        "gender": "CoEd",
        "name": "St Oliver&apos;s Primary School",
        "suburb": "Harris Park",
        "state": "NSW",
        "postcode": "2150",
        "centroid": "POINT (151.008767 -33.82504)",
        "profile": {
          "url": "http://www.stoliversharrispark.catholic.edu.au",
          "yearRange": "K-6",
          "icsea": 1083,
          "bottomSeaQuarter": 10,
          "lowerMiddleSeaQuarter": 19,
          "upperMiddleSeaQuarter": 33,
          "topSeaQuarter": 39,
          "totalEnrolments": 170,
          "girlsEnrolments": 73,
          "boysEnrolments": 97
        },
        "domainId": 7847
      }
    },
    {
      "distance": 588.140269474445,
      "school": {
        "schoolSector": "Government",
        "schoolType": "Secondary",
        "gender": "CoEd",
        "name": "Arthur Phillip High School",
        "suburb": "Parramatta",
        "state": "NSW",
        "postcode": "2150",
        "centroid": "POINT (151.007364 -33.816386)",
        "profile": {
          "url": "https://arthurphil-h.schools.nsw.gov.au/",
          "yearRange": "7-12",
          "icsea": 982,
          "bottomSeaQuarter": 44,
          "lowerMiddleSeaQuarter": 23,
          "upperMiddleSeaQuarter": 21,
          "topSeaQuarter": 12,
          "totalEnrolments": 1462,
          "girlsEnrolments": 553,
          "boysEnrolments": 909
        },
        "domainId": 31
      }
    },
    {
      "distance": 592.4605320156569,
      "school": {
        "schoolSector": "Government",
        "schoolType": "Primary",
        "gender": "CoEd",
        "name": "Parramatta Public School",
        "suburb": "Parramatta",
        "state": "NSW",
        "postcode": "2150",
        "centroid": "POINT (151.007738 -33.81637)",
        "profile": {
          "url": "https://parramatta-p.schools.nsw.gov.au",
          "yearRange": "K-6",
          "icsea": 1137,
          "bottomSeaQuarter": 3,
          "lowerMiddleSeaQuarter": 9,
          "upperMiddleSeaQuarter": 30,
          "topSeaQuarter": 58,
          "totalEnrolments": 740,
          "girlsEnrolments": 387,
          "boysEnrolments": 353
        },
        "domainId": 796
      }
    },
    {
      "distance": 731.6753160409284,
      "school": {
        "schoolSector": "Independent",
        "schoolType": "Combined",
        "gender": "CoEd",
        "name": "Maronite College of the Holy Family - Parramatta",
        "suburb": "Harris Park",
        "state": "NSW",
        "postcode": "2150",
        "centroid": "POINT (151.015 -33.8216)",
        "profile": {
          "url": "http://www.mchf.nsw.edu.au",
          "yearRange": "K-12",
          "icsea": 1019,
          "bottomSeaQuarter": 29,
          "lowerMiddleSeaQuarter": 34,
          "upperMiddleSeaQuarter": 25,
          "topSeaQuarter": 12,
          "totalEnrolments": 1258,
          "girlsEnrolments": 637,
          "boysEnrolments": 621
        }
      }
    },
    {
      "distance": 892.8050194951117,
      "school": {
        "schoolSector": "Government",
        "schoolType": "Secondary",
        "name": "Secondary College of Languages",
        "suburb": "Parramatta",
        "state": "NSW",
        "postcode": "2150",
        "centroid": "POINT (151.0081765 -33.81369267)",
        "profile": {
          "url": "https://sclanguages.schools.nsw.gov.au",
          "yearRange": "Unknown"
        }
      }
    },
    {
      "distance": 1077.8169252169423,
      "school": {
        "schoolSector": "Government",
        "schoolType": "Secondary",
        "gender": "Girls",
        "name": "Macarthur Girls High School",
        "suburb": "Parramatta",
        "state": "NSW",
        "postcode": "2150",
        "centroid": "POINT (151.012499 -33.813086)",
        "profile": {
          "url": "https://macarthurg-h.schools.nsw.gov.au/",
          "yearRange": "7-12",
          "icsea": 1058,
          "bottomSeaQuarter": 19,
          "lowerMiddleSeaQuarter": 22,
          "upperMiddleSeaQuarter": 31,
          "topSeaQuarter": 28,
          "totalEnrolments": 1055,
          "girlsEnrolments": 1055,
          "boysEnrolments": 0
        },
        "domainId": 732
      }
    },
    {
      "distance": 1081.5872082214275,
      "school": {
        "schoolSector": "Government",
        "schoolType": "Primary",
        "gender": "CoEd",
        "name": "Rosehill Public School",
        "suburb": "Rosehill",
        "state": "NSW",
        "postcode": "2142",
        "centroid": "POINT (151.018376 -33.824229)",
        "profile": {
          "url": "https://rosehill-p.schools.nsw.gov.au/",
          "yearRange": "P-6",
          "icsea": 1068,
          "bottomSeaQuarter": 14,
          "lowerMiddleSeaQuarter": 24,
          "upperMiddleSeaQuarter": 33,
          "topSeaQuarter": 28,
          "totalEnrolments": 654,
          "girlsEnrolments": 317,
          "boysEnrolments": 337
        },
        "domainId": 157
      }
    },
    {
      "distance": 1099.7185510009986,
      "school": {
        "schoolSector": "Government",
        "schoolType": "Secondary",
        "gender": "CoEd",
        "name": "Parramatta High School",
        "suburb": "Parramatta",
        "state": "NSW",
        "postcode": "2150",
        "centroid": "POINT (150.996083 -33.817883)",
        "profile": {
          "url": "https://parramatta-h.schools.nsw.gov.au/",
          "yearRange": "7-12",
          "icsea": 1121,
          "bottomSeaQuarter": 6,
          "lowerMiddleSeaQuarter": 12,
          "upperMiddleSeaQuarter": 31,
          "topSeaQuarter": 52,
          "totalEnrolments": 1242,
          "girlsEnrolments": 512,
          "boysEnrolments": 730
        },
        "domainId": 241
      }
    },
    {
      "distance": 1412.5031199366795,
      "school": {
        "schoolSector": "Government",
        "schoolType": "Primary",
        "gender": "CoEd",
        "name": "Bayanami Public School",
        "suburb": "Parramatta",
        "state": "NSW",
        "postcode": "2150",
        "centroid": "POINT (151.002418 -33.809572)",
        "profile": {
          "url": "https://bayanami-p.schools.nsw.gov.au/",
          "yearRange": "K-6",
          "icsea": 1116,
          "bottomSeaQuarter": 5,
          "lowerMiddleSeaQuarter": 10,
          "upperMiddleSeaQuarter": 34,
          "topSeaQuarter": 51,
          "totalEnrolments": 795,
          "girlsEnrolments": 422,
          "boysEnrolments": 373
        }
      }
    },
    {
      "distance": 1417.9600212045168,
      "school": {
        "schoolSector": "Government",
        "schoolType": "Primary",
        "gender": "CoEd",
        "name": "Granville Public School",
        "suburb": "Granville",
        "state": "NSW",
        "postcode": "2142",
        "centroid": "POINT (151.004671 -33.834264)",
        "profile": {
          "url": "https://granville-p.schools.nsw.gov.au",
          "yearRange": "P-6",
          "icsea": 978,
          "bottomSeaQuarter": 46,
          "lowerMiddleSeaQuarter": 25,
          "upperMiddleSeaQuarter": 19,
          "topSeaQuarter": 9,
          "totalEnrolments": 595,
          "girlsEnrolments": 266,
          "boysEnrolments": 329
        },
        "domainId": 314
      }
    },
    {
      "distance": 1448.6666227110425,
      "school": {
        "schoolSector": "Government",
        "schoolType": "Secondary",
        "gender": "Boys",
        "name": "Granville Boys High School",
        "suburb": "Granville",
        "state": "NSW",
        "postcode": "2142",
        "centroid": "POINT (151.008817 -33.834618)",
        "profile": {
          "url": "https://granvilleb-h.schools.nsw.gov.au/",
          "yearRange": "7-12",
          "icsea": 950,
          "bottomSeaQuarter": 58,
          "lowerMiddleSeaQuarter": 20,
          "upperMiddleSeaQuarter": 15,
          "topSeaQuarter": 7,
          "totalEnrolments": 695,
          "girlsEnrolments": 0,
          "boysEnrolments": 695
        },
        "domainId": 656
      }
    },
    {
      "distance": 1492.6411407509684,
      "school": {
        "schoolSector": "Independent",
        "schoolType": "Secondary",
        "gender": "Girls",
        "name": "Muslim Girls Grammar School",
        "suburb": "Granville",
        "state": "NSW",
        "postcode": "2142",
        "centroid": "POINT (151.0101571 -33.834848)",
        "profile": {
          "url": "https://www.mggs.nsw.edu.au/",
          "yearRange": "7-10",
          "icsea": 997,
          "bottomSeaQuarter": 33,
          "lowerMiddleSeaQuarter": 32,
          "upperMiddleSeaQuarter": 23,
          "topSeaQuarter": 12,
          "totalEnrolments": 161,
          "girlsEnrolments": 161,
          "boysEnrolments": 0
        }
      }
    },
    {
      "distance": 1517.6248861719955,
      "school": {
        "schoolSector": "Catholic",
        "schoolType": "Secondary",
        "gender": "CoEd",
        "name": "Delany College",
        "suburb": "Granville",
        "state": "NSW",
        "postcode": "2142",
        "centroid": "POINT (151.001174 -33.834406)",
        "profile": {
          "url": "http://www.delanygranville.catholic.edu.au",
          "yearRange": "7-12",
          "icsea": 982,
          "bottomSeaQuarter": 44,
          "lowerMiddleSeaQuarter": 30,
          "upperMiddleSeaQuarter": 19,
          "topSeaQuarter": 7,
          "totalEnrolments": 357,
          "girlsEnrolments": 151,
          "boysEnrolments": 206
        },
        "domainId": 7797
      }
    },
    {
      "distance": 1520.6312137592322,
      "school": {
        "schoolSector": "Government",
        "schoolType": "Primary",
        "gender": "CoEd",
        "name": "Parramatta West Public School",
        "suburb": "Parramatta",
        "state": "NSW",
        "postcode": "2150",
        "centroid": "POINT (150.990968 -33.824472)",
        "profile": {
          "url": "https://parramattw-p.schools.nsw.gov.au/",
          "yearRange": "K-6",
          "icsea": 1032,
          "bottomSeaQuarter": 26,
          "lowerMiddleSeaQuarter": 22,
          "upperMiddleSeaQuarter": 31,
          "topSeaQuarter": 21,
          "totalEnrolments": 916,
          "girlsEnrolments": 438,
          "boysEnrolments": 478
        },
        "domainId": 797
      }
    },
    {
      "distance": 1533.7175691044868,
      "school": {
        "schoolSector": "Independent",
        "schoolType": "Secondary",
        "gender": "Girls",
        "name": "Our Lady of Mercy College Parramatta",
        "suburb": "Parramatta",
        "state": "NSW",
        "postcode": "2150",
        "centroid": "POINT (151.003 -33.8083)",
        "profile": {
          "url": "http://www.olmc.nsw.edu.au/",
          "yearRange": "7-12",
          "icsea": 1111,
          "bottomSeaQuarter": 5,
          "lowerMiddleSeaQuarter": 19,
          "upperMiddleSeaQuarter": 33,
          "topSeaQuarter": 42,
          "totalEnrolments": 1068,
          "girlsEnrolments": 1068,
          "boysEnrolments": 0
        },
        "domainId": 10069
      }
    },
    {
      "distance": 1568.2391876474255,
      "school": {
        "schoolSector": "Catholic",
        "schoolType": "Primary",
        "gender": "CoEd",
        "name": "Holy Trinity Primary School",
        "suburb": "Granville",
        "state": "NSW",
        "postcode": "2142",
        "centroid": "POINT (151.001612 -33.835022)",
        "profile": {
          "url": "http://www.htgranville.catholic.edu.au",
          "yearRange": "K-6",
          "icsea": 1027,
          "bottomSeaQuarter": 23,
          "lowerMiddleSeaQuarter": 27,
          "upperMiddleSeaQuarter": 28,
          "topSeaQuarter": 21,
          "totalEnrolments": 186,
          "girlsEnrolments": 100,
          "boysEnrolments": 86
        }
      }
    },
    {
      "distance": 1623.9208943022277,
      "school": {
        "schoolSector": "Catholic",
        "schoolType": "Primary",
        "gender": "CoEd",
        "name": "St Patrick&apos;s Primary School",
        "suburb": "Parramatta",
        "state": "NSW",
        "postcode": "2150",
        "centroid": "POINT (151.003808 -33.807321)",
        "profile": {
          "url": "http://www.stpatsparra.catholic.edu.au",
          "yearRange": "K-6",
          "icsea": 1103,
          "bottomSeaQuarter": 4,
          "lowerMiddleSeaQuarter": 17,
          "upperMiddleSeaQuarter": 39,
          "topSeaQuarter": 39,
          "totalEnrolments": 413,
          "girlsEnrolments": 203,
          "boysEnrolments": 210
        }
      }
    },
    {
      "distance": 1971.4645997948237,
      "school": {
        "schoolSector": "Government",
        "schoolType": "Primary",
        "gender": "CoEd",
        "name": "Parramatta North Public School",
        "suburb": "North Parramatta",
        "state": "NSW",
        "postcode": "2151",
        "centroid": "POINT (151.004573 -33.804063)",
        "profile": {
          "url": "https://parramattn-p.schools.nsw.gov.au/",
          "yearRange": "K-6",
          "icsea": 1066,
          "bottomSeaQuarter": 15,
          "lowerMiddleSeaQuarter": 21,
          "upperMiddleSeaQuarter": 30,
          "topSeaQuarter": 34,
          "totalEnrolments": 254,
          "girlsEnrolments": 124,
          "boysEnrolments": 130
        },
        "domainId": 193
      }
    },
    {
      "distance": 1994.8408996348496,
      "school": {
        "schoolSector": "Government",
        "schoolType": "Primary",
        "gender": "CoEd",
        "name": "Parramatta East Public School",
        "suburb": "Parramatta",
        "state": "NSW",
        "postcode": "2150",
        "centroid": "POINT (151.017766 -33.806081)",
        "profile": {
          "url": "https://parramatte-p.schools.nsw.gov.au/",
          "yearRange": "K-6",
          "icsea": 1094,
          "bottomSeaQuarter": 11,
          "lowerMiddleSeaQuarter": 15,
          "upperMiddleSeaQuarter": 32,
          "topSeaQuarter": 42,
          "totalEnrolments": 500,
          "girlsEnrolments": 255,
          "boysEnrolments": 245
        },
        "domainId": 185
      }
    },
    {
      "distance": 2081.8154720087487,
      "school": {
        "schoolSector": "Catholic",
        "schoolType": "Primary",
        "gender": "CoEd",
        "name": "Sacred Heart Primary School",
        "suburb": "Westmead",
        "state": "NSW",
        "postcode": "2145",
        "centroid": "POINT (150.986749 -33.813595)",
        "profile": {
          "url": "http://www.sacredheartwestmead.catholic.edu.au/",
          "yearRange": "K-6",
          "icsea": 1081,
          "bottomSeaQuarter": 9,
          "lowerMiddleSeaQuarter": 23,
          "upperMiddleSeaQuarter": 34,
          "topSeaQuarter": 35,
          "totalEnrolments": 188,
          "girlsEnrolments": 80,
          "boysEnrolments": 108
        },
        "domainId": 10060
      }
    },
    {
      "distance": 2384.7232550372482,
      "school": {
        "schoolSector": "Government",
        "schoolType": "Primary",
        "gender": "CoEd",
        "name": "Westmead Public School",
        "suburb": "Westmead",
        "state": "NSW",
        "postcode": "2145",
        "centroid": "POINT (150.985291 -33.810171)",
        "profile": {
          "url": "http://www.westmead-p.schools.nsw.edu.au",
          "yearRange": "K-6",
          "icsea": 1125,
          "bottomSeaQuarter": 5,
          "lowerMiddleSeaQuarter": 9,
          "upperMiddleSeaQuarter": 31,
          "topSeaQuarter": 55,
          "totalEnrolments": 865,
          "girlsEnrolments": 415,
          "boysEnrolments": 450
        },
        "domainId": 103
      }
    },
    {
      "distance": 2474.3683481895782,
      "school": {
        "schoolSector": "Independent",
        "schoolType": "Primary",
        "gender": "CoEd",
        "name": "Westmead Christian Grammar School",
        "suburb": "Westmead",
        "state": "NSW",
        "postcode": "2145",
        "centroid": "POINT (150.981 -33.8166)",
        "profile": {
          "url": "http://www.wcgs.com.au",
          "yearRange": "K-6",
          "icsea": 1125,
          "bottomSeaQuarter": 5,
          "lowerMiddleSeaQuarter": 11,
          "upperMiddleSeaQuarter": 28,
          "topSeaQuarter": 56,
          "totalEnrolments": 171,
          "girlsEnrolments": 84,
          "boysEnrolments": 87
        },
        "domainId": 7821
      }
    },
    {
      "distance": 2481.826305198296,
      "school": {
        "schoolSector": "Government",
        "schoolType": "Combined",
        "gender": "CoEd",
        "name": "Holroyd School",
        "suburb": "Merrylands",
        "state": "NSW",
        "postcode": "2160",
        "centroid": "POINT (150.981664 -33.828906)",
        "profile": {
          "url": "https://holroyd-s.schools.nsw.gov.au",
          "yearRange": "U",
          "icsea": 987,
          "bottomSeaQuarter": 44,
          "lowerMiddleSeaQuarter": 24,
          "upperMiddleSeaQuarter": 20,
          "topSeaQuarter": 11,
          "totalEnrolments": 186,
          "girlsEnrolments": 54,
          "boysEnrolments": 132
        },
        "domainId": 7888
      }
    },
    {
      "distance": 2578.84341112242,
      "school": {
        "schoolSector": "Government",
        "schoolType": "Combined",
        "name": "The Childrens Hospital School",
        "suburb": "Westmead",
        "state": "NSW",
        "postcode": "2145",
        "centroid": "POINT (150.991834 -33.802243)",
        "profile": {
          "url": "https://childhosp-s.schools.nsw.gov.au/",
          "yearRange": "Unknown"
        },
        "domainId": 7820
      }
    },
    {
      "distance": 2812.3132727540774,
      "school": {
        "schoolSector": "Catholic",
        "schoolType": "Secondary",
        "gender": "Girls",
        "name": "Catherine McAuley Westmead",
        "suburb": "Westmead",
        "state": "NSW",
        "postcode": "2145",
        "centroid": "POINT (150.984486 -33.804721)",
        "profile": {
          "url": "http://www.mcauleywestmead.catholic.edu.au",
          "yearRange": "7-12",
          "icsea": 1080,
          "bottomSeaQuarter": 9,
          "lowerMiddleSeaQuarter": 24,
          "upperMiddleSeaQuarter": 35,
          "topSeaQuarter": 32,
          "totalEnrolments": 1191,
          "girlsEnrolments": 1191,
          "boysEnrolments": 0
        },
        "domainId": 7813
      }
    },
    {
      "distance": 2817.141029325152,
      "school": {
        "schoolSector": "Government",
        "schoolType": "Primary",
        "gender": "CoEd",
        "name": "Oatlands Public School",
        "suburb": "Oatlands",
        "state": "NSW",
        "postcode": "2117",
        "centroid": "POINT (151.02278126 -33.79995194)",
        "profile": {
          "url": "https://oatlands-p.schools.nsw.gov.au/",
          "yearRange": "K-6",
          "icsea": 1082,
          "bottomSeaQuarter": 14,
          "lowerMiddleSeaQuarter": 16,
          "upperMiddleSeaQuarter": 28,
          "topSeaQuarter": 42,
          "totalEnrolments": 175,
          "girlsEnrolments": 83,
          "boysEnrolments": 92
        },
        "domainId": 189
      }
    },
    {
      "distance": 2850.1118361172903,
      "school": {
        "schoolSector": "Catholic",
        "schoolType": "Primary",
        "gender": "CoEd",
        "name": "St Margaret Mary&apos;s Primary School",
        "suburb": "Merrylands",
        "state": "NSW",
        "postcode": "2160",
        "centroid": "POINT (150.981727 -33.836279)",
        "profile": {
          "url": "http://www.stmmmerrylands.catholic.edu.au",
          "yearRange": "K-6",
          "icsea": 1042,
          "bottomSeaQuarter": 20,
          "lowerMiddleSeaQuarter": 31,
          "upperMiddleSeaQuarter": 31,
          "topSeaQuarter": 18,
          "totalEnrolments": 555,
          "girlsEnrolments": 294,
          "boysEnrolments": 261
        },
        "domainId": 7892
      }
    },
    {
      "distance": 2874.2350170387867,
      "school": {
        "schoolSector": "Government",
        "schoolType": "Primary",
        "gender": "CoEd",
        "name": "Merrylands East Public School",
        "suburb": "Merrylands",
        "state": "NSW",
        "postcode": "2160",
        "centroid": "POINT (150.988301 -33.842281)",
        "profile": {
          "url": "https://merrylandseast.schools.nsw.gov.au",
          "yearRange": "K-6",
          "icsea": 965,
          "bottomSeaQuarter": 47,
          "lowerMiddleSeaQuarter": 26,
          "upperMiddleSeaQuarter": 19,
          "topSeaQuarter": 7,
          "totalEnrolments": 327,
          "girlsEnrolments": 164,
          "boysEnrolments": 163
        },
        "domainId": 750
      }
    },
    {
      "distance": 2932.612250005982,
      "school": {
        "schoolSector": "Government",
        "schoolType": "Combined",
        "gender": "CoEd",
        "name": "Redbank School",
        "suburb": "Westmead",
        "state": "NSW",
        "postcode": "2145",
        "centroid": "POINT (150.987659 -33.800809)",
        "profile": {
          "url": "https://redbank-s.schools.nsw.gov.au/",
          "yearRange": "U",
          "icsea": 992,
          "bottomSeaQuarter": 37,
          "lowerMiddleSeaQuarter": 21,
          "upperMiddleSeaQuarter": 29,
          "topSeaQuarter": 13,
          "totalEnrolments": 44,
          "girlsEnrolments": 35,
          "boysEnrolments": 9
        },
        "domainId": 7817
      }
    },
    {
      "distance": 2969.9010181465237,
      "school": {
        "schoolSector": "Government",
        "schoolType": "Primary",
        "gender": "CoEd",
        "name": "Granville East Public School",
        "suburb": "Granville",
        "state": "NSW",
        "postcode": "2142",
        "centroid": "POINT (151.009981 -33.84827)",
        "profile": {
          "url": "http://www.granvillee-p.schools.nsw.gov.au",
          "yearRange": "K-6",
          "icsea": 919,
          "bottomSeaQuarter": 65,
          "lowerMiddleSeaQuarter": 22,
          "upperMiddleSeaQuarter": 11,
          "topSeaQuarter": 2,
          "totalEnrolments": 264,
          "girlsEnrolments": 133,
          "boysEnrolments": 131
        },
        "domainId": 657
      }
    },
    {
      "distance": 2997.7562640063047,
      "school": {
        "schoolSector": "Catholic",
        "schoolType": "Primary",
        "gender": "CoEd",
        "name": "Mother Teresa Primary School",
        "suburb": "Westmead",
        "state": "NSW",
        "postcode": "2145",
        "centroid": "POINT (150.983 -33.8036)",
        "profile": {
          "url": "http://www.motherteresawestmead.catholic.edu.au",
          "yearRange": "K-6",
          "icsea": 1108,
          "bottomSeaQuarter": 4,
          "lowerMiddleSeaQuarter": 20,
          "upperMiddleSeaQuarter": 34,
          "topSeaQuarter": 41,
          "totalEnrolments": 396,
          "girlsEnrolments": 182,
          "boysEnrolments": 214
        },
        "domainId": 7814
      }
    },
    {
      "distance": 2997.916313444999,
      "school": {
        "schoolSector": "Catholic",
        "schoolType": "Primary",
        "gender": "CoEd",
        "name": "Holy Family Primary School",
        "suburb": "Granville East",
        "state": "NSW",
        "postcode": "2142",
        "centroid": "POINT (151.01161 -33.848367)",
        "profile": {
          "url": "http://www.hfgranville.catholic.edu.au",
          "yearRange": "K-6",
          "icsea": 1004,
          "bottomSeaQuarter": 35,
          "lowerMiddleSeaQuarter": 35,
          "upperMiddleSeaQuarter": 20,
          "topSeaQuarter": 11,
          "totalEnrolments": 244,
          "girlsEnrolments": 103,
          "boysEnrolments": 141
        },
        "domainId": 10054
      }
    },
    {
      "distance": 3031.368662983028,
      "school": {
        "schoolSector": "Government",
        "schoolType": "Combined",
        "name": "Palm Avenue School",
        "suburb": "Westmead",
        "state": "NSW",
        "postcode": "2145",
        "centroid": "POINT (150.986561 -33.800397)",
        "profile": {
          "url": "https://palmave-s.schools.nsw.gov.au/",
          "yearRange": "U"
        },
        "domainId": 7815
      }
    },
    {
      "distance": 3161.4600585530993,
      "school": {
        "schoolSector": "Government",
        "schoolType": "Primary",
        "gender": "CoEd",
        "name": "Hilltop Road Public School",
        "suburb": "Merrylands",
        "state": "NSW",
        "postcode": "2160",
        "centroid": "POINT (150.973319 -33.826331)",
        "profile": {
          "url": "https://hilltoprd-p.schools.nsw.gov.au",
          "yearRange": "K-6",
          "icsea": 973,
          "bottomSeaQuarter": 46,
          "lowerMiddleSeaQuarter": 29,
          "upperMiddleSeaQuarter": 17,
          "topSeaQuarter": 8,
          "totalEnrolments": 761,
          "girlsEnrolments": 371,
          "boysEnrolments": 390
        },
        "domainId": 299
      }
    },
    {
      "distance": 3164.294148444155,
      "school": {
        "schoolSector": "Government",
        "schoolType": "Primary",
        "gender": "CoEd",
        "name": "Granville South Public School",
        "suburb": "Guildford",
        "state": "NSW",
        "postcode": "2161",
        "centroid": "POINT (150.99673745 -33.8487994)",
        "profile": {
          "url": "https://granvilles-p.schools.nsw.gov.au",
          "yearRange": "K-6",
          "icsea": 951,
          "bottomSeaQuarter": 55,
          "lowerMiddleSeaQuarter": 25,
          "upperMiddleSeaQuarter": 16,
          "topSeaQuarter": 4,
          "totalEnrolments": 317,
          "girlsEnrolments": 142,
          "boysEnrolments": 175
        },
        "domainId": 658
      }
    },
    {
      "distance": 3164.946005553318,
      "school": {
        "schoolSector": "Catholic",
        "schoolType": "Primary",
        "gender": "CoEd",
        "name": "St Monica&apos;s Primary School",
        "suburb": "North Parramatta",
        "state": "NSW",
        "postcode": "2151",
        "centroid": "POINT (151.001217 -33.793627)",
        "profile": {
          "url": "http://www.stmonicasparra.catholic.edu.au",
          "yearRange": "K-6",
          "icsea": 1116,
          "bottomSeaQuarter": 4,
          "lowerMiddleSeaQuarter": 16,
          "upperMiddleSeaQuarter": 31,
          "topSeaQuarter": 49,
          "totalEnrolments": 197,
          "girlsEnrolments": 91,
          "boysEnrolments": 106
        },
        "domainId": 1751
      }
    },
    {
      "distance": 3189.485471971643,
      "school": {
        "schoolSector": "Catholic",
        "schoolType": "Secondary",
        "gender": "Boys",
        "name": "Parramatta Marist High School",
        "suburb": "Westmead",
        "state": "NSW",
        "postcode": "2145",
        "centroid": "POINT (150.979606 -33.804301)",
        "profile": {
          "url": "http://www.parramarist.catholic.edu.au/",
          "yearRange": "7-12",
          "icsea": 1084,
          "bottomSeaQuarter": 9,
          "lowerMiddleSeaQuarter": 24,
          "upperMiddleSeaQuarter": 35,
          "topSeaQuarter": 32,
          "totalEnrolments": 1068,
          "girlsEnrolments": 0,
          "boysEnrolments": 1068
        },
        "domainId": 7816
      }
    },
    {
      "distance": 3257.9809676559926,
      "school": {
        "schoolSector": "Independent",
        "schoolType": "Combined",
        "gender": "CoEd",
        "name": "Redeemer Baptist School",
        "suburb": "North Parramatta",
        "state": "NSW",
        "postcode": "2151",
        "centroid": "POINT (151.017344 -33.793639)",
        "profile": {
          "url": "http://www.redeemer.nsw.edu.au",
          "yearRange": "K-12",
          "icsea": 1139,
          "bottomSeaQuarter": 4,
          "lowerMiddleSeaQuarter": 11,
          "upperMiddleSeaQuarter": 28,
          "topSeaQuarter": 57,
          "totalEnrolments": 506,
          "girlsEnrolments": 211,
          "boysEnrolments": 295
        },
        "domainId": 7852
      }
    },
    {
      "distance": 3296.2675714072857,
      "school": {
        "schoolSector": "Catholic",
        "schoolType": "Primary",
        "gender": "CoEd",
        "name": "St Mary&apos;s Primary School",
        "suburb": "Rydalmere",
        "state": "NSW",
        "postcode": "2116",
        "centroid": "POINT (151.040766 -33.811898)",
        "profile": {
          "url": "http://www.stmarysrydalmere.catholic.edu.au",
          "yearRange": "K-6",
          "icsea": 1081,
          "bottomSeaQuarter": 10,
          "lowerMiddleSeaQuarter": 20,
          "upperMiddleSeaQuarter": 34,
          "topSeaQuarter": 37,
          "totalEnrolments": 381,
          "girlsEnrolments": 193,
          "boysEnrolments": 188
        },
        "domainId": 10032
      }
    },
    {
      "distance": 3318.8148712676466,
      "school": {
        "schoolSector": "Government",
        "schoolType": "Primary",
        "gender": "CoEd",
        "name": "Dundas Public School",
        "suburb": "Dundas",
        "state": "NSW",
        "postcode": "2117",
        "centroid": "POINT (151.036399 -33.804424)",
        "profile": {
          "url": "https://dundas-p.schools.nsw.gov.au",
          "yearRange": "K-6",
          "icsea": 1067,
          "bottomSeaQuarter": 14,
          "lowerMiddleSeaQuarter": 23,
          "upperMiddleSeaQuarter": 33,
          "topSeaQuarter": 30,
          "totalEnrolments": 334,
          "girlsEnrolments": 148,
          "boysEnrolments": 186
        },
        "domainId": 367
      }
    },
    {
      "distance": 3397.172679659193,
      "school": {
        "schoolSector": "Government",
        "schoolType": "Primary",
        "gender": "CoEd",
        "name": "Burnside Public School",
        "suburb": "North Parramatta",
        "state": "NSW",
        "postcode": "2151",
        "centroid": "POINT (151.018316 -33.79258)",
        "profile": {
          "url": "https://burnside-p.schools.nsw.gov.au/",
          "yearRange": "K-6",
          "icsea": 1110,
          "bottomSeaQuarter": 9,
          "lowerMiddleSeaQuarter": 20,
          "upperMiddleSeaQuarter": 27,
          "topSeaQuarter": 44,
          "totalEnrolments": 206,
          "girlsEnrolments": 98,
          "boysEnrolments": 108
        },
        "domainId": 431
      }
    },
    {
      "distance": 3405.7789486592374,
      "school": {
        "schoolSector": "Government",
        "schoolType": "Secondary",
        "gender": "Girls",
        "name": "Auburn Girls High School",
        "suburb": "Auburn",
        "state": "NSW",
        "postcode": "2144",
        "centroid": "POINT (151.033681 -33.84288)",
        "profile": {
          "url": "http://www.auburng-h.schools.nsw.edu.au",
          "yearRange": "7-12",
          "icsea": 959,
          "bottomSeaQuarter": 54,
          "lowerMiddleSeaQuarter": 21,
          "upperMiddleSeaQuarter": 17,
          "topSeaQuarter": 8,
          "totalEnrolments": 837,
          "girlsEnrolments": 837,
          "boysEnrolments": 0
        },
        "domainId": 23
      }
    },
    {
      "distance": 3550.7747644915007,
      "school": {
        "schoolSector": "Government",
        "schoolType": "Primary",
        "gender": "CoEd",
        "name": "Merrylands Public School",
        "suburb": "Merrylands",
        "state": "NSW",
        "postcode": "2160",
        "centroid": "POINT (150.976047 -33.840516)",
        "profile": {
          "url": "https://merryland-p.schools.nsw.gov.au/",
          "yearRange": "K-6",
          "icsea": 943,
          "bottomSeaQuarter": 59,
          "lowerMiddleSeaQuarter": 25,
          "upperMiddleSeaQuarter": 13,
          "topSeaQuarter": 4,
          "totalEnrolments": 594,
          "girlsEnrolments": 284,
          "boysEnrolments": 310
        },
        "domainId": 206
      }
    },
    {
      "distance": 3570.739063324028,
      "school": {
        "schoolSector": "Government",
        "schoolType": "Primary",
        "gender": "CoEd",
        "name": "Rydalmere Public School",
        "suburb": "Rydalmere",
        "state": "NSW",
        "postcode": "2116",
        "centroid": "POINT (151.044705 -33.814317)",
        "profile": {
          "url": "https://rydalmere-p.schools.nsw.gov.au/",
          "yearRange": "P-6",
          "icsea": 1026,
          "bottomSeaQuarter": 21,
          "lowerMiddleSeaQuarter": 33,
          "upperMiddleSeaQuarter": 31,
          "topSeaQuarter": 15,
          "totalEnrolments": 154,
          "girlsEnrolments": 72,
          "boysEnrolments": 82
        },
        "domainId": 543
      }
    },
    {
      "distance": 3619.2157956923397,
      "school": {
        "schoolSector": "Catholic",
        "schoolType": "Primary",
        "gender": "CoEd",
        "name": "Our Lady of Mount Carmel Primary School",
        "suburb": "Wentworthville",
        "state": "NSW",
        "postcode": "2145",
        "centroid": "POINT (150.969591 -33.812221)",
        "profile": {
          "url": "http://www.olmcwentworthville.catholic.edu.au",
          "yearRange": "K-6",
          "icsea": 1088,
          "bottomSeaQuarter": 11,
          "lowerMiddleSeaQuarter": 21,
          "upperMiddleSeaQuarter": 30,
          "topSeaQuarter": 38,
          "totalEnrolments": 382,
          "girlsEnrolments": 211,
          "boysEnrolments": 171
        }
      }
    },
    {
      "distance": 3653.959550492341,
      "school": {
        "schoolSector": "Government",
        "schoolType": "Combined",
        "gender": "CoEd",
        "name": "Fowler Road School",
        "suburb": "Merrylands",
        "state": "NSW",
        "postcode": "2160",
        "centroid": "POINT (150.975533 -33.841497)",
        "profile": {
          "url": "https://fowlerroad-s.schools.nsw.gov.au",
          "yearRange": "U",
          "icsea": 862,
          "bottomSeaQuarter": 68,
          "lowerMiddleSeaQuarter": 22,
          "upperMiddleSeaQuarter": 9,
          "topSeaQuarter": 1,
          "totalEnrolments": 46,
          "girlsEnrolments": 8,
          "boysEnrolments": 38
        },
        "domainId": 7887
      }
    },
    {
      "distance": 3654.469026327638,
      "school": {
        "schoolSector": "Government",
        "schoolType": "Primary",
        "gender": "CoEd",
        "name": "Wentworthville Public School",
        "suburb": "Wentworthville",
        "state": "NSW",
        "postcode": "2145",
        "centroid": "POINT (150.968613 -33.814004)",
        "profile": {
          "url": "http://www.wentwthvil-p.schools.nsw.edu.au",
          "yearRange": "K-6",
          "icsea": 1111,
          "bottomSeaQuarter": 6,
          "lowerMiddleSeaQuarter": 11,
          "upperMiddleSeaQuarter": 34,
          "topSeaQuarter": 49,
          "totalEnrolments": 876,
          "girlsEnrolments": 423,
          "boysEnrolments": 453
        },
        "domainId": 97
      }
    },
    {
      "distance": 3664.8677971932293,
      "school": {
        "schoolSector": "Government",
        "schoolType": "Primary",
        "gender": "CoEd",
        "name": "Auburn North Public School",
        "suburb": "Auburn",
        "state": "NSW",
        "postcode": "2144",
        "centroid": "POINT (151.038618 -33.841669)",
        "profile": {
          "url": "https://auburnnth-p.schools.nsw.gov.au/",
          "yearRange": "K-6",
          "icsea": 1007,
          "bottomSeaQuarter": 35,
          "lowerMiddleSeaQuarter": 25,
          "upperMiddleSeaQuarter": 26,
          "topSeaQuarter": 14,
          "totalEnrolments": 637,
          "girlsEnrolments": 313,
          "boysEnrolments": 324
        },
        "domainId": 38
      }
    },
    {
      "distance": 3747.528649584739,
      "school": {
        "schoolSector": "Independent",
        "schoolType": "Combined",
        "gender": "Girls",
        "name": "Tara Anglican School for Girls",
        "suburb": "North Parramatta",
        "state": "NSW",
        "postcode": "2151",
        "centroid": "POINT (151.01665608 -33.78892051)",
        "profile": {
          "url": "https://www.tara.nsw.edu.au/",
          "yearRange": "K-12",
          "icsea": 1165,
          "bottomSeaQuarter": 1,
          "lowerMiddleSeaQuarter": 6,
          "upperMiddleSeaQuarter": 23,
          "topSeaQuarter": 69,
          "totalEnrolments": 875,
          "girlsEnrolments": 875,
          "boysEnrolments": 0
        },
        "domainId": 7853
      }
    },
    {
      "distance": 3747.950288826543,
      "school": {
        "schoolSector": "Government",
        "schoolType": "Primary",
        "gender": "CoEd",
        "name": "Toongabbie East Public School",
        "suburb": "Wentworthville",
        "state": "NSW",
        "postcode": "2145",
        "centroid": "POINT (150.97916826 -33.79721092)",
        "profile": {
          "url": "https://toongabest-p.schools.nsw.gov.au/",
          "yearRange": "K-6",
          "icsea": 942,
          "bottomSeaQuarter": 48,
          "lowerMiddleSeaQuarter": 23,
          "upperMiddleSeaQuarter": 19,
          "topSeaQuarter": 10,
          "totalEnrolments": 110,
          "girlsEnrolments": 44,
          "boysEnrolments": 66
        },
        "domainId": 149
      }
    },
    {
      "distance": 3788.9965735656792,
      "school": {
        "schoolSector": "Independent",
        "schoolType": "Primary",
        "gender": "CoEd",
        "name": "Sydney Adventist School - Auburn",
        "suburb": "Auburn",
        "state": "NSW",
        "postcode": "2144",
        "centroid": "POINT (151.03319 -33.847952)",
        "profile": {
          "url": "http://www.auburn.adventist.edu.au",
          "yearRange": "K-6",
          "icsea": 1012,
          "bottomSeaQuarter": 38,
          "lowerMiddleSeaQuarter": 27,
          "upperMiddleSeaQuarter": 22,
          "topSeaQuarter": 13,
          "totalEnrolments": 165,
          "girlsEnrolments": 72,
          "boysEnrolments": 93
        },
        "domainId": 7811
      }
    },
    {
      "distance": 3796.1125557950418,
      "school": {
        "schoolSector": "Government",
        "schoolType": "Secondary",
        "gender": "CoEd",
        "name": "Northmead Creative and Performing Arts High School",
        "suburb": "Northmead",
        "state": "NSW",
        "postcode": "2152",
        "centroid": "POINT (150.999661 -33.788092)",
        "profile": {
          "url": "https://northmead-h.schools.nsw.gov.au/",
          "yearRange": "7-12",
          "icsea": 1050,
          "bottomSeaQuarter": 19,
          "lowerMiddleSeaQuarter": 26,
          "upperMiddleSeaQuarter": 31,
          "topSeaQuarter": 24,
          "totalEnrolments": 1148,
          "girlsEnrolments": 606,
          "boysEnrolments": 542
        },
        "domainId": 247
      }
    },
    {
      "distance": 3816.258230890869,
      "school": {
        "schoolSector": "Independent",
        "schoolType": "Secondary",
        "name": "Aspect South East Sydney School, Auburn",
        "suburb": "Auburn",
        "state": "NSW",
        "postcode": "2144",
        "centroid": "POINT (151.0301525 -33.850141)"
      }
    },
    {
      "distance": 3825.768888823915,
      "school": {
        "schoolSector": "Catholic",
        "schoolType": "Secondary",
        "gender": "CoEd",
        "name": "Trinity Catholic College",
        "suburb": "Auburn",
        "state": "NSW",
        "postcode": "2144",
        "centroid": "POINT (151.02985 -33.850411)",
        "profile": {
          "url": "https://trinity.syd.catholic.edu.au",
          "yearRange": "7-12",
          "icsea": 1010,
          "bottomSeaQuarter": 33,
          "lowerMiddleSeaQuarter": 30,
          "upperMiddleSeaQuarter": 25,
          "topSeaQuarter": 12,
          "totalEnrolments": 1294,
          "girlsEnrolments": 624,
          "boysEnrolments": 670
        },
        "domainId": 7812
      }
    },
    {
      "distance": 3832.7123691253964,
      "school": {
        "schoolSector": "Independent",
        "schoolType": "Secondary",
        "name": "Key College, Chapel School campus",
        "suburb": "Merrylands",
        "state": "NSW",
        "postcode": "2160",
        "centroid": "POINT (150.970141 -33.83737)",
        "domainId": 7889
      }
    },
    {
      "distance": 3876.8891912617487,
      "school": {
        "schoolSector": "Independent",
        "schoolType": "Secondary",
        "name": "Alpha Omega Senior College, Queen Street Campus",
        "suburb": "Auburn",
        "state": "NSW",
        "postcode": "2144",
        "centroid": "POINT (151.031785 -33.849857)"
      }
    },
    {
      "distance": 3940.0935964122646,
      "school": {
        "schoolSector": "Independent",
        "schoolType": "Secondary",
        "gender": "CoEd",
        "name": "Alpha Omega Senior College",
        "suburb": "Auburn",
        "state": "NSW",
        "postcode": "2144",
        "centroid": "POINT (151.03133 -33.850822)",
        "profile": {
          "url": "http://www.aosc.nsw.edu.au/",
          "yearRange": "7-12",
          "icsea": 1093,
          "bottomSeaQuarter": 12,
          "lowerMiddleSeaQuarter": 19,
          "upperMiddleSeaQuarter": 28,
          "topSeaQuarter": 40,
          "totalEnrolments": 653,
          "girlsEnrolments": 300,
          "boysEnrolments": 353
        },
        "domainId": 7808
      }
    },
    {
      "distance": 3997.4571844565894,
      "school": {
        "schoolSector": "Catholic",
        "schoolType": "Secondary",
        "gender": "Girls",
        "name": "Cerdon College",
        "suburb": "Merrylands",
        "state": "NSW",
        "postcode": "2160",
        "centroid": "POINT (150.968322 -33.837664)",
        "profile": {
          "url": "http://www.cerdonmerrylands.catholic.edu.au",
          "yearRange": "7-12",
          "icsea": 1038,
          "bottomSeaQuarter": 21,
          "lowerMiddleSeaQuarter": 33,
          "upperMiddleSeaQuarter": 29,
          "topSeaQuarter": 17,
          "totalEnrolments": 1026,
          "girlsEnrolments": 1026,
          "boysEnrolments": 0
        },
        "domainId": 7886
      }
    },
    {
      "distance": 4036.7316503441543,
      "school": {
        "schoolSector": "Government",
        "schoolType": "Primary",
        "gender": "CoEd",
        "name": "Blaxcell Street Public School",
        "suburb": "Granville",
        "state": "NSW",
        "postcode": "2142",
        "centroid": "POINT (151.00605986 -33.85796326)",
        "profile": {
          "url": "https://blaxcellst-p.schools.nsw.gov.au/",
          "yearRange": "K-6",
          "icsea": 960,
          "bottomSeaQuarter": 55,
          "lowerMiddleSeaQuarter": 25,
          "upperMiddleSeaQuarter": 15,
          "topSeaQuarter": 5,
          "totalEnrolments": 939,
          "girlsEnrolments": 485,
          "boysEnrolments": 454
        },
        "domainId": 58
      }
    },
    {
      "distance": 4065.3913385192827,
      "school": {
        "schoolSector": "Catholic",
        "schoolType": "Primary",
        "gender": "CoEd",
        "name": "St John&apos;s Catholic Primary School",
        "suburb": "Auburn",
        "state": "NSW",
        "postcode": "2144",
        "centroid": "POINT (151.032786 -33.851348)",
        "profile": {
          "url": "https://stjauburn.syd.catholic.edu.au",
          "yearRange": "K-6",
          "icsea": 1021,
          "bottomSeaQuarter": 31,
          "lowerMiddleSeaQuarter": 28,
          "upperMiddleSeaQuarter": 26,
          "topSeaQuarter": 14,
          "totalEnrolments": 342,
          "girlsEnrolments": 153,
          "boysEnrolments": 189
        },
        "domainId": 10058
      }
    },
    {
      "distance": 4068.3277681391965,
      "school": {
        "schoolSector": "Government",
        "schoolType": "Secondary",
        "gender": "CoEd",
        "name": "Merrylands High School",
        "suburb": "Merrylands",
        "state": "NSW",
        "postcode": "2160",
        "centroid": "POINT (150.96957 -33.840847)",
        "profile": {
          "url": "https://merryland-h.schools.nsw.gov.au/",
          "yearRange": "7-12",
          "icsea": 925,
          "bottomSeaQuarter": 65,
          "lowerMiddleSeaQuarter": 23,
          "upperMiddleSeaQuarter": 10,
          "topSeaQuarter": 3,
          "totalEnrolments": 793,
          "girlsEnrolments": 367,
          "boysEnrolments": 426
        },
        "domainId": 751
      }
    },
    {
      "distance": 4080.9927337634813,
      "school": {
        "schoolSector": "Independent",
        "schoolType": "Combined",
        "gender": "CoEd",
        "name": "The King&apos;s School",
        "suburb": "North Parramatta",
        "state": "NSW",
        "postcode": "2151",
        "centroid": "POINT (151.02611856 -33.78855336)",
        "profile": {
          "url": "http://www.kings.edu.au",
          "yearRange": "K-12",
          "icsea": 1159,
          "bottomSeaQuarter": 2,
          "lowerMiddleSeaQuarter": 7,
          "upperMiddleSeaQuarter": 25,
          "topSeaQuarter": 66,
          "totalEnrolments": 2137,
          "girlsEnrolments": 88,
          "boysEnrolments": 2049
        },
        "domainId": 7854
      }
    },
    {
      "distance": 4190.467818898717,
      "school": {
        "schoolSector": "Government",
        "schoolType": "Primary",
        "gender": "CoEd",
        "name": "Northmead Public School",
        "suburb": "Northmead",
        "state": "NSW",
        "postcode": "2152",
        "centroid": "POINT (150.990753 -33.786511)",
        "profile": {
          "url": "https://northmead-p.schools.nsw.gov.au/",
          "yearRange": "K-6",
          "icsea": 1080,
          "bottomSeaQuarter": 11,
          "lowerMiddleSeaQuarter": 22,
          "upperMiddleSeaQuarter": 34,
          "topSeaQuarter": 32,
          "totalEnrolments": 649,
          "girlsEnrolments": 319,
          "boysEnrolments": 330
        },
        "domainId": 784
      }
    },
    {
      "distance": 4233.416159746152,
      "school": {
        "schoolSector": "Catholic",
        "schoolType": "Secondary",
        "gender": "CoEd",
        "name": "St Patrick&apos;s Marist College",
        "suburb": "Dundas",
        "state": "NSW",
        "postcode": "2117",
        "centroid": "POINT (151.046824 -33.802722)",
        "profile": {
          "url": "http://www.stpatsdundas.catholic.edu.au",
          "yearRange": "7-12",
          "icsea": 1083,
          "bottomSeaQuarter": 9,
          "lowerMiddleSeaQuarter": 25,
          "upperMiddleSeaQuarter": 35,
          "topSeaQuarter": 31,
          "totalEnrolments": 1083,
          "girlsEnrolments": 517,
          "boysEnrolments": 566
        },
        "domainId": 7758
      }
    },
    {
      "distance": 4243.883679301822,
      "school": {
        "schoolSector": "Independent",
        "schoolType": "Primary",
        "name": "Amity College Auburn Campus",
        "suburb": "Auburn",
        "state": "NSW",
        "postcode": "2144",
        "centroid": "POINT (151.034 -33.8526)"
      }
    },
    {
      "distance": 4316.708015893107,
      "school": {
        "schoolSector": "Government",
        "schoolType": "Primary",
        "gender": "CoEd",
        "name": "Auburn Public School",
        "suburb": "Auburn",
        "state": "NSW",
        "postcode": "2144",
        "centroid": "POINT (151.032953 -33.854)",
        "profile": {
          "url": "https://auburn-p.schools.nsw.gov.au/",
          "yearRange": "K-6",
          "icsea": 975,
          "bottomSeaQuarter": 46,
          "lowerMiddleSeaQuarter": 23,
          "upperMiddleSeaQuarter": 22,
          "topSeaQuarter": 9,
          "totalEnrolments": 498,
          "girlsEnrolments": 209,
          "boysEnrolments": 289
        },
        "domainId": 22
      }
    },
    {
      "distance": 4318.080369368897,
      "school": {
        "schoolSector": "Independent",
        "schoolType": "Combined",
        "gender": "CoEd",
        "name": "Al-Faisal College",
        "suburb": "Auburn",
        "state": "NSW",
        "postcode": "2144",
        "centroid": "POINT (151.031559 -33.854755)",
        "profile": {
          "url": "http://www.afc.nsw.edu.au",
          "yearRange": "K-12",
          "icsea": 1069,
          "bottomSeaQuarter": 16,
          "lowerMiddleSeaQuarter": 23,
          "upperMiddleSeaQuarter": 31,
          "topSeaQuarter": 30,
          "totalEnrolments": 2174,
          "girlsEnrolments": 1127,
          "boysEnrolments": 1047
        },
        "domainId": 7807
      }
    },
    {
      "distance": 4349.166161017681,
      "school": {
        "schoolSector": "Government",
        "schoolType": "Primary",
        "gender": "CoEd",
        "name": "Telopea Public School",
        "suburb": "Telopea",
        "state": "NSW",
        "postcode": "2117",
        "centroid": "POINT (151.04364 -33.797032)",
        "profile": {
          "url": "http://www.telopea-p.schoolwebsites.com.au/",
          "yearRange": "K-6",
          "icsea": 998,
          "bottomSeaQuarter": 34,
          "lowerMiddleSeaQuarter": 32,
          "upperMiddleSeaQuarter": 21,
          "topSeaQuarter": 13,
          "totalEnrolments": 110,
          "girlsEnrolments": 55,
          "boysEnrolments": 55
        },
        "domainId": 513
      }
    },
    {
      "distance": 4383.808987083176,
      "school": {
        "schoolSector": "Government",
        "schoolType": "Primary",
        "gender": "CoEd",
        "name": "Darcy Road Public School",
        "suburb": "Wentworthville",
        "state": "NSW",
        "postcode": "2145",
        "centroid": "POINT (150.96599 -33.801954)",
        "profile": {
          "url": "https://darcyroad-p.schools.nsw.gov.au",
          "yearRange": "K-6",
          "icsea": 1124,
          "bottomSeaQuarter": 5,
          "lowerMiddleSeaQuarter": 9,
          "upperMiddleSeaQuarter": 32,
          "topSeaQuarter": 54,
          "totalEnrolments": 648,
          "girlsEnrolments": 315,
          "boysEnrolments": 333
        },
        "domainId": 450
      }
    },
    {
      "distance": 4417.12078545958,
      "school": {
        "schoolSector": "Independent",
        "schoolType": "Secondary",
        "gender": "CoEd",
        "name": "My Dream Australian Academy",
        "suburb": "Auburn",
        "state": "NSW",
        "postcode": "2144",
        "centroid": "POINT (151.0340006 -33.85450296)",
        "profile": {
          "url": "https://www.mydreamaa.com.au/",
          "yearRange": "7-8",
          "icsea": 1031,
          "bottomSeaQuarter": 21,
          "lowerMiddleSeaQuarter": 42,
          "upperMiddleSeaQuarter": 29,
          "topSeaQuarter": 8,
          "totalEnrolments": 26,
          "girlsEnrolments": 9,
          "boysEnrolments": 17
        }
      }
    },
    {
      "distance": 4419.350614960913,
      "school": {
        "schoolSector": "Government",
        "schoolType": "Secondary",
        "gender": "CoEd",
        "name": "Granville South Creative and Performing Arts High School",
        "suburb": "Guildford",
        "state": "NSW",
        "postcode": "2161",
        "centroid": "POINT (150.997598 -33.860626)",
        "profile": {
          "url": "http://granvilles-h.schools.nsw.edu.au",
          "yearRange": "7-12",
          "icsea": 865,
          "bottomSeaQuarter": 84,
          "lowerMiddleSeaQuarter": 11,
          "upperMiddleSeaQuarter": 3,
          "topSeaQuarter": 1,
          "totalEnrolments": 706,
          "girlsEnrolments": 340,
          "boysEnrolments": 366
        },
        "domainId": 391
      }
    },
    {
      "distance": 4426.9168789513105,
      "school": {
        "schoolSector": "Catholic",
        "schoolType": "Primary",
        "gender": "CoEd",
        "name": "St Patrick&apos;s Primary School",
        "suburb": "Guildford",
        "state": "NSW",
        "postcode": "2161",
        "centroid": "POINT (150.978105 -33.853384)",
        "profile": {
          "url": "http://www.stpatsguildford.catholic.edu.au",
          "yearRange": "K-6",
          "icsea": 1024,
          "bottomSeaQuarter": 25,
          "lowerMiddleSeaQuarter": 34,
          "upperMiddleSeaQuarter": 28,
          "topSeaQuarter": 12,
          "totalEnrolments": 336,
          "girlsEnrolments": 163,
          "boysEnrolments": 173
        },
        "domainId": 10079
      }
    },
    {
      "distance": 4442.942021066655,
      "school": {
        "schoolSector": "Government",
        "schoolType": "Primary",
        "gender": "CoEd",
        "name": "Ringrose Public School",
        "suburb": "Greystanes",
        "state": "NSW",
        "postcode": "2145",
        "centroid": "POINT (150.95902613 -33.82002221)",
        "profile": {
          "url": "https://ringrose-p.schools.nsw.gov.au/",
          "yearRange": "K-6",
          "icsea": 1004,
          "bottomSeaQuarter": 33,
          "lowerMiddleSeaQuarter": 30,
          "upperMiddleSeaQuarter": 25,
          "topSeaQuarter": 12,
          "totalEnrolments": 383,
          "girlsEnrolments": 198,
          "boysEnrolments": 185
        },
        "domainId": 819
      }
    },
    {
      "distance": 4448.96636360854,
      "school": {
        "schoolSector": "Government",
        "schoolType": "Primary",
        "gender": "CoEd",
        "name": "Guildford Public School",
        "suburb": "Guildford",
        "state": "NSW",
        "postcode": "2161",
        "centroid": "POINT (150.981983 -33.855821)",
        "profile": {
          "url": "https://guildford-p.schools.nsw.gov.au",
          "yearRange": "K-6",
          "icsea": 945,
          "bottomSeaQuarter": 57,
          "lowerMiddleSeaQuarter": 23,
          "upperMiddleSeaQuarter": 15,
          "topSeaQuarter": 5,
          "totalEnrolments": 599,
          "girlsEnrolments": 262,
          "boysEnrolments": 337
        },
        "domainId": 663
      }
    },
    {
      "distance": 4520.069511478593,
      "school": {
        "schoolSector": "Government",
        "schoolType": "Primary",
        "gender": "CoEd",
        "name": "Auburn West Public School",
        "suburb": "Auburn",
        "state": "NSW",
        "postcode": "2144",
        "centroid": "POINT (151.019764 -33.860931)",
        "profile": {
          "url": "https://auburnwest-p.schools.nsw.gov.au/",
          "yearRange": "K-6",
          "icsea": 932,
          "bottomSeaQuarter": 65,
          "lowerMiddleSeaQuarter": 22,
          "upperMiddleSeaQuarter": 10,
          "topSeaQuarter": 4,
          "totalEnrolments": 559,
          "girlsEnrolments": 280,
          "boysEnrolments": 279
        },
        "domainId": 36
      }
    },
    {
      "distance": 4569.493912740112,
      "school": {
        "schoolSector": "Independent",
        "schoolType": "Combined",
        "gender": "CoEd",
        "name": "International Maarif Schools of Australia - Gallipoli Campus",
        "suburb": "Auburn",
        "state": "NSW",
        "postcode": "2144",
        "centroid": "POINT (151.037238 -33.854247)",
        "profile": {
          "url": "https://www.maarif.nsw.edu.au/",
          "yearRange": "K-9",
          "icsea": 1041,
          "bottomSeaQuarter": 19,
          "lowerMiddleSeaQuarter": 30,
          "upperMiddleSeaQuarter": 33,
          "topSeaQuarter": 17,
          "totalEnrolments": 410,
          "girlsEnrolments": 187,
          "boysEnrolments": 223
        }
      }
    },
    {
      "distance": 4579.804507504212,
      "school": {
        "schoolSector": "Independent",
        "schoolType": "Combined",
        "gender": "CoEd",
        "name": "OneSchool Global NSW - Sydney",
        "suburb": "Oatlands",
        "state": "NSW",
        "postcode": "2117",
        "centroid": "POINT (151.032553 -33.786338)",
        "profile": {
          "url": "https://www.oneschoolglobal.com/regions/australia/new-south-wales/",
          "yearRange": "3-12",
          "icsea": 978,
          "bottomSeaQuarter": 46,
          "lowerMiddleSeaQuarter": 47,
          "upperMiddleSeaQuarter": 7,
          "topSeaQuarter": 0,
          "totalEnrolments": 296,
          "girlsEnrolments": 137,
          "boysEnrolments": 159
        }
      }
    },
    {
      "distance": 4653.340209552136,
      "school": {
        "schoolSector": "Government",
        "schoolType": "Primary",
        "gender": "CoEd",
        "name": "Sherwood Grange Public School",
        "suburb": "Merrylands",
        "state": "NSW",
        "postcode": "2160",
        "centroid": "POINT (150.960103 -33.836784)",
        "profile": {
          "url": "https://sherwoodgr-p.schools.nsw.gov.au",
          "yearRange": "K-6",
          "icsea": 998,
          "bottomSeaQuarter": 37,
          "lowerMiddleSeaQuarter": 30,
          "upperMiddleSeaQuarter": 23,
          "topSeaQuarter": 11,
          "totalEnrolments": 257,
          "girlsEnrolments": 128,
          "boysEnrolments": 129
        },
        "domainId": 170
      }
    },
    {
      "distance": 4670.810587007758,
      "school": {
        "schoolSector": "Government",
        "schoolType": "Combined",
        "gender": "CoEd",
        "name": "The Hills School",
        "suburb": "Northmead",
        "state": "NSW",
        "postcode": "2152",
        "centroid": "POINT (151.003951 -33.779745)",
        "profile": {
          "url": "https://thehills-s.schools.nsw.gov.au/",
          "yearRange": "U",
          "icsea": 1049,
          "bottomSeaQuarter": 21,
          "lowerMiddleSeaQuarter": 22,
          "upperMiddleSeaQuarter": 31,
          "topSeaQuarter": 26,
          "totalEnrolments": 114,
          "girlsEnrolments": 45,
          "boysEnrolments": 69
        },
        "domainId": 7856
      }
    },
    {
      "distance": 4734.0067862889355,
      "school": {
        "schoolSector": "Government",
        "schoolType": "Primary",
        "gender": "CoEd",
        "name": "Guildford West Public School",
        "suburb": "Guildford West",
        "state": "NSW",
        "postcode": "2161",
        "centroid": "POINT (150.966496 -33.847672)",
        "profile": {
          "url": "https://guildfordw-p.schools.nsw.gov.au",
          "yearRange": "K-6",
          "icsea": 967,
          "bottomSeaQuarter": 48,
          "lowerMiddleSeaQuarter": 30,
          "upperMiddleSeaQuarter": 16,
          "topSeaQuarter": 6,
          "totalEnrolments": 363,
          "girlsEnrolments": 172,
          "boysEnrolments": 191
        },
        "domainId": 309
      }
    },
    {
      "distance": 4747.390821282976,
      "school": {
        "schoolSector": "Government",
        "schoolType": "Primary",
        "gender": "CoEd",
        "name": "Rydalmere East Public School",
        "suburb": "Ermington",
        "state": "NSW",
        "postcode": "2115",
        "centroid": "POINT (151.05818 -33.817144)",
        "profile": {
          "url": "https://rydalmeree-p.schools.nsw.gov.au/",
          "yearRange": "P-6",
          "icsea": 1022,
          "bottomSeaQuarter": 22,
          "lowerMiddleSeaQuarter": 33,
          "upperMiddleSeaQuarter": 32,
          "topSeaQuarter": 14,
          "totalEnrolments": 113,
          "girlsEnrolments": 43,
          "boysEnrolments": 70
        },
        "domainId": 538
      }
    },
    {
      "distance": 4896.4686900308425,
      "school": {
        "schoolSector": "Government",
        "schoolType": "Primary",
        "gender": "CoEd",
        "name": "Newington Public School",
        "suburb": "Newington",
        "state": "NSW",
        "postcode": "2127",
        "centroid": "POINT (151.05464 -33.841119)",
        "profile": {
          "url": "https://www.newington-p.schools.nsw.gov.au",
          "yearRange": "K-6",
          "icsea": 1116,
          "bottomSeaQuarter": 4,
          "lowerMiddleSeaQuarter": 16,
          "upperMiddleSeaQuarter": 33,
          "topSeaQuarter": 47,
          "totalEnrolments": 696,
          "girlsEnrolments": 340,
          "boysEnrolments": 356
        },
        "domainId": 775
      }
    },
    {
      "distance": 4902.19089399304,
      "school": {
        "schoolSector": "Government",
        "schoolType": "Secondary",
        "gender": "CoEd",
        "name": "Cumberland High School",
        "suburb": "Carlingford",
        "state": "NSW",
        "postcode": "2118",
        "centroid": "POINT (151.036898 -33.785205)",
        "profile": {
          "url": "https://cumberland-h.schools.nsw.gov.au",
          "yearRange": "7-12",
          "icsea": 1055,
          "bottomSeaQuarter": 18,
          "lowerMiddleSeaQuarter": 25,
          "upperMiddleSeaQuarter": 32,
          "topSeaQuarter": 25,
          "totalEnrolments": 985,
          "girlsEnrolments": 412,
          "boysEnrolments": 573
        },
        "domainId": 607
      }
    },
    {
      "distance": 4903.971714485186,
      "school": {
        "schoolSector": "Catholic",
        "schoolType": "Primary",
        "gender": "CoEd",
        "name": "Our Lady Queen of Peace Primary School",
        "suburb": "Greystanes",
        "state": "NSW",
        "postcode": "2145",
        "centroid": "POINT (150.95404 -33.819847)",
        "profile": {
          "url": "http://www.olqpgreystanes.catholic.edu.au",
          "yearRange": "K-6",
          "icsea": 1061,
          "bottomSeaQuarter": 15,
          "lowerMiddleSeaQuarter": 28,
          "upperMiddleSeaQuarter": 33,
          "topSeaQuarter": 25,
          "totalEnrolments": 829,
          "girlsEnrolments": 413,
          "boysEnrolments": 416
        },
        "domainId": 10059
      }
    }
]';
$extracted_schools = rc_domain_extract_schools($fetched_schools);

// $fetched_demographics = json_decode(rc_domain_fetch_demographics($state, $suburb, $postcode));
$fetched_demographics = '
{
  "demographics": [
    {
      "type": "Rent",
      "total": 6101,
      "year": 2016,
      "items": [
        {
          "label": "75 to 99",
          "value": 18,
          "composition": "Total"
        },
        {
          "label": "950+",
          "value": 19,
          "composition": "Total"
        },
        {
          "label": "850 to 949",
          "value": 22,
          "composition": "Total"
        },
        {
          "label": "750 to 849",
          "value": 42,
          "composition": "Total"
        },
        {
          "label": "200 to 224",
          "value": 81,
          "composition": "Total"
        },
        {
          "label": "0 to 74",
          "value": 87,
          "composition": "Total"
        },
        {
          "label": "150 to 199",
          "value": 88,
          "composition": "Total"
        },
        {
          "label": "650 to 749",
          "value": 101,
          "composition": "Total"
        },
        {
          "label": "225 to 274",
          "value": 117,
          "composition": "Total"
        },
        {
          "label": "Rent not stated",
          "value": 143,
          "composition": "Total"
        },
        {
          "label": "100 to 149",
          "value": 148,
          "composition": "Total"
        },
        {
          "label": "275 to 349",
          "value": 454,
          "composition": "Total"
        },
        {
          "label": "550 to 649",
          "value": 731,
          "composition": "Total"
        },
        {
          "label": "450 to 549",
          "value": 1791,
          "composition": "Total"
        },
        {
          "label": "350 to 449",
          "value": 2260,
          "composition": "Total"
        }
      ]
    },
    {
      "type": "HouseholdIncome",
      "total": 8917,
      "year": 2016,
      "items": [
        {
          "label": "1 to 149",
          "value": 66,
          "composition": "Total"
        },
        {
          "label": "All incomes not stated",
          "value": 100,
          "composition": "Total"
        },
        {
          "label": "150 to 299",
          "value": 166,
          "composition": "Total"
        },
        {
          "label": "300 to 399",
          "value": 191,
          "composition": "Total"
        },
        {
          "label": "Nil",
          "value": 219,
          "composition": "Total"
        },
        {
          "label": "500 to 649",
          "value": 252,
          "composition": "Total"
        },
        {
          "label": "400 to 499",
          "value": 296,
          "composition": "Total"
        },
        {
          "label": "3500 to 3999",
          "value": 400,
          "composition": "Total"
        },
        {
          "label": "650 to 799",
          "value": 417,
          "composition": "Total"
        },
        {
          "label": "800 to 999",
          "value": 475,
          "composition": "Total"
        },
        {
          "label": "3000 to 3499",
          "value": 495,
          "composition": "Total"
        },
        {
          "label": "4000+",
          "value": 514,
          "composition": "Total"
        },
        {
          "label": "Partial income stated",
          "value": 532,
          "composition": "Total"
        },
        {
          "label": "2500 to 2999",
          "value": 622,
          "composition": "Total"
        },
        {
          "label": "1500 to 1749",
          "value": 682,
          "composition": "Total"
        },
        {
          "label": "1000 to 1249",
          "value": 691,
          "composition": "Total"
        },
        {
          "label": "1250 to 1499",
          "value": 726,
          "composition": "Total"
        },
        {
          "label": "1750 to 1999",
          "value": 766,
          "composition": "Total"
        },
        {
          "label": "2000 to 2499",
          "value": 1320,
          "composition": "Total"
        }
      ]
    },
    {
      "type": "FamilyComposition",
      "total": 6429,
      "year": 2016,
      "items": [
        {
          "label": "Other Familiy",
          "value": 205,
          "composition": "Families"
        },
        {
          "label": "One Parent (all)",
          "value": 738,
          "composition": "Families"
        },
        {
          "label": "Couple with no children",
          "value": 2448,
          "composition": "Families"
        },
        {
          "label": "Couples (all)",
          "value": 3043,
          "composition": "Families"
        }
      ]
    },
    {
      "type": "TransportToWork",
      "total": 12700,
      "year": 2016,
      "items": [
        {
          "label": "Other three",
          "value": 0,
          "composition": "Persons"
        },
        {
          "label": "Bus & Tram",
          "value": 0,
          "composition": "Persons"
        },
        {
          "label": "Bus & Ferry",
          "value": 0,
          "composition": "Persons"
        },
        {
          "label": "Ferry",
          "value": 0,
          "composition": "Persons"
        },
        {
          "label": "Bus & other two",
          "value": 3,
          "composition": "Persons"
        },
        {
          "label": "Train & Ferry",
          "value": 5,
          "composition": "Persons"
        },
        {
          "label": "Bus & Other",
          "value": 6,
          "composition": "Persons"
        },
        {
          "label": "Tram",
          "value": 6,
          "composition": "Persons"
        },
        {
          "label": "Bus & Car (driver)",
          "value": 12,
          "composition": "Persons"
        },
        {
          "label": "Bus & Car (Pas.)",
          "value": 23,
          "composition": "Persons"
        },
        {
          "label": "Truck",
          "value": 26,
          "composition": "Persons"
        },
        {
          "label": "Train & Car (Pas.)",
          "value": 31,
          "composition": "Persons"
        },
        {
          "label": "Train & Other",
          "value": 32,
          "composition": "Persons"
        },
        {
          "label": "Taxi",
          "value": 35,
          "composition": "Persons"
        },
        {
          "label": "Train & Tram",
          "value": 37,
          "composition": "Persons"
        },
        {
          "label": "Other two",
          "value": 39,
          "composition": "Persons"
        },
        {
          "label": "Motorbike/scooter",
          "value": 39,
          "composition": "Persons"
        },
        {
          "label": "Other",
          "value": 43,
          "composition": "Persons"
        },
        {
          "label": "Train & Car (driver)",
          "value": 59,
          "composition": "Persons"
        },
        {
          "label": "Bicycle",
          "value": 69,
          "composition": "Persons"
        },
        {
          "label": "Train & other two",
          "value": 102,
          "composition": "Persons"
        },
        {
          "label": "Not Stated",
          "value": 103,
          "composition": "Persons"
        },
        {
          "label": "Worked at home",
          "value": 249,
          "composition": "Persons"
        },
        {
          "label": "Car (Pas.)",
          "value": 354,
          "composition": "Persons"
        },
        {
          "label": "Did not go to work",
          "value": 736,
          "composition": "Persons"
        },
        {
          "label": "Train & Bus",
          "value": 794,
          "composition": "Persons"
        },
        {
          "label": "Bus",
          "value": 878,
          "composition": "Persons"
        },
        {
          "label": "Walked only",
          "value": 1243,
          "composition": "Persons"
        },
        {
          "label": "Train",
          "value": 3761,
          "composition": "Persons"
        },
        {
          "label": "Car (driver)",
          "value": 4023,
          "composition": "Persons"
        }
      ]
    },
    {
      "type": "Religion",
      "total": 25798,
      "year": 2016,
      "items": [
        {
          "label": "Aboriginal Religions",
          "value": 3,
          "composition": "Persons"
        },
        {
          "label": "Brethren",
          "value": 3,
          "composition": "Persons"
        },
        {
          "label": "Assyrian Apostolic",
          "value": 3,
          "composition": "Persons"
        },
        {
          "label": "Salvation Army",
          "value": 7,
          "composition": "Persons"
        },
        {
          "label": "Judaism",
          "value": 12,
          "composition": "Persons"
        },
        {
          "label": "Other Christian",
          "value": 12,
          "composition": "Persons"
        },
        {
          "label": "Churches of Christ",
          "value": 14,
          "composition": "Persons"
        },
        {
          "label": "Lutheran",
          "value": 20,
          "composition": "Persons"
        },
        {
          "label": "Latter Day Saints",
          "value": 22,
          "composition": "Persons"
        },
        {
          "label": "Jehovahs Witnesses",
          "value": 33,
          "composition": "Persons"
        },
        {
          "label": "Other affiliation",
          "value": 43,
          "composition": "Persons"
        },
        {
          "label": "Seventh Day Adventist",
          "value": 44,
          "composition": "Persons"
        },
        {
          "label": "Oriental Orthodox",
          "value": 80,
          "composition": "Persons"
        },
        {
          "label": "Other Protestant",
          "value": 82,
          "composition": "Persons"
        },
        {
          "label": "Pentecostal",
          "value": 169,
          "composition": "Persons"
        },
        {
          "label": "Baptist",
          "value": 209,
          "composition": "Persons"
        },
        {
          "label": "Other group",
          "value": 240,
          "composition": "Persons"
        },
        {
          "label": "Uniting Church",
          "value": 247,
          "composition": "Persons"
        },
        {
          "label": "Presbyterian",
          "value": 316,
          "composition": "Persons"
        },
        {
          "label": "Eastern Orthodox",
          "value": 372,
          "composition": "Persons"
        },
        {
          "label": "Christian (Not Stated)",
          "value": 453,
          "composition": "Persons"
        },
        {
          "label": "Sikhism",
          "value": 614,
          "composition": "Persons"
        },
        {
          "label": "Anglican",
          "value": 942,
          "composition": "Persons"
        },
        {
          "label": "Buddhism",
          "value": 1073,
          "composition": "Persons"
        },
        {
          "label": "Islam",
          "value": 1605,
          "composition": "Persons"
        },
        {
          "label": "Not Stated",
          "value": 2977,
          "composition": "Persons"
        },
        {
          "label": "Catholic",
          "value": 3267,
          "composition": "Persons"
        },
        {
          "label": "No Religion",
          "value": 5512,
          "composition": "Persons"
        },
        {
          "label": "Hinduism",
          "value": 7353,
          "composition": "Persons"
        }
      ]
    },
    {
      "type": "MaritalStatus",
      "total": 21614,
      "year": 2016,
      "items": [
        {
          "label": "Separated",
          "value": 525,
          "composition": "Persons"
        },
        {
          "label": "Widowed",
          "value": 544,
          "composition": "Persons"
        },
        {
          "label": "Divorced",
          "value": 1350,
          "composition": "Persons"
        },
        {
          "label": "Never Married",
          "value": 7287,
          "composition": "Persons"
        },
        {
          "label": "Married",
          "value": 11908,
          "composition": "Persons"
        }
      ]
    },
    {
      "type": "CountryOfBirth",
      "total": 25798,
      "year": 2016,
      "items": [
        {
          "label": "Wales",
          "value": 3,
          "composition": "Persons"
        },
        {
          "label": "Macedonia",
          "value": 3,
          "composition": "Persons"
        },
        {
          "label": "Papua New Guinea",
          "value": 10,
          "composition": "Persons"
        },
        {
          "label": "Northern Ireland",
          "value": 10,
          "composition": "Persons"
        },
        {
          "label": "Netherlands",
          "value": 18,
          "composition": "Persons"
        },
        {
          "label": "Zimbabwe",
          "value": 20,
          "composition": "Persons"
        },
        {
          "label": "Malta",
          "value": 20,
          "composition": "Persons"
        },
        {
          "label": "Mauritius",
          "value": 24,
          "composition": "Persons"
        },
        {
          "label": "France",
          "value": 24,
          "composition": "Persons"
        },
        {
          "label": "Ireland",
          "value": 26,
          "composition": "Persons"
        },
        {
          "label": "South Eastern Europe",
          "value": 28,
          "composition": "Persons"
        },
        {
          "label": "Scotland",
          "value": 29,
          "composition": "Persons"
        },
        {
          "label": "Canada",
          "value": 29,
          "composition": "Persons"
        },
        {
          "label": "Chile",
          "value": 36,
          "composition": "Persons"
        },
        {
          "label": "South_Africa",
          "value": 38,
          "composition": "Persons"
        },
        {
          "label": "Myanmar",
          "value": 41,
          "composition": "Persons"
        },
        {
          "label": "Germany",
          "value": 41,
          "composition": "Persons"
        },
        {
          "label": "Croatia",
          "value": 42,
          "composition": "Persons"
        },
        {
          "label": "Cambodia",
          "value": 42,
          "composition": "Persons"
        },
        {
          "label": "Bosnia & Herzegovina",
          "value": 42,
          "composition": "Persons"
        },
        {
          "label": "Poland",
          "value": 53,
          "composition": "Persons"
        },
        {
          "label": "Turkey",
          "value": 55,
          "composition": "Persons"
        },
        {
          "label": "Japan",
          "value": 61,
          "composition": "Persons"
        },
        {
          "label": "Singapore",
          "value": 62,
          "composition": "Persons"
        },
        {
          "label": "Italy",
          "value": 62,
          "composition": "Persons"
        },
        {
          "label": "USA",
          "value": 76,
          "composition": "Persons"
        },
        {
          "label": "Egypt",
          "value": 77,
          "composition": "Persons"
        },
        {
          "label": "Greece",
          "value": 94,
          "composition": "Persons"
        },
        {
          "label": "Iraq",
          "value": 95,
          "composition": "Persons"
        },
        {
          "label": "Thailand",
          "value": 117,
          "composition": "Persons"
        },
        {
          "label": "Indonesia",
          "value": 117,
          "composition": "Persons"
        },
        {
          "label": "Afghanistan",
          "value": 121,
          "composition": "Persons"
        },
        {
          "label": "Taiwan",
          "value": 127,
          "composition": "Persons"
        },
        {
          "label": "Fiji",
          "value": 142,
          "composition": "Persons"
        },
        {
          "label": "Sri Lanka",
          "value": 183,
          "composition": "Persons"
        },
        {
          "label": "Malaysia",
          "value": 189,
          "composition": "Persons"
        },
        {
          "label": "England",
          "value": 199,
          "composition": "Persons"
        },
        {
          "label": "Vietnam",
          "value": 202,
          "composition": "Persons"
        },
        {
          "label": "Bangladesh",
          "value": 203,
          "composition": "Persons"
        },
        {
          "label": "Pakistan",
          "value": 279,
          "composition": "Persons"
        },
        {
          "label": "Lebanon",
          "value": 281,
          "composition": "Persons"
        },
        {
          "label": "Hong Kong",
          "value": 290,
          "composition": "Persons"
        },
        {
          "label": "New Zealand",
          "value": 322,
          "composition": "Persons"
        },
        {
          "label": "Iran",
          "value": 364,
          "composition": "Persons"
        },
        {
          "label": "Nepal",
          "value": 378,
          "composition": "Persons"
        },
        {
          "label": "South Korea",
          "value": 392,
          "composition": "Persons"
        },
        {
          "label": "Philippines",
          "value": 575,
          "composition": "Persons"
        },
        {
          "label": "Born Elsewhere",
          "value": 1007,
          "composition": "Persons"
        },
        {
          "label": "Country of Birth Not Stated",
          "value": 2113,
          "composition": "Persons"
        },
        {
          "label": "China",
          "value": 3088,
          "composition": "Persons"
        },
        {
          "label": "Australia",
          "value": 6263,
          "composition": "Persons"
        },
        {
          "label": "India",
          "value": 7693,
          "composition": "Persons"
        }
      ]
    },
    {
      "type": "DwellingStructure",
      "total": 8917,
      "year": 2016,
      "items": [
        {
          "label": "Not Stated",
          "value": 32,
          "composition": "Dwelling"
        },
        {
          "label": "Other",
          "value": 104,
          "composition": "Dwelling"
        },
        {
          "label": "Semi/Terrace",
          "value": 593,
          "composition": "Dwelling"
        },
        {
          "label": "Separate House",
          "value": 912,
          "composition": "Dwelling"
        },
        {
          "label": "Flat",
          "value": 7278,
          "composition": "Dwelling"
        }
      ]
    },
    {
      "type": "HousingLoanRepayment",
      "total": 1556,
      "year": 2016,
      "items": [
        {
          "label": "$450-$599",
          "value": 16,
          "composition": "Dwelling"
        },
        {
          "label": "$300-$449",
          "value": 28,
          "composition": "Dwelling"
        },
        {
          "label": "$600-$799",
          "value": 44,
          "composition": "Dwelling"
        },
        {
          "label": "Not Stated",
          "value": 54,
          "composition": "Dwelling"
        },
        {
          "label": "$800-$999",
          "value": 66,
          "composition": "Dwelling"
        },
        {
          "label": "$1-$299",
          "value": 83,
          "composition": "Dwelling"
        },
        {
          "label": "$4000+",
          "value": 84,
          "composition": "Dwelling"
        },
        {
          "label": "$3000-$3999",
          "value": 133,
          "composition": "Dwelling"
        },
        {
          "label": "$1000-$1399",
          "value": 183,
          "composition": "Dwelling"
        },
        {
          "label": "$2400-$2999",
          "value": 191,
          "composition": "Dwelling"
        },
        {
          "label": "$1400-$1799",
          "value": 280,
          "composition": "Dwelling"
        },
        {
          "label": "$1800-$2399",
          "value": 398,
          "composition": "Dwelling"
        }
      ]
    },
    {
      "type": "NatureOfOccupancy",
      "total": 8917,
      "year": 2016,
      "items": [
        {
          "label": "Other tenure type",
          "value": 47,
          "composition": "Total"
        },
        {
          "label": "Not Stated",
          "value": 258,
          "composition": "Total"
        },
        {
          "label": "Fully Owned",
          "value": 958,
          "composition": "Total"
        },
        {
          "label": "Purchasing",
          "value": 1556,
          "composition": "Total"
        },
        {
          "label": "Rented",
          "value": 6101,
          "composition": "Total"
        }
      ]
    },
    {
      "type": "Occupation",
      "total": 12700,
      "year": 2016,
      "items": [
        {
          "label": "Inadequately Described/Not Stated",
          "value": 288,
          "composition": "Total"
        },
        {
          "label": "Machinery Operators and Drivers",
          "value": 618,
          "composition": "Total"
        },
        {
          "label": "Labourers",
          "value": 912,
          "composition": "Total"
        },
        {
          "label": "Community and Personal Service Workers",
          "value": 979,
          "composition": "Total"
        },
        {
          "label": "Sales Workers",
          "value": 1023,
          "composition": "Total"
        },
        {
          "label": "Technicians and Trade Workers",
          "value": 1105,
          "composition": "Total"
        },
        {
          "label": "Managers",
          "value": 1335,
          "composition": "Total"
        },
        {
          "label": "Clerical and Administrative Workers",
          "value": 1710,
          "composition": "Total"
        },
        {
          "label": "Professionals",
          "value": 4733,
          "composition": "Total"
        }
      ]
    },
    {
      "type": "EducationAttendance",
      "total": 7692,
      "year": 2016,
      "items": [
        {
          "label": "Pre-school",
          "value": 373,
          "composition": "Persons"
        },
        {
          "label": "Other",
          "value": 432,
          "composition": "Persons"
        },
        {
          "label": "Technical/Further",
          "value": 545,
          "composition": "Persons"
        },
        {
          "label": "Secondary Education",
          "value": 710,
          "composition": "Persons"
        },
        {
          "label": "Infants/Primary",
          "value": 1260,
          "composition": "Persons"
        },
        {
          "label": "University",
          "value": 2126,
          "composition": "Persons"
        },
        {
          "label": "Not Stated",
          "value": 2236,
          "composition": "Persons"
        }
      ]
    },
    {
      "type": "AgeGroupOfPopulation",
      "total": 25798,
      "year": 2016,
      "items": [
        {
          "label": "0 to 4",
          "value": 2325,
          "composition": "Persons"
        },
        {
          "label": "60+",
          "value": 2524,
          "composition": "Persons"
        },
        {
          "label": "5 to 19",
          "value": 2612,
          "composition": "Persons"
        },
        {
          "label": "40 to 59",
          "value": 4416,
          "composition": "Persons"
        },
        {
          "label": "20 to 39",
          "value": 13916,
          "composition": "Persons"
        }
      ]
    },
    {
      "type": "GeographicalPopulation",
      "total": 25798,
      "year": 2016,
      "items": [
        {
          "label": "Population",
          "value": 25798,
          "composition": "Persons"
        }
      ]
    }
  ]
}';
$extracted_demographics = rc_domain_extract_demographics($fetched_demographics);

// $fetched_suburb_performance_statistics = json_decode($extracted_suburb_performance_statistics($state, $suburb, $postcode));
$fetched_suburb_performance_statistics = '
{
  "header": {
    "suburb": "Parramatta",
    "state": "NSW",
    "propertyCategory": "House"
  },
  "series": {
    "seriesInfo": [
      {
        "year": 2014,
        "month": 11,
        "values": {
          "medianSoldPrice": 855000,
          "numberSold": 22,
          "highestSoldPrice": 2700000,
          "lowestSoldPrice": 456000,
          "5thPercentileSoldPrice": 572000,
          "25thPercentileSoldPrice": 673000,
          "75thPercentileSoldPrice": 1121000,
          "95thPercentileSoldPrice": 1925000,
          "medianSaleListingPrice": 775000,
          "numberSaleListing": 29,
          "highestSaleListingPrice": 3000000,
          "lowestSaleListingPrice": 190000,
          "auctionNumberAuctioned": 6,
          "auctionNumberSold": 6,
          "auctionNumberWithdrawn": null,
          "daysOnMarket": 37,
          "discountPercentage": null,
          "medianRentListingPrice": 470,
          "numberRentListing": 72,
          "highestRentListingPrice": 1200,
          "lowestRentListingPrice": 150
        }
      },
      {
        "year": 2015,
        "month": 2,
        "values": {
          "medianSoldPrice": 715000,
          "numberSold": 19,
          "highestSoldPrice": 1625000,
          "lowestSoldPrice": 530000,
          "5thPercentileSoldPrice": 530000,
          "25thPercentileSoldPrice": 680000,
          "75thPercentileSoldPrice": 900000,
          "95thPercentileSoldPrice": 1625000,
          "medianSaleListingPrice": 659000,
          "numberSaleListing": 21,
          "highestSaleListingPrice": 2700000,
          "lowestSaleListingPrice": 160000,
          "auctionNumberAuctioned": 8,
          "auctionNumberSold": 8,
          "auctionNumberWithdrawn": null,
          "daysOnMarket": null,
          "discountPercentage": null,
          "medianRentListingPrice": 500,
          "numberRentListing": 60,
          "highestRentListingPrice": 3270,
          "lowestRentListingPrice": 195
        }
      },
      {
        "year": 2015,
        "month": 5,
        "values": {
          "medianSoldPrice": 766000,
          "numberSold": 26,
          "highestSoldPrice": 2290000,
          "lowestSoldPrice": 613000,
          "5thPercentileSoldPrice": 613000,
          "25thPercentileSoldPrice": 700000,
          "75thPercentileSoldPrice": 1291000,
          "95thPercentileSoldPrice": 1728000,
          "medianSaleListingPrice": 748000,
          "numberSaleListing": 32,
          "highestSaleListingPrice": 2700000,
          "lowestSaleListingPrice": 160000,
          "auctionNumberAuctioned": 8,
          "auctionNumberSold": 7,
          "auctionNumberWithdrawn": null,
          "daysOnMarket": 11,
          "discountPercentage": null,
          "medianRentListingPrice": 520,
          "numberRentListing": 75,
          "highestRentListingPrice": 3270,
          "lowestRentListingPrice": 195
        }
      },
      {
        "year": 2015,
        "month": 8,
        "values": {
          "medianSoldPrice": 983000,
          "numberSold": 24,
          "highestSoldPrice": 4900000,
          "lowestSoldPrice": 485000,
          "5thPercentileSoldPrice": 550000,
          "25thPercentileSoldPrice": 720000,
          "75thPercentileSoldPrice": 1360000,
          "95thPercentileSoldPrice": 1950000,
          "medianSaleListingPrice": 884000,
          "numberSaleListing": 24,
          "highestSaleListingPrice": 4190000,
          "lowestSaleListingPrice": 530000,
          "auctionNumberAuctioned": 8,
          "auctionNumberSold": 4,
          "auctionNumberWithdrawn": null,
          "daysOnMarket": 23,
          "discountPercentage": null,
          "medianRentListingPrice": 510,
          "numberRentListing": 77,
          "highestRentListingPrice": 3270,
          "lowestRentListingPrice": 270
        }
      },
      {
        "year": 2015,
        "month": 11,
        "values": {
          "medianSoldPrice": 880000,
          "numberSold": 19,
          "highestSoldPrice": 2100000,
          "lowestSoldPrice": 565000,
          "5thPercentileSoldPrice": 565000,
          "25thPercentileSoldPrice": 800000,
          "75thPercentileSoldPrice": 1080000,
          "95thPercentileSoldPrice": 2100000,
          "medianSaleListingPrice": 950000,
          "numberSaleListing": 27,
          "highestSaleListingPrice": 3590000,
          "lowestSaleListingPrice": 580000,
          "auctionNumberAuctioned": 8,
          "auctionNumberSold": 3,
          "auctionNumberWithdrawn": null,
          "daysOnMarket": null,
          "discountPercentage": null,
          "medianRentListingPrice": 520,
          "numberRentListing": 87,
          "highestRentListingPrice": 3270,
          "lowestRentListingPrice": 180
        }
      },
      {
        "year": 2016,
        "month": 2,
        "values": {
          "medianSoldPrice": null,
          "numberSold": 5,
          "highestSoldPrice": 1030000,
          "lowestSoldPrice": 510000,
          "5thPercentileSoldPrice": null,
          "25thPercentileSoldPrice": null,
          "75thPercentileSoldPrice": null,
          "95thPercentileSoldPrice": null,
          "medianSaleListingPrice": 875000,
          "numberSaleListing": 21,
          "highestSaleListingPrice": 1325000,
          "lowestSaleListingPrice": 400000,
          "auctionNumberAuctioned": 5,
          "auctionNumberSold": 4,
          "auctionNumberWithdrawn": null,
          "daysOnMarket": null,
          "discountPercentage": null,
          "medianRentListingPrice": 510,
          "numberRentListing": 95,
          "highestRentListingPrice": 3270,
          "lowestRentListingPrice": 45
        }
      },
      {
        "year": 2016,
        "month": 5,
        "values": {
          "medianSoldPrice": 835000,
          "numberSold": 13,
          "highestSoldPrice": 2126000,
          "lowestSoldPrice": 656000,
          "5thPercentileSoldPrice": 656000,
          "25thPercentileSoldPrice": 774000,
          "75thPercentileSoldPrice": 1032000,
          "95thPercentileSoldPrice": 2126000,
          "medianSaleListingPrice": 870000,
          "numberSaleListing": 26,
          "highestSaleListingPrice": 2200000,
          "lowestSaleListingPrice": 630000,
          "auctionNumberAuctioned": 3,
          "auctionNumberSold": 2,
          "auctionNumberWithdrawn": null,
          "daysOnMarket": null,
          "discountPercentage": null,
          "medianRentListingPrice": 480,
          "numberRentListing": 74,
          "highestRentListingPrice": 3270,
          "lowestRentListingPrice": 45
        }
      },
      {
        "year": 2016,
        "month": 8,
        "values": {
          "medianSoldPrice": 975000,
          "numberSold": 13,
          "highestSoldPrice": 5000000,
          "lowestSoldPrice": 535000,
          "5thPercentileSoldPrice": 535000,
          "25thPercentileSoldPrice": 855000,
          "75thPercentileSoldPrice": 1140000,
          "95thPercentileSoldPrice": 5000000,
          "medianSaleListingPrice": 955000,
          "numberSaleListing": 23,
          "highestSaleListingPrice": 3000000,
          "lowestSaleListingPrice": 545000,
          "auctionNumberAuctioned": 3,
          "auctionNumberSold": 2,
          "auctionNumberWithdrawn": null,
          "daysOnMarket": null,
          "discountPercentage": null,
          "medianRentListingPrice": 500,
          "numberRentListing": 71,
          "highestRentListingPrice": 1100,
          "lowestRentListingPrice": 270
        }
      },
      {
        "year": 2016,
        "month": 11,
        "values": {
          "medianSoldPrice": 930000,
          "numberSold": 13,
          "highestSoldPrice": 1370000,
          "lowestSoldPrice": 735000,
          "5thPercentileSoldPrice": 735000,
          "25thPercentileSoldPrice": 860000,
          "75thPercentileSoldPrice": 1101000,
          "95thPercentileSoldPrice": 1370000,
          "medianSaleListingPrice": 900000,
          "numberSaleListing": 29,
          "highestSaleListingPrice": 14998000,
          "lowestSaleListingPrice": 545000,
          "auctionNumberAuctioned": 9,
          "auctionNumberSold": 6,
          "auctionNumberWithdrawn": null,
          "daysOnMarket": 45,
          "discountPercentage": null,
          "medianRentListingPrice": 540,
          "numberRentListing": 65,
          "highestRentListingPrice": 800,
          "lowestRentListingPrice": 270
        }
      },
      {
        "year": 2017,
        "month": 2,
        "values": {
          "medianSoldPrice": 981000,
          "numberSold": 16,
          "highestSoldPrice": 2400000,
          "lowestSoldPrice": 680000,
          "5thPercentileSoldPrice": 680000,
          "25thPercentileSoldPrice": 820000,
          "75thPercentileSoldPrice": 1135000,
          "95thPercentileSoldPrice": 2400000,
          "medianSaleListingPrice": 1005000,
          "numberSaleListing": 21,
          "highestSaleListingPrice": 14998000,
          "lowestSaleListingPrice": 670000,
          "auctionNumberAuctioned": 7,
          "auctionNumberSold": 7,
          "auctionNumberWithdrawn": null,
          "daysOnMarket": null,
          "discountPercentage": null,
          "medianRentListingPrice": 510,
          "numberRentListing": 77,
          "highestRentListingPrice": 800,
          "lowestRentListingPrice": 165
        }
      },
      {
        "year": 2017,
        "month": 5,
        "values": {
          "medianSoldPrice": 1278000,
          "numberSold": 10,
          "highestSoldPrice": 2210000,
          "lowestSoldPrice": 700000,
          "5thPercentileSoldPrice": 700000,
          "25thPercentileSoldPrice": 896000,
          "75thPercentileSoldPrice": 1350000,
          "95thPercentileSoldPrice": 2210000,
          "medianSaleListingPrice": 965000,
          "numberSaleListing": 26,
          "highestSaleListingPrice": 1575000,
          "lowestSaleListingPrice": 600000,
          "auctionNumberAuctioned": 8,
          "auctionNumberSold": 7,
          "auctionNumberWithdrawn": null,
          "daysOnMarket": null,
          "discountPercentage": null,
          "medianRentListingPrice": 520,
          "numberRentListing": 85,
          "highestRentListingPrice": 750,
          "lowestRentListingPrice": 165
        }
      },
      {
        "year": 2017,
        "month": 8,
        "values": {
          "medianSoldPrice": 1145000,
          "numberSold": 16,
          "highestSoldPrice": 2000000,
          "lowestSoldPrice": 646000,
          "5thPercentileSoldPrice": 646000,
          "25thPercentileSoldPrice": 857000,
          "75thPercentileSoldPrice": 1590000,
          "95thPercentileSoldPrice": 2000000,
          "medianSaleListingPrice": 950000,
          "numberSaleListing": 22,
          "highestSaleListingPrice": 2000000,
          "lowestSaleListingPrice": 600000,
          "auctionNumberAuctioned": 5,
          "auctionNumberSold": 4,
          "auctionNumberWithdrawn": null,
          "daysOnMarket": null,
          "discountPercentage": null,
          "medianRentListingPrice": 540,
          "numberRentListing": 78,
          "highestRentListingPrice": 720,
          "lowestRentListingPrice": 170
        }
      },
      {
        "year": 2017,
        "month": 11,
        "values": {
          "medianSoldPrice": 950000,
          "numberSold": 18,
          "highestSoldPrice": 2130000,
          "lowestSoldPrice": 715000,
          "5thPercentileSoldPrice": 715000,
          "25thPercentileSoldPrice": 860000,
          "75thPercentileSoldPrice": 1300000,
          "95thPercentileSoldPrice": 2130000,
          "medianSaleListingPrice": 900000,
          "numberSaleListing": 31,
          "highestSaleListingPrice": 2000000,
          "lowestSaleListingPrice": 600000,
          "auctionNumberAuctioned": 19,
          "auctionNumberSold": 8,
          "auctionNumberWithdrawn": null,
          "daysOnMarket": 24,
          "discountPercentage": null,
          "medianRentListingPrice": 510,
          "numberRentListing": 93,
          "highestRentListingPrice": 795,
          "lowestRentListingPrice": 170
        }
      },
      {
        "year": 2018,
        "month": 2,
        "values": {
          "medianSoldPrice": null,
          "numberSold": 6,
          "highestSoldPrice": 1615000,
          "lowestSoldPrice": 650000,
          "5thPercentileSoldPrice": null,
          "25thPercentileSoldPrice": null,
          "75thPercentileSoldPrice": null,
          "95thPercentileSoldPrice": null,
          "medianSaleListingPrice": 954000,
          "numberSaleListing": 18,
          "highestSaleListingPrice": 2000000,
          "lowestSaleListingPrice": 635000,
          "auctionNumberAuctioned": 2,
          "auctionNumberSold": 1,
          "auctionNumberWithdrawn": null,
          "daysOnMarket": null,
          "discountPercentage": null,
          "medianRentListingPrice": 520,
          "numberRentListing": 106,
          "highestRentListingPrice": 850,
          "lowestRentListingPrice": 230
        }
      },
      {
        "year": 2018,
        "month": 5,
        "values": {
          "medianSoldPrice": null,
          "numberSold": 7,
          "highestSoldPrice": 5000000,
          "lowestSoldPrice": 595000,
          "5thPercentileSoldPrice": null,
          "25thPercentileSoldPrice": null,
          "75thPercentileSoldPrice": null,
          "95thPercentileSoldPrice": null,
          "medianSaleListingPrice": 850000,
          "numberSaleListing": 20,
          "highestSaleListingPrice": 2000000,
          "lowestSaleListingPrice": 195000,
          "auctionNumberAuctioned": 5,
          "auctionNumberSold": 2,
          "auctionNumberWithdrawn": null,
          "daysOnMarket": null,
          "discountPercentage": null,
          "medianRentListingPrice": 500,
          "numberRentListing": 84,
          "highestRentListingPrice": 800,
          "lowestRentListingPrice": 210
        }
      },
      {
        "year": 2018,
        "month": 8,
        "values": {
          "medianSoldPrice": null,
          "numberSold": 8,
          "highestSoldPrice": 1300000,
          "lowestSoldPrice": 540000,
          "5thPercentileSoldPrice": null,
          "25thPercentileSoldPrice": null,
          "75thPercentileSoldPrice": null,
          "95thPercentileSoldPrice": null,
          "medianSaleListingPrice": 850000,
          "numberSaleListing": 21,
          "highestSaleListingPrice": 2000000,
          "lowestSaleListingPrice": 100000,
          "auctionNumberAuctioned": 1,
          "auctionNumberSold": null,
          "auctionNumberWithdrawn": null,
          "daysOnMarket": null,
          "discountPercentage": null,
          "medianRentListingPrice": 500,
          "numberRentListing": 90,
          "highestRentListingPrice": 800,
          "lowestRentListingPrice": 210
        }
      },
      {
        "year": 2018,
        "month": 11,
        "values": {
          "medianSoldPrice": 915000,
          "numberSold": 17,
          "highestSoldPrice": 2600000,
          "lowestSoldPrice": 425000,
          "5thPercentileSoldPrice": 425000,
          "25thPercentileSoldPrice": 850000,
          "75thPercentileSoldPrice": 1150000,
          "95thPercentileSoldPrice": 2600000,
          "medianSaleListingPrice": 900000,
          "numberSaleListing": 36,
          "highestSaleListingPrice": 2000000,
          "lowestSaleListingPrice": 100000,
          "auctionNumberAuctioned": 9,
          "auctionNumberSold": 6,
          "auctionNumberWithdrawn": null,
          "daysOnMarket": null,
          "discountPercentage": null,
          "medianRentListingPrice": 515,
          "numberRentListing": 90,
          "highestRentListingPrice": 1000,
          "lowestRentListingPrice": 210
        }
      },
      {
        "year": 2019,
        "month": 2,
        "values": {
          "medianSoldPrice": null,
          "numberSold": 7,
          "highestSoldPrice": 1125000,
          "lowestSoldPrice": 385000,
          "5thPercentileSoldPrice": null,
          "25thPercentileSoldPrice": null,
          "75thPercentileSoldPrice": null,
          "95thPercentileSoldPrice": null,
          "medianSaleListingPrice": 970000,
          "numberSaleListing": 23,
          "highestSaleListingPrice": 2000000,
          "lowestSaleListingPrice": 100000,
          "auctionNumberAuctioned": 1,
          "auctionNumberSold": null,
          "auctionNumberWithdrawn": null,
          "daysOnMarket": null,
          "discountPercentage": null,
          "medianRentListingPrice": 510,
          "numberRentListing": 71,
          "highestRentListingPrice": 1000,
          "lowestRentListingPrice": 20
        }
      },
      {
        "year": 2019,
        "month": 5,
        "values": {
          "medianSoldPrice": 1000000,
          "numberSold": 15,
          "highestSoldPrice": 1200000,
          "lowestSoldPrice": 600000,
          "5thPercentileSoldPrice": 600000,
          "25thPercentileSoldPrice": 879000,
          "75thPercentileSoldPrice": 1121000,
          "95thPercentileSoldPrice": 1200000,
          "medianSaleListingPrice": 879000,
          "numberSaleListing": 28,
          "highestSaleListingPrice": 2000000,
          "lowestSaleListingPrice": 100000,
          "auctionNumberAuctioned": 8,
          "auctionNumberSold": 3,
          "auctionNumberWithdrawn": null,
          "daysOnMarket": null,
          "discountPercentage": null,
          "medianRentListingPrice": 510,
          "numberRentListing": 85,
          "highestRentListingPrice": 1000,
          "lowestRentListingPrice": 50
        }
      },
      {
        "year": 2019,
        "month": 8,
        "values": {
          "medianSoldPrice": 892000,
          "numberSold": 13,
          "highestSoldPrice": 1527000,
          "lowestSoldPrice": 685000,
          "5thPercentileSoldPrice": 685000,
          "25thPercentileSoldPrice": 738000,
          "75thPercentileSoldPrice": 1160000,
          "95thPercentileSoldPrice": 1527000,
          "medianSaleListingPrice": 812000,
          "numberSaleListing": 28,
          "highestSaleListingPrice": 1900000,
          "lowestSaleListingPrice": 100000,
          "auctionNumberAuctioned": 7,
          "auctionNumberSold": 5,
          "auctionNumberWithdrawn": null,
          "daysOnMarket": null,
          "discountPercentage": null,
          "medianRentListingPrice": 500,
          "numberRentListing": 98,
          "highestRentListingPrice": 1000,
          "lowestRentListingPrice": 50
        }
      },
      {
        "year": 2019,
        "month": 11,
        "values": {
          "medianSoldPrice": 1086000,
          "numberSold": 20,
          "highestSoldPrice": 1730000,
          "lowestSoldPrice": 575000,
          "5thPercentileSoldPrice": 575000,
          "25thPercentileSoldPrice": 808000,
          "75thPercentileSoldPrice": 1295000,
          "95thPercentileSoldPrice": 1670000,
          "medianSaleListingPrice": 850000,
          "numberSaleListing": 36,
          "highestSaleListingPrice": 1900000,
          "lowestSaleListingPrice": 100000,
          "auctionNumberAuctioned": 11,
          "auctionNumberSold": 6,
          "auctionNumberWithdrawn": null,
          "daysOnMarket": 82,
          "discountPercentage": null,
          "medianRentListingPrice": 500,
          "numberRentListing": 93,
          "highestRentListingPrice": null,
          "lowestRentListingPrice": 50
        }
      },
      {
        "year": 2020,
        "month": 2,
        "values": {
          "medianSoldPrice": 1295000,
          "numberSold": 11,
          "highestSoldPrice": 3950000,
          "lowestSoldPrice": 710000,
          "5thPercentileSoldPrice": 710000,
          "25thPercentileSoldPrice": 991000,
          "75thPercentileSoldPrice": 1750000,
          "95thPercentileSoldPrice": 3950000,
          "medianSaleListingPrice": 865000,
          "numberSaleListing": 31,
          "highestSaleListingPrice": 1900000,
          "lowestSaleListingPrice": 100000,
          "auctionNumberAuctioned": 7,
          "auctionNumberSold": 5,
          "auctionNumberWithdrawn": null,
          "daysOnMarket": null,
          "discountPercentage": null,
          "medianRentListingPrice": 510,
          "numberRentListing": 109,
          "highestRentListingPrice": 1000,
          "lowestRentListingPrice": 40
        }
      },
      {
        "year": 2020,
        "month": 5,
        "values": {
          "medianSoldPrice": 1067000,
          "numberSold": 10,
          "highestSoldPrice": 1516000,
          "lowestSoldPrice": 615000,
          "5thPercentileSoldPrice": 615000,
          "25thPercentileSoldPrice": 867000,
          "75thPercentileSoldPrice": 1155000,
          "95thPercentileSoldPrice": 1516000,
          "medianSaleListingPrice": 875000,
          "numberSaleListing": 35,
          "highestSaleListingPrice": 1900000,
          "lowestSaleListingPrice": 100000,
          "auctionNumberAuctioned": 7,
          "auctionNumberSold": 2,
          "auctionNumberWithdrawn": null,
          "daysOnMarket": null,
          "discountPercentage": null,
          "medianRentListingPrice": 499,
          "numberRentListing": 109,
          "highestRentListingPrice": 1000,
          "lowestRentListingPrice": 30
        }
      },
      {
        "year": 2020,
        "month": 8,
        "values": {
          "medianSoldPrice": null,
          "numberSold": 7,
          "highestSoldPrice": 1260000,
          "lowestSoldPrice": 510000,
          "5thPercentileSoldPrice": null,
          "25thPercentileSoldPrice": null,
          "75thPercentileSoldPrice": null,
          "95thPercentileSoldPrice": null,
          "medianSaleListingPrice": 886000,
          "numberSaleListing": 20,
          "highestSaleListingPrice": 1388000,
          "lowestSaleListingPrice": 405000,
          "auctionNumberAuctioned": 1,
          "auctionNumberSold": null,
          "auctionNumberWithdrawn": null,
          "daysOnMarket": null,
          "discountPercentage": null,
          "medianRentListingPrice": 495,
          "numberRentListing": 77,
          "highestRentListingPrice": 770,
          "lowestRentListingPrice": 30
        }
      },
      {
        "year": 2020,
        "month": 11,
        "values": {
          "medianSoldPrice": 750000,
          "numberSold": 11,
          "highestSoldPrice": 1650000,
          "lowestSoldPrice": 630000,
          "5thPercentileSoldPrice": 630000,
          "25thPercentileSoldPrice": 682000,
          "75thPercentileSoldPrice": 1085000,
          "95thPercentileSoldPrice": 1650000,
          "medianSaleListingPrice": 925000,
          "numberSaleListing": 26,
          "highestSaleListingPrice": 5000000,
          "lowestSaleListingPrice": 405000,
          "auctionNumberAuctioned": 6,
          "auctionNumberSold": 3,
          "auctionNumberWithdrawn": null,
          "daysOnMarket": null,
          "discountPercentage": null,
          "medianRentListingPrice": 495,
          "numberRentListing": 86,
          "highestRentListingPrice": 825,
          "lowestRentListingPrice": 30
        }
      },
      {
        "year": 2021,
        "month": 2,
        "values": {
          "medianSoldPrice": 838000,
          "numberSold": 17,
          "highestSoldPrice": 1860000,
          "lowestSoldPrice": null,
          "5thPercentileSoldPrice": null,
          "25thPercentileSoldPrice": 695000,
          "75thPercentileSoldPrice": 1105000,
          "95thPercentileSoldPrice": 1860000,
          "medianSaleListingPrice": 1125000,
          "numberSaleListing": 27,
          "highestSaleListingPrice": 5000000,
          "lowestSaleListingPrice": 400000,
          "auctionNumberAuctioned": 7,
          "auctionNumberSold": 2,
          "auctionNumberWithdrawn": null,
          "daysOnMarket": 95,
          "discountPercentage": null,
          "medianRentListingPrice": 480,
          "numberRentListing": 99,
          "highestRentListingPrice": 900,
          "lowestRentListingPrice": 300
        }
      },
      {
        "year": 2021,
        "month": 5,
        "values": {
          "medianSoldPrice": 1100000,
          "numberSold": 20,
          "highestSoldPrice": 1950000,
          "lowestSoldPrice": null,
          "5thPercentileSoldPrice": null,
          "25thPercentileSoldPrice": 890000,
          "75thPercentileSoldPrice": 1370000,
          "95thPercentileSoldPrice": 1900000,
          "medianSaleListingPrice": 890000,
          "numberSaleListing": 31,
          "highestSaleListingPrice": 5000000,
          "lowestSaleListingPrice": 400000,
          "auctionNumberAuctioned": 18,
          "auctionNumberSold": 14,
          "auctionNumberWithdrawn": null,
          "daysOnMarket": null,
          "discountPercentage": null,
          "medianRentListingPrice": 470,
          "numberRentListing": 107,
          "highestRentListingPrice": 900,
          "lowestRentListingPrice": 30
        }
      },
      {
        "year": 2021,
        "month": 8,
        "values": {
          "medianSoldPrice": 1280000,
          "numberSold": 16,
          "highestSoldPrice": 2400000,
          "lowestSoldPrice": null,
          "5thPercentileSoldPrice": null,
          "25thPercentileSoldPrice": 745000,
          "75thPercentileSoldPrice": 1500000,
          "95thPercentileSoldPrice": 2400000,
          "medianSaleListingPrice": 1200000,
          "numberSaleListing": 31,
          "highestSaleListingPrice": 5000000,
          "lowestSaleListingPrice": 430000,
          "auctionNumberAuctioned": 14,
          "auctionNumberSold": 7,
          "auctionNumberWithdrawn": null,
          "daysOnMarket": null,
          "discountPercentage": null,
          "medianRentListingPrice": 480,
          "numberRentListing": 89,
          "highestRentListingPrice": 685,
          "lowestRentListingPrice": 3
        }
      },
      {
        "year": 2021,
        "month": 11,
        "values": {
          "medianSoldPrice": 993000,
          "numberSold": 22,
          "highestSoldPrice": 3250000,
          "lowestSoldPrice": null,
          "5thPercentileSoldPrice": 635000,
          "25thPercentileSoldPrice": 950000,
          "75thPercentileSoldPrice": 1360000,
          "95thPercentileSoldPrice": 1620000,
          "medianSaleListingPrice": 920000,
          "numberSaleListing": 31,
          "highestSaleListingPrice": 5000000,
          "lowestSaleListingPrice": 415000,
          "auctionNumberAuctioned": 14,
          "auctionNumberSold": 10,
          "auctionNumberWithdrawn": null,
          "daysOnMarket": null,
          "discountPercentage": null,
          "medianRentListingPrice": 495,
          "numberRentListing": 82,
          "highestRentListingPrice": 780,
          "lowestRentListingPrice": 10
        }
      },
      {
        "year": 2022,
        "month": 2,
        "values": {
          "medianSoldPrice": 1375000,
          "numberSold": 13,
          "highestSoldPrice": 2621000,
          "lowestSoldPrice": 545000,
          "5thPercentileSoldPrice": 545000,
          "25thPercentileSoldPrice": 860000,
          "75thPercentileSoldPrice": 1728000,
          "95thPercentileSoldPrice": 2621000,
          "medianSaleListingPrice": 1275000,
          "numberSaleListing": 23,
          "highestSaleListingPrice": 5000000,
          "lowestSaleListingPrice": 440000,
          "auctionNumberAuctioned": 7,
          "auctionNumberSold": 6,
          "auctionNumberWithdrawn": null,
          "daysOnMarket": null,
          "discountPercentage": null,
          "medianRentListingPrice": 500,
          "numberRentListing": 74,
          "highestRentListingPrice": 830,
          "lowestRentListingPrice": 50
        }
      },
      {
        "year": 2022,
        "month": 5,
        "values": {
          "medianSoldPrice": 1383000,
          "numberSold": 11,
          "highestSoldPrice": 2010000,
          "lowestSoldPrice": 701000,
          "5thPercentileSoldPrice": 701000,
          "25thPercentileSoldPrice": 720000,
          "75thPercentileSoldPrice": 1506000,
          "95thPercentileSoldPrice": 2010000,
          "medianSaleListingPrice": 1320000,
          "numberSaleListing": 26,
          "highestSaleListingPrice": 2080000,
          "lowestSaleListingPrice": 440000,
          "auctionNumberAuctioned": 12,
          "auctionNumberSold": 5,
          "auctionNumberWithdrawn": null,
          "daysOnMarket": null,
          "discountPercentage": null,
          "medianRentListingPrice": 498,
          "numberRentListing": 72,
          "highestRentListingPrice": 850,
          "lowestRentListingPrice": 50
        }
      },
      {
        "year": 2022,
        "month": 8,
        "values": {
          "medianSoldPrice": null,
          "numberSold": 8,
          "highestSoldPrice": 3150000,
          "lowestSoldPrice": 800000,
          "5thPercentileSoldPrice": null,
          "25thPercentileSoldPrice": null,
          "75thPercentileSoldPrice": null,
          "95thPercentileSoldPrice": null,
          "medianSaleListingPrice": 949000,
          "numberSaleListing": 23,
          "highestSaleListingPrice": 1900000,
          "lowestSaleListingPrice": 440000,
          "auctionNumberAuctioned": 8,
          "auctionNumberSold": 3,
          "auctionNumberWithdrawn": null,
          "daysOnMarket": null,
          "discountPercentage": null,
          "medianRentListingPrice": 500,
          "numberRentListing": 65,
          "highestRentListingPrice": 900,
          "lowestRentListingPrice": 50
        }
      },
      {
        "year": 2022,
        "month": 11,
        "values": {
          "medianSoldPrice": 1198000,
          "numberSold": 17,
          "highestSoldPrice": 1894000,
          "lowestSoldPrice": 810000,
          "5thPercentileSoldPrice": 810000,
          "25thPercentileSoldPrice": 930000,
          "75thPercentileSoldPrice": 1380000,
          "95thPercentileSoldPrice": 1894000,
          "medianSaleListingPrice": 1300000,
          "numberSaleListing": 28,
          "highestSaleListingPrice": 2200000,
          "lowestSaleListingPrice": 425000,
          "auctionNumberAuctioned": 10,
          "auctionNumberSold": 6,
          "auctionNumberWithdrawn": null,
          "daysOnMarket": 60,
          "discountPercentage": null,
          "medianRentListingPrice": 545,
          "numberRentListing": 60,
          "highestRentListingPrice": 1200,
          "lowestRentListingPrice": 45
        }
      },
      {
        "year": 2023,
        "month": 2,
        "values": {
          "medianSoldPrice": null,
          "numberSold": 7,
          "highestSoldPrice": 1720000,
          "lowestSoldPrice": 1220000,
          "5thPercentileSoldPrice": null,
          "25thPercentileSoldPrice": null,
          "75thPercentileSoldPrice": null,
          "95thPercentileSoldPrice": null,
          "medianSaleListingPrice": 1325000,
          "numberSaleListing": 18,
          "highestSaleListingPrice": 2200000,
          "lowestSaleListingPrice": 425000,
          "auctionNumberAuctioned": 3,
          "auctionNumberSold": null,
          "auctionNumberWithdrawn": null,
          "daysOnMarket": null,
          "discountPercentage": null,
          "medianRentListingPrice": 550,
          "numberRentListing": 59,
          "highestRentListingPrice": 850,
          "lowestRentListingPrice": 45
        }
      },
      {
        "year": 2023,
        "month": 5,
        "values": {
          "medianSoldPrice": 943000,
          "numberSold": 14,
          "highestSoldPrice": 1950000,
          "lowestSoldPrice": 410000,
          "5thPercentileSoldPrice": 410000,
          "25thPercentileSoldPrice": 860000,
          "75thPercentileSoldPrice": 1426000,
          "95thPercentileSoldPrice": 1950000,
          "medianSaleListingPrice": 955000,
          "numberSaleListing": 21,
          "highestSaleListingPrice": 1900000,
          "lowestSaleListingPrice": 110000,
          "auctionNumberAuctioned": 6,
          "auctionNumberSold": 3,
          "auctionNumberWithdrawn": null,
          "daysOnMarket": 58,
          "discountPercentage": null,
          "medianRentListingPrice": 600,
          "numberRentListing": 63,
          "highestRentListingPrice": 1000,
          "lowestRentListingPrice": 45
        }
      },
      {
        "year": 2023,
        "month": 8,
        "values": {
          "medianSoldPrice": 1391000,
          "numberSold": 19,
          "highestSoldPrice": 4850000,
          "lowestSoldPrice": 820000,
          "5thPercentileSoldPrice": 820000,
          "25thPercentileSoldPrice": 1160000,
          "75thPercentileSoldPrice": 1801000,
          "95thPercentileSoldPrice": 4850000,
          "medianSaleListingPrice": 1180000,
          "numberSaleListing": 25,
          "highestSaleListingPrice": 1900000,
          "lowestSaleListingPrice": 608000,
          "auctionNumberAuctioned": 8,
          "auctionNumberSold": 7,
          "auctionNumberWithdrawn": null,
          "daysOnMarket": null,
          "discountPercentage": null,
          "medianRentListingPrice": 660,
          "numberRentListing": 51,
          "highestRentListingPrice": 1150,
          "lowestRentListingPrice": 45
        }
      },
      {
        "year": 2023,
        "month": 11,
        "values": {
          "medianSoldPrice": 1490000,
          "numberSold": 14,
          "highestSoldPrice": 2270000,
          "lowestSoldPrice": 773000,
          "5thPercentileSoldPrice": 773000,
          "25thPercentileSoldPrice": 1030000,
          "75thPercentileSoldPrice": 1825000,
          "95thPercentileSoldPrice": 2270000,
          "medianSaleListingPrice": 1250000,
          "numberSaleListing": 23,
          "highestSaleListingPrice": 2200000,
          "lowestSaleListingPrice": 608000,
          "auctionNumberAuctioned": 14,
          "auctionNumberSold": 7,
          "auctionNumberWithdrawn": null,
          "daysOnMarket": null,
          "discountPercentage": null,
          "medianRentListingPrice": 693,
          "numberRentListing": 48,
          "highestRentListingPrice": 1250,
          "lowestRentListingPrice": 50
        }
      },
      {
        "year": 2024,
        "month": 2,
        "values": {
          "medianSoldPrice": 1275000,
          "numberSold": 16,
          "highestSoldPrice": 1985000,
          "lowestSoldPrice": 765000,
          "5thPercentileSoldPrice": 765000,
          "25thPercentileSoldPrice": 950000,
          "75thPercentileSoldPrice": 1460000,
          "95thPercentileSoldPrice": 1985000,
          "medianSaleListingPrice": 1080000,
          "numberSaleListing": 18,
          "highestSaleListingPrice": 1600000,
          "lowestSaleListingPrice": 608000,
          "auctionNumberAuctioned": 8,
          "auctionNumberSold": 7,
          "auctionNumberWithdrawn": null,
          "daysOnMarket": null,
          "discountPercentage": null,
          "medianRentListingPrice": 650,
          "numberRentListing": 58,
          "highestRentListingPrice": 2400,
          "lowestRentListingPrice": 50
        }
      },
      {
        "year": 2024,
        "month": 5,
        "values": {
          "medianSoldPrice": 1170000,
          "numberSold": 16,
          "highestSoldPrice": 4000000,
          "lowestSoldPrice": 735000,
          "5thPercentileSoldPrice": 735000,
          "25thPercentileSoldPrice": 870000,
          "75thPercentileSoldPrice": 1700000,
          "95thPercentileSoldPrice": 4000000,
          "medianSaleListingPrice": 1090000,
          "numberSaleListing": 22,
          "highestSaleListingPrice": 1800000,
          "lowestSaleListingPrice": 608000,
          "auctionNumberAuctioned": 12,
          "auctionNumberSold": 7,
          "auctionNumberWithdrawn": null,
          "daysOnMarket": null,
          "discountPercentage": null,
          "medianRentListingPrice": 700,
          "numberRentListing": 52,
          "highestRentListingPrice": 2400,
          "lowestRentListingPrice": 500
        }
      },
      {
        "year": 2024,
        "month": 8,
        "values": {
          "medianSoldPrice": 1676000,
          "numberSold": 16,
          "highestSoldPrice": 3502000,
          "lowestSoldPrice": 745000,
          "5thPercentileSoldPrice": 745000,
          "25thPercentileSoldPrice": 1079000,
          "75thPercentileSoldPrice": 2175000,
          "95thPercentileSoldPrice": 3502000,
          "medianSaleListingPrice": 1250000,
          "numberSaleListing": 28,
          "highestSaleListingPrice": 2200000,
          "lowestSaleListingPrice": 608000,
          "auctionNumberAuctioned": 11,
          "auctionNumberSold": 8,
          "auctionNumberWithdrawn": null,
          "daysOnMarket": 23,
          "discountPercentage": null,
          "medianRentListingPrice": 723,
          "numberRentListing": 60,
          "highestRentListingPrice": 2400,
          "lowestRentListingPrice": 350
        }
      }
    ]
  }
}';
$extracted_suburb_performance_statistics = rc_domain_extract_suburb_performance_statistics($fetched_suburb_performance_statistics);

echo '<pre>';
print_r($fetched_property);
echo '</pre>';