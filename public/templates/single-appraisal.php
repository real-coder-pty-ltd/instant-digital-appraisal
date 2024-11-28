<?php
/**
 * The template for displaying a single appraisal.
 *
 * @package understrap
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

$no_data = '<span style="line-height: 61.5px;">No data currently available.</span>';

// Property fields
$property_details = get_field('field_5f90e9ac60f5f');

if(!empty($property_details)) {
	foreach ( $property_details as $key => $value ) {
		if ( empty($value)) {
			$property_details[$key] = false;
		}
	}
}

$pricing_information = get_field('field_5f90eb5280d7f');
foreach ( $pricing_information as $key => $value ) {
	if($key == 'price_confidence') continue;
	if ( empty($value)) {
		$pricing_information[$key] = $no_data;
	} else {
		$pricing_information[$key] = '$'.number_format(floatval($pricing_information[$key]));
	}
}

$suburb_statistics 		= get_field('field_5f90ebdc22621');
$investment_potential 	= get_field('field_5f90f14fc43c5');
$rental_sales_history 	= get_field('field_5f90f1e93973a');
if(empty($rental_sales_history)) { $rental_sales_history  = $no_data;}
$schools 				= get_field('field_5f90f2fe50d42');
$unique_id 				= get_field('field_5f90fba8a55d1');
$map 					= get_field('field_5f964ca49e086');
$map 					= '/wp-content/uploads/digital-appraisal/'.$map.'.jpg';
$search					= json_decode(get_field('suggest_result'));
$demographics			= json_decode(get_field('demographics_result'));
$property_result		= json_decode(get_field('property_result'));
$listings_result		= json_decode(get_field('listings_result'));
$sales_result			= json_decode(get_field('sales_result'));
$rentals_result			= json_decode(get_field('rentals_result'));
$avm					= json_decode(get_field('avm_result'));
$suburb					= json_decode(get_field('suburb_response'));
$schools_result			= json_decode(get_field('schools_result'));
$formatted_title = '<h1 class="entry-title" style="font-size: 51px;">'.$property_result->address->streetAddress.' <span class="suburb-details">'.$property_result->address->locality.', '.$property_result->address->state.' '.$property_result->address->postcode.'</span></h1>';

add_action('wp_head', 'pricefinder_da_google');
function pricefinder_da_google(){
	echo '	<script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
			<script src="https://maps.googleapis.com/maps/api/js?key='.get_option('rc_ida_google_maps_api_key').'&callback=initMap&libraries=&v=weekly" defer></script>';
	};
	echo '<script>
	let map;

	function initMap() {
		const myLatLng = { lat: '.$search->property->location->lat.', lng: '.$search->property->location->lon.' };
		const map = new google.maps.Map(document.getElementById("map"), {
		  zoom: 15,
		  center: myLatLng,
		});
		new google.maps.Marker({
		  position: myLatLng,
		  map,
		  title: "Property Pin",
		});
	  }
  </script>';

get_header();


?>

<div class="wrapper" id="single-wrapper"> <!-- Full page content wrapper -->

	<div class="banner-wrapper"><!-- Banner Section Start -->

		<div class="container-fluid" id="appraisal-content" tabindex="-1">

			<div class="row mb-90">

			<?php while ( have_posts() ) : the_post(); ?>

				<div id="appraisal-summary" class="col-md-5 flex-column">

					<div class="appraisal-inner">

						<header class="entry-header">
							<label>Property Report For</label>

							<?php echo $formatted_title; ?>
							<?php echo pricefinder_da_icons_constructor($property_details); ?>
						</header><!-- .entry-header -->

						<?php if(!$avm->error) : ?>

							<div class="price-range">
								<h3>Estimated Price Range</h3>
								<label><?php echo $pricing_information['price_confidence']; ?> Confidence</label>
							</div>

							<div class="pricing-confidence">
								<div><label>LOW</label><?php echo rc_ida_nice_number($avm->salesAVM->priceRangeMinimum); ?></div>
								<div class="mid-range"><label>MID</label><?php echo rc_ida_nice_number($avm->salesAVM->price); ?></div>
								<div><label>HIGH</label><?php echo rc_ida_nice_number($avm->salesAVM->priceRangeMaximum); ?></div>
							</div>
					
							<a href="/contact-us" class="btn btn-primary">Get a Personalised Appraisal Now</a>

						<?php else : ?>		

							<div class="no-confidence mb-5 text-center"><?php echo $pricing_information['price_confidence']; ?></div>
							<a href="/contact-us" class="btn btn-primary">Get a Personalised Appraisal Now</a>

						<?php endif; ?>

					</div>

				</div>

				<div class="col-md-7 hero-image">
					<?php echo get_the_post_thumbnail( $post->ID, 'full' ); ?>
				</div>

			<?php endwhile; ?>

			</div><!-- .row -->

		</div>

	</div><!-- Banner section end -->

	<div class="container">
	
			<div class="row mb-90">

			<?php if($suburb->house->saleCount) : ?>

				<div class="col-md-6">
					<div class="featured-box">
						<div class="feature-container">
							<?php echo '<img class="feature" src="'.plugin_dir_url( __FILE__ ).'icons/properties-sold.png" />'; ?>
							<label>Properties Sold in the Last 12 Months</label>
						</div>
						<p><?php echo $suburb->house->saleCount; ?></p>
					</div>
				</div>

			<?php endif; ?>

			<?php if($suburb->unit->saleCount) : ?>

				<div class="col-md-6">

					<div class="featured-box">

					<div class="feature-container">

						<?php echo '<img class="feature" src="'.plugin_dir_url( __FILE__ ).'icons/units-sold.png" />'; ?>

						<label>Units Sold in the Last 12 Months</label>

					</div>

					<p><?php echo $suburb->unit->saleCount; ?></p>

				</div>

			</div>

			<?php endif; ?>

			</div>

			<div class="row">

				<div class="col-12">

					<h2 class="text-center"> Investment Potential - Last 12 Months</h2>

				</div>

			</div>

		<div id="investment-potential" class="row mb-90">

			<?php if($suburb->house->medianRentalPrice) : ?>
			<div class="col">
				<h5><?php echo '$'.$suburb->house->medianRentalPrice.'<span>/WEEK</span>'; ?></h5>
				<label>Average rent for houses nearby</label>	
			</div>
			<?php endif; ?>

			<?php if($suburb->house->suburbRentalYield) : ?>
			<div class="col">
				<h5><?php echo round($suburb->house->suburbRentalYield * 100, 2).'%'; ?></h5>
				<label>Average annual rental yield for houses nearby</label>
			</div>
			<?php endif; ?>

			<?php if($suburb->house->suburbGrowth) : ?>
			<div class="col">
				<h5><?php echo round($suburb->house->suburbGrowth * 100, 2).'%'; ?></h5>
				<label>Average capital growth for houses nearby</label>
			</div>
			<?php endif; ?>


			<div class="col">
				<h5>
					<?php if(!empty($investment_potential['days_on_market_rental'])) {
							echo $investment_potential['days_on_market_rental'];
						} else {
							echo $no_data;
						} ?>
				</h5>

				<label>Average days on market for rentals</label>
			</div>

			<div class="col">
			<h5>
					<?php if(!empty($investment_potential['days_on_market_sale'])) {
							echo $investment_potential['days_on_market_sale'];
						} else {
							echo $no_data;
						} ?>
				</h5>

				<label>Average days on market for sale</label>
			</div>
		</div>
		
		<?php echo da_pricefinder_sales_history($rental_sales_history); ?>

		<div class="property-stats">

			<div class="row">
				<div class="col">
					<h2 class="text-center mb-5">Suburb Statistics</h2>
				</div>
			</div>

			<div class="row">
				<div class="col property-map mb-40">
			
					<div id="map"></div>
				</div>
			</div>
						
			<div class="row mb-90 stats-row">
				<div class="col col-md-3">
					<h5><?php echo $suburb_statistics['population'];?></h5>
					<label id="population">Population</label>
				</div>
				<div class="col col-md-3">
					<h5><?php echo $suburb_statistics['average_age'];?></h5>
					<label id="demographic">Main Demographic</label>
				</div>
				<div class="col col-md-3">
					<h5><?php echo $suburb_statistics['owner_occupier'];?>  %</h5>
					<label id="owner">Owner Occupier</label>
				</div>
				<div class="col col-md-3">
					<h5><?php echo $suburb_statistics['properties_for_sale'];?> </h5>
					<label id="on-market">Houses on Market</label>
				</div>
			</div>

		</div>

		<div class="row">
			<div class="col">
				<h2 class="text-center mb-5">Local Schools</h2>
			</div>
		</div>

		<div class="row mb-90">
			<div class="col">
				<table id="schools-table" class="table table-striped">
				  	<tbody>
					<?php foreach ($schools_result->schools as $school):
						$dd = GetDrivingDistance($search->property->location->lat, $search->property->location->lon, $school->location->lat, $school->location->lon);?>
						<tr>
							<td><?php echo $school->name; ?></td>
							<!-- <td><?php echo geolocationaddress($school->location->lat, $school->location->lon); ?></td> -->
							<td><?php echo $school->type; ?></td>
							<td><?php echo $dd['distance']; ?></td>
							<td><?php echo $dd['time']; ?> by car</td>
						</tr>
					<?php endforeach; ?>
				  	</tbody>	
				</table>
			</div>
		</div>

	</div>

<div class="wrap">
	<div id="contact-wrap" class="container pt-5 pb-5">
		<div class="row">
			<div class="col-md-6">
				<h2>Want a personalised, in depth appraisal on your property?</h2>
				<p>If you would like a detailed report that takes into consideration unique attributes of your property and the current market conditions, get in touch today.</p>
			</div>
			<div class="col-md-6">
				<?php echo do_shortcode('[gravityform id="21" title="false" description="false"]'); ?>
			</div>
		</div>
	</div>
</div>

	</div><!-- #content -->

</div><!-- #single-wrapper -->

<?php get_footer(); ?>

<style type="text/css"> #map { height: 450px; } </style>

<?php









