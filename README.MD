# Instant Digital Appraisal Plugin

Create Digital Appraisals for users, generate suburb reports using data from Pricefinder.

## You'll need:

* A Google Maps API Key with a billable account set up
* Pricefinder API subscription
* Gravityforms with Gravityforms Conversational forms addon (recommended)
* Advanced Custom Fields PRO

## Installation

#### 1. Install the plugin with Composer
```
composer require real-coder-pty-ltd/instant-digital-appraisal`
```

#### 2. Add Credentials
Navigate to Tools -> Pricefinder DA Settings. Add Client ID, Secret, and Google Maps API key. Note: Ensure your Google Maps API key is set to use the Places API.

#### 3. Create Lead Capture Form
Create a Gravityform that has a field called that can be dynamically populated. The value should be "address".

#### 4. Shortcode 
Add the shortcode for the autocomplete to your page
```
[pfda_address_form]
```
#### 5. Set your Appraisal URL.
Navigate to the public class:
```
/wp-content/plugins/pricefinder-da/public/class-pricefinder-da-public.php
```
Update line `368` to the *URL you put your Gravityform on*. The default option is `/gravity-forms-appraisal/`. If your page is called Digital Appraisal, the slug is probably "/digital-appraisal", etc.

#### 6. Data Processing URL
For the Gravityform confirmation, point it to the processing file. 
- In your form settings, click on `confirmations`, edit default confirmation.
- change the type to "Redirect".
- Use the URL `http://{yourwebsitehere.com.au}/{content-dir}/plugins/instant-digital-appraisal/public/pricefinder-da-processor.php`.
- Add Address from address field to query string. EG `address={Appraisal Address:4}`.

#### 7. Configure Advanced Custom Fields
This plugin does not register them for you yet. You'll need to do this yourself.

##### Appraisal Fields Required
- `unique_id` -> text
- `suggest_result` -> textarea
- `demographics_result` -> textarea
- `listings_result` -> textarea
- `sales_result` -> textarea
- `rentals_result` -> textarea
- `property_result` -> textarea
- `property_details` -> textarea
- `rental_sales_history` -> textarea
- `avm_result` -> textarea
- `pricing_information` -> textarea
- `suburb_response` -> textarea
- `suburb_statistics` -> textarea
- `investment_potential` -> textarea
- `schools_result` -> textarea
- `data_dump` -> textarea
- `schools` -> textarea

#### 8. Summary
The flow goes like this:

- Add shortcode to any page
- Shortcode submits to your Gravityform to capture lead data
- On submit, data is sent to the `pricefinder-da-processor.php` file
- data is saved in ACF Pro fields on the `appraisal` post type