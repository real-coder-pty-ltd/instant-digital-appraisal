# Instant Digital Appraisal Plugin

Create Suburb reports from the Pricefinder API.

## You'll need:

* A Google Maps API Key with a billable account set up
* Domain API subscription

## Installation

#### 1. Install the plugin with Composer
```
composer require real-coder-pty-ltd/instant-digital-appraisal`
```

#### 2. Run the WP CLI Commands
you can now run wp update-locations which will get the suburb and state for taxonomy term locations.. 
```
wp update-locations
```
```
wp create-suburb-profiles
```

Optional for testing: run `wp delete-suburb-profiles` will delete all existing suburb profiles (recommended)

#### 2. Add Credentials
Navigate to Tools in the admin panel. Add your API key, and Google Maps API key. Note: Ensure your Google Maps API key is set to use the Places API.

