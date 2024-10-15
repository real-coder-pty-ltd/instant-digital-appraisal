=== Plugin Name ===
Contributors: mattyjneal
Tags: real estate, api, appraisal, custom post, pricefinder, Digital Aappraisal, gravityforms
Requires at least: 4.9
Tested up to: 6.0
Stable tag: 1.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Create Digital Appraisals for users, generate suburb reports using data from Pricefinder.

== Description ==

You'll need:

* A Google Maps API Key with a billable account set up
* Pricefinder API subscription
* Gravityforms with Gravityforms Conversational forms addon (recommended)

== Installation ==

1. Upload the plugin or install it with `composer require real-coder-pty-ltd/instant-digital-appraisal` and activate.
2. Create a form in Gravityforms that has a field called "Address", which can be dynamically populated.
3. In the WP Admin, go to Tools -> Pricefinder DA Settings and add in your Pricefinder Client ID, Secret, and your Google Maps API key. Ensure your google maps API key is set to use the places API.
4. Add the shortcode for the autocomplete to your page: [pfda_address_form]
5. Because this plugin isn't ready yet, you need to edit your /wp-content/plugins/pricefinder-da/public/class-pricefinder-da-public.php file on line 368 to the URL you put your gravity form on. IF you set your form to be on a page with the slug "/gravity-forms-appraisal/", you can leave it. If your page is called Digital Appraisal, the slug is probably "/digital-appraisal", etc.
6. For the Gravityform confirmation, point it to the Processing file. In your form, click on confirmations, edit default confirmation, change the type to "Redirect", and enter: "http://yourwebsitehere.com/wp-content/plugins/pricefinder-da/public/pricefinder-da-processor.php" without the quotes.
7. Make sure to enable the query string. My address field is called "Appraisal Address", so I need to add the following query string: "address={Appraisal Address:4}". the "address=" must be manually added, but the drop down to the right will fill in the variable to match what you added for the name.

== Frequently Asked Questions ==

= Is this free? =

Yes, but you need a Pricefinder API subscription and a Google Maps API key.

== Screenshots ==

1. This screen shot description corresponds to screenshot-1.(png|jpg|jpeg|gif). Note that the screenshot is taken from
the /assets directory or the directory that contains the stable readme.txt (tags or trunk). Screenshots in the /assets
directory take precedence. For example, `/assets/screenshot-1.png` would win over `/tags/4.3/screenshot-1.png`
(or jpg, jpeg, gif).
2. This is the second screen shot

== Changelog ==

= 1.0 =
* First release. Not sure this should be labelled 1.0, because it's still very much experimental and needs some code refactoring.

== More details ==

Contact matt.neal@realcoder.com.au if you need pricing or more info.