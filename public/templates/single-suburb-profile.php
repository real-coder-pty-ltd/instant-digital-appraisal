<?php
/**
 * The template for displaying a single appraisal.
 */

// Exit if accessed directly.
defined('ABSPATH') || exit;

$post_id = get_the_ID();
$featured_image = get_the_post_thumbnail_url(get_the_ID(), 'full', ['class' => 'w-100', 'style' => 'max-height: 650px; object-fit: cover;']);

// Fallback image
$plugin_dir = plugin_dir_url(dirname(__FILE__));
$image_dir =  $plugin_dir . 'images/';
$banner_img_url = $featured_image ? '<img class="w-100 object-fit-cover" style="max-height: 650px;" src="' . $featured_image . '">' : '<img class="w-100 object-fit-cover" style="max-height: 650px;" src="' . $image_dir . 'background.jpg">';

$thumnail_url = get_the_post_thumbnail_url(get_the_ID(), 'full');
if ( ! $thumnail_url ) {
    $thumnail_url = $image_dir . 'classic.webp';
}

$suburb = get_post_meta(get_the_ID(), 'rc_suburb', true);
$state = get_post_meta(get_the_ID(), 'rc_state', true);
$postcode = get_post_meta(get_the_ID(), 'rc_postcode', true);

$lat = get_post_meta(get_the_ID(), 'rc_lat', true);
$long = get_post_meta(get_the_ID(), 'rc_long', true);

if ( ! $lat || ! $long ) {
    $boundary_fetcher = new Boundary_Fetcher($suburb, $state, 'Australia', get_the_ID());
    $lat = $boundary_fetcher->getLat();
    $long = $boundary_fetcher->getLong();
}

$boundary = get_post_meta(get_the_ID(), 'rc_boundary', true);
$center = get_post_meta(get_the_ID(), 'rc_center', true);

$suburb_label = ucwords(strtolower($suburb));

$suburb_id = dsp_domain_get_suburb_id($suburb, $state, $postcode);
$location_profile = dsp_domain_get_location_profile($suburb_id);

$population = number_format($location_profile['data']['population']) ?? 0;
$average_age = $location_profile['data']['mostCommonAgeBracket'] ?? 0;
$renter_percentage = round(($location_profile['data']['renterPercentage'] ?? 0) * 100);
$owner_percentage = round(($location_profile['data']['ownerOccupierPercentage'] ?? 0) * 100);
$single_percentage = round(($location_profile['data']['singlePercentage'] ?? 0) * 100);
$married_percentage = round(($location_profile['data']['marriedPercentage'] ?? 0) * 100);
$property_categories = $location_profile['data']['propertyCategories'] ?? '';

function output_suburb_or_postcode($text, $suburb_label, $postcode) {
    if (strpos($text, '{suburb}') !== false) {
        $text = str_replace('{suburb}', $suburb_label, $text);
    }
    if (strpos($text, '{postcode}') !== false) {
        $text = str_replace('{postcode}', $postcode, $text);
    }
    return $text;
}

$def_suburb_description =  'Showcasing a prominent local presence in ' . $suburb_label . ' 
                            and a team illustrating rich and accumulative experience, ' . get_bloginfo('name') . ' 
                            offers an unrivalled calibre of personal attention. Established with a focus on delivering a personal 
                            and customised service, our commitment to honesty, integrity and professionalism is reflected in our 
                            strong sales history and industry reputation in ' . $suburb_label . ' and surrounding suburbs.';
$dsp_suburb_description = get_option('dsp_suburb_description') ? output_suburb_or_postcode(get_option('dsp_suburb_description'), $suburb_label, $postcode) : $def_suburb_description;

$def_suburb_about_heading = 'About ' . get_bloginfo('name');
$dsp_suburb_about_heading = get_option('dsp_suburb_about_heading') ? output_suburb_or_postcode(get_option('dsp_suburb_about_heading'), $suburb_label, $postcode) : $def_suburb_about_heading;

$def_demographics_description = 'A little bit about who lives locally, as provided by government census data.';
$dsp_demographics_description = get_option('dsp_demographics_description') ? output_suburb_or_postcode(get_option('dsp_demographics_description'), $suburb_label, $postcode) : $def_demographics_description;

$def_market_trends_description = 'View median property prices in ' . $suburb_label . ' to get a better understanding of local market trends.';
$dsp_market_trends_description = get_option('dsp_market_trends_description') ? output_suburb_or_postcode(get_option('dsp_market_trends_description'), $suburb_label, $postcode) : $def_market_trends_description;

$surrounding_suburbs = $location_profile['surroundingSuburbs'] ?? '';

$surrounding_suburbs_list = [];
foreach ($surrounding_suburbs as $s_suburb) {
    if (isset($s_suburb['name'])) {
        $surrounding_suburbs_list[] = $s_suburb['name'];
    }
}

$nearby_suburbs = get_posts([
    'post_type' => 'suburb-profile',
    'posts_per_page' => -1,
    'post__not_in' => [get_the_ID()],
    'meta_query' => [
        'relation' => 'OR',
        array(
            'key' => 'rc_suburb',
            'value' => $surrounding_suburbs_list,
            'compare' => 'IN'
        )
    ],
]);

// Get the travel distance and save the data as meta data on the post.
$travel_distance = new DistanceCalculator();
foreach ( $nearby_suburbs as $nearby_suburb ) {
    $travel_distance->calculateAndSaveDistances(get_the_ID(), $nearby_suburb->ID);
}

$plugin_dir = plugin_dir_url(dirname(__FILE__));
$image_dir =  $plugin_dir . 'images/';
$template_dir = $plugin_dir . 'templates/';
$items = [
    [
        'img'     => 'car.svg',
        'alt'     => 'Car',
        'time'    => 'N/A',
        'classes' => 'ps-3 pe-2',
    ],
    [
        'img'     => 'train.svg',
        'alt'     => 'Train',
        'time'    => 'N/A',
        'classes' => 'ps-2 pe-3',
    ],
    [
        'img'     => 'walking.svg',
        'alt'     => 'Walking',
        'time'    => 'N/A',
        'classes' => 'ps-3 pe-2',
    ],
    [
        'img'     => 'bicycle.svg',
        'alt'     => 'Bicycle',
        'time'    => 'N/A',
        'classes' => 'ps-2 pe-3',
    ],
];

?>
<div id="suburb-profile-header" class="container-fluid position-relative py-2 py-md-5"
    style="background-image: url(<?php echo $thumnail_url; ?>); background-size: cover; background-position: center;">
    <div class="row position-relative z-1 py-2 my-2 py-md-5 my-md-5">
        <div class="col-sm-10 offset-sm-1 text-light pt-5 pb-0 pb-md-3">
            <div class="text-center mb-0 mb-md-4 py-2 py-md-5">
                <h1 class="dsp-hero__title text-center mb-0 display-2 fs-3 fs-md-1 py-1 py-md-3">
                    <span><?= $suburb_label . '</span><br><span class="text-white" style="letter-spacing: 1px; font-size: 30px;">' . $state . ' ' . $postcode; ?></span>
                    </sm>
                </h1>
            </div>
        </div>
    </div>
</div>
<style>
#suburb-profile-header::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.65);
    pointer-events: none;
}

.dsp-hero__title {
    .letter-spacing: 0.5px;
    font-size: 3.5rem !important;
    line-height: 3rem;
}

@media (max-width: 768px) {
    .dsp-hero__title {
        font-size: 2.8rem !important;
        line-height: 3rem;
    }
}

#dsp-nav .nav-link:before {
    content: "";
    height: 3px;
    left: 0;
    bottom: 10px;
    width: 0;
    position: absolute;
    transition: all 0.2s ease 0s;
    background: #ff7538;
}

#dsp-nav .nav-link:hover {
    color: #ff7538;
    transition: color 0.1s;
}

#dsp-nav .nav-link:hover:before {
    width: 100%;
}

#dsp-nav .nav-link.active {
    color: #ff7538 !important;
}

span.hidden-mobile {
    display: none !important;
}

body {
    height: 3000px;
}


/* Progress bar */
.progress-bar-container {
    /* position: fixed; */
    top: 0;
    left: 0;
    width: 100%;
    height: 5px;
    background-color: #f2f2f2;
    z-index: 9999;
}

.progress-bar {
    height: 100%;
    background-color: #ff7538;
    transition: all 0.3s ease;
}

.progress-background {
    fill: transparent;
    stroke: #f2f2f2;
    stroke-width: 5;
    stroke-dasharray: none;
}
</style>

<section id="dsp-nav" class="bg-white mb-5 position-sticky z-3 top-0">
    <div class="container">
        <div class="row">
            <nav class="col navbar pb-0">
                <div class="container-fluid justify-content-center">
                    <a class="nav-link navbar-brand fs-5 px-0 px-md-3 position-relative"
                        href="#section--dsp-about">About</a>
                    <a class="nav-link navbar-brand fs-5 px-0 px-md-3 position-relative"
                        href="#section--dsp-demographics">Demographics</a>
                    <a class="nav-link navbar-brand fs-5 px-0 px-md-3 position-relative"
                        href="#section--dsp-location">Location</a>
                    <a class="nav-link navbar-brand fs-5 px-0 px-md-3 position-relative"
                        href="#section--dsp-market-trends">Market</a>
                </div>
                <div class="progress-bar-container">
                    <div class="progress-bar"></div>
                </div>
            </nav>
        </div>
    </div>
</section>

<section id="section--dsp-about">
    <div class="container">
        <div class="row">
            <div class="col">
                <h2 class="dsp-about__title"><?= $dsp_suburb_about_heading; ?></h2>
                <div class="dsp-about__content">
                    <?php if (get_the_content()) : ?>
                        <?= get_the_content(); ?>
                    <?php else: ?>
                        <p class="mb-0"><?= $dsp_suburb_description; ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col">
                <hr class="bg-dark border-1 border-top border-dark my-5">
            </div>
        </div>
    </div>
</section>

<section id="section--dsp-demographics">
    <div class="container">
        <div class="row mb-5">
            <div class="col">
                <h2 class="dsp-demographics__title">Demographics</h2>
                <div class="dsp-demographics__content">
                    <p><?= $dsp_demographics_description; ?></p>
                </div>
            </div>
        </div>
        <div class="row gy-3">
            <?php if ($population) { ?>
            <div class="col-6 col-md-3">
                <h5 class="mb-2">Population</h5>
                <p class="fs-2 py-5 border border-1 border-dark text-center rounded fw-bold"><?= $population ?>
                </p>
            </div>
            <?php } ?>
            <?php if ($average_age) { ?>
            <div class="col-6 col-md-3">
                <h5 class="mb-2">Average Age</h5>
                <p class="fs-2 py-5 border border-1 border-dark text-center rounded fw-bold">
                    <?= $average_age ?></p>
            </div>
            <?php } ?>

            <div class="col-md-6">
                <div class="d-flex justify-content-between">
                    <h5 class="mb-2">Owner</h5>
                    <h5 class="mb-2">Renter</h5>
                </div>
                <div class="progress d-flex justify-content-between pe-3 mb-3" style="height: 40px;">
                    <div class="progress-bar ps-3 text-start fs-5 fw-bold" role="progressbar"
                        aria-valuenow="<?= $owner_percentage ?>" aria-valuemin="0" aria-valuemax="100"
                        style="width:<?= $owner_percentage ?>%">
                        <?= $owner_percentage ?>%</div>
                    <span class="float-right fs-5 fw-bold" style="line-height: 40px;"><?= $renter_percentage ?>%</span>
                </div>
                <div class="d-flex justify-content-between">
                    <h5 class="mb-2">Family</h5>
                    <h5 class="mb-2">Single</h5>
                </div>
                <div class="progress d-flex justify-content-between pe-3 mb-3" style="height: 40px;">
                    <div class="progress-bar w-75 ps-3 text-start fs-5 fw-bold" role="progressbar"
                        aria-valuenow="<?= $married_percentage ?>" aria-valuemin="0" aria-valuemax="100"
                        style="width:<?= $married_percentage ?>%">
                        <?= $married_percentage ?>%</div>
                    <span class="float-right fs-5 fw-bold" style="line-height: 40px;"><?= $single_percentage ?>%</span>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="section--dsp-location">
    <div class="container">
        <div class="row my-5">
            <div class="col d-flex">
                <h4 class="fw-bolder flex-column d-flex mb-0 justify-content-center" style="width: 150px;">Distance to
                </h4>
                <div>
                    <select id="nearby-suburbs-select" class="form-select dsp-nearby-suburbs"
                        aria-label="Nearby suburbs dropdown" style="width: 250px;">
                        <?php foreach ($nearby_suburbs as $index => $nearby_suburb) { 
                            $drive_lat = get_post_meta($nearby_suburb->ID, 'rc_lat', true);
                            $drive_long = get_post_meta($nearby_suburb->ID, 'rc_long', true);
                            $drive_suburb = get_post_meta($nearby_suburb->ID, 'rc_suburb', true);
                            $drive_suburb = ucwords(strtolower($drive_suburb));
                            $drive_state = get_post_meta($nearby_suburb->ID, 'rc_state', true);
                            $drive_postcode = get_post_meta($nearby_suburb->ID, 'rc_postcode', true);
                        ?>
                        <option value="<?= get_the_title($nearby_suburb->ID); ?>"
                            data-lat="<?= esc_attr($drive_lat); ?>" data-long="<?= esc_attr($drive_long); ?>"
                            data-drive-car="<?php echo isset($travel_distance->data[$nearby_suburb->ID]['driving']['duration']) ? esc_attr($travel_distance->data[$nearby_suburb->ID]['driving']['duration']) : ''; ?>"
                            data-drive-train="<?php echo isset($travel_distance->data[$nearby_suburb->ID]['transit']['duration']) ? esc_attr($travel_distance->data[$nearby_suburb->ID]['transit']['duration']) : ''; ?>"
                            data-drive-walking="<?php echo isset($travel_distance->data[$nearby_suburb->ID]['walking']['duration']) ? esc_attr($travel_distance->data[$nearby_suburb->ID]['walking']['duration']) : ''; ?>"
                            data-drive-bicycle="<?php echo isset($travel_distance->data[$nearby_suburb->ID]['bicycling']['duration']) ? esc_attr($travel_distance->data[$nearby_suburb->ID]['bicycling']['duration']) : ''; ?>"
                            <?= $index === 0 ? 'selected' : ''; ?>>
                            <?= $drive_suburb . ', ' . $drive_state . ' ' . $drive_postcode ; ?>
                        </option>
                        <?php } ?>
                    </select>
                </div>
            </div>
        </div>
        <div class=" row my-5">
            <?php foreach ($items as $item) { ?>
            <div class="col-6 col-md-3 mb-3 <?php echo $item['classes']; ?> px-md-2">
                <div class="border border-1 border-dark rounded p-5 d-flex flex-column align-items-center">
                    <img src="<?php echo $image_dir.$item['img']; ?>" alt="<?php echo $item['alt']; ?>"
                        style="width: 28px; height: 28px;">
                    <p class="mt-2 mb-0 text-center rc-drive-<?= strtolower($item['alt']); ?>">
                        <?php echo $item['time']; ?></p>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col">
                <hr class="bg-dark border-1 border-top border-dark my-5">
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col">
                <div id="map" style="height: 500px; width: 100%;"></div>
            </div>
        </div>
    </div>
</section>

<div class="container">
    <div class="row">
        <div class="col">
            <hr class="bg-dark border-1 border-top border-dark my-5">
        </div>
    </div>
</div>

<section id="section--dsp-market-trends">
    <div class="container">
        <div class="row">
            <div class="col">
                <h2 class="dsp-market-trends__title">Market Trends</h2>
                <div class="dsp-market-trends__content">
                    <p><?= $dsp_market_trends_description; ?></p>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col table-responsive">
                <table class="table border rounded-1">
                    <thead>
                        <tr class="text-dark text-center">
                            <th class="text-dark">Bed<span class="hidden-mobile">rooms</span></th>
                            <th class="text-dark">Type</th>
                            <th class="text-dark">Median Price</th>
                            <th class="text-dark d-none d-md-table-cell">Avg Days on Market</th>
                            <th class="text-dark d-none d-md-table-cell">Clearance Rate</th>
                            <th class="text-dark d-none d-md-table-cell">Sold This Year</th>
                            <th class="text-dark"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($property_categories as $item) : ?>
                        <?php
                            $id = strtolower($item['propertyCategory']) . '-' . $item['bedrooms'];
                            $bedrooms = $item['bedrooms'] == 1 ? $item['bedrooms'].' Bed' : $item['bedrooms'].' Beds';
                            $property_category = $item['propertyCategory'];
                            $number_sold = 0.0 != $item['numberSold'] ? $item['numberSold'] : '-';
                            $days_on_market = $item['daysOnMarket'] ?: '-';
                            $days_on_market_label = '-' == $days_on_market ? '-' : $days_on_market . ' ' . ($days_on_market < 2 ? 'day' : 'days');

                            $median_sold_price = 0 != $item['medianSoldPrice'] ? dsp_nice_number($item['medianSoldPrice']) : '-';
                            $entry_level_price = 0 != $item['entryLevelPrice'] ? dsp_nice_number($item['entryLevelPrice']) : '-';
                            $luxury_level_price = 0 != $item['luxuryLevelPrice'] ? dsp_nice_number($item['luxuryLevelPrice']) : '-';
                            $median_rent_price = 0 != $item['medianRentPrice'] ? dsp_nice_number($item['medianRentPrice']) : '-';
                            $auction_clearance_rate = 0.0 != $item['auctionClearanceRate'] ? round(($item['auctionClearanceRate']) * 100) . '%' : '-';

                            $chart_years = '';
                            $chart_median_sold_prices = '';
                            
                            $sales_growth_list = $item['salesGrowthList'] ?? null;
                            usort($sales_growth_list, function ($a, $b) {
                                return $b['year'] <=> $a['year'];
                            });
                        ?>
                        <tr>
                            <td class="text-center"><?= $bedrooms; ?></td>
                            <td class="text-center"><?= $property_category; ?></td>
                            <td class="text-center"><?= $median_sold_price; ?></td>
                            <td class="text-center d-none d-md-table-cell"><?= $days_on_market_label; ?></td>
                            <td class="text-center d-none d-md-table-cell"><?= $auction_clearance_rate; ?></td>
                            <td class="text-center d-none d-md-table-cell"><?= $number_sold; ?></td>
                            <td class="text-center">
                                <button
                                    class="btn btn-sm btn-primary rounded-circle fw-bolder p-2 d-inline-flex justify-content-center"
                                    style="width: 30px; height: 30px; line-height: 0.75;" data-bs-toggle="collapse"
                                    href="#trend-<?= $id; ?>" role="button" aria-expanded="false"
                                    aria-controls="trend-<?= $id; ?>">+</button>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="7" scope="row" class="collapse rounded-2 border" id="trend-<?= $id; ?>">
                                <div class="suburb-profile-market-performance p-4">
                                    <div class="row">
                                        <div class="col-12">
                                            <p class="display-1 fs-1 text-dark">Market Performance</p>
                                        </div>
                                    </div>
                                    <?php if ($number_sold >= 10) : ?>
                                    <div class="row gy-4">
                                        <div class="col-12">
                                            <h4 class="text-dark">Sales Price Range</h4>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="row gy-4">
                                                <div class="col-12">
                                                    <h5>Median Price</h5>
                                                    <p class="h2"><?= $median_sold_price; ?></p>
                                                </div>
                                                <div class="col-md-6">
                                                    <h5>Entry Level</h5>
                                                    <p class="h2"><?= $entry_level_price; ?>
                                                </div>
                                                <div class="col-md-6">
                                                    <h5>High End</h5>
                                                    <p class="h2"><?= $luxury_level_price; ?></p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="row gy-4">
                                                <div class="col-md-6">
                                                    <div class="d-flex justify-content-between">
                                                        <span>Sold This Year</span>
                                                        <span><?= $number_sold; ?></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="d-flex justify-content-between">
                                                        <span>Rental Median Price</span>
                                                        <span>$<?= $median_rent_price; ?></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="d-flex justify-content-between">
                                                        <span>Auction clearance<br><small>Higher = more
                                                                competition</small></span>
                                                        <span><?= $auction_clearance_rate; ?></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="d-flex justify-content-between">
                                                        <span>Average days on market<br><small>Lower = more
                                                                competition</small></span>
                                                        <span><?= $days_on_market_label; ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="row gy-3">
                                                <div class="col-12">
                                                    <h4>Sales and Growth</h4>
                                                </div>
                                                <div class="col-md-6">
                                                    <table class="table table-striped text-center">
                                                        <tr>
                                                            <th>Year</th>
                                                            <th>Median</th>
                                                            <th>Growth</th>
                                                            <th># of Sales</th>
                                                        </tr>
                                                        <?php foreach ($sales_growth_list as $sub_item) :?>
                                                        <?php
                                                                $sales_year = $sub_item['year'];
                                                                $sales_number_sold = $sub_item['numberSold'] ?: '-';

                                                                $sales_median_sold_price = $sub_item['medianSoldPrice'];
                                                                $sales_median_sold_price_label = 0 != $sales_median_sold_price ? dsp_nice_number($sales_median_sold_price) : '-';
                                                                $sales_annual_growth = round(($sub_item['annualGrowth']) * 100) . '%';

                                                                $chart_years .= $sales_year . ',';
                                                                $chart_median_sold_prices .= (string) $sales_median_sold_price . ',';
                                                            ?>
                                                        <tr>
                                                            <td><?= $sales_year; ?></td>
                                                            <td><?= $sales_median_sold_price_label; ?></td>
                                                            <td
                                                                class="fw-bold <?php echo ($sales_annual_growth > 0) ? 'text-success' : 'text-danger'; ?> ">
                                                                <?= $sales_annual_growth; ?></td>
                                                            <td><?= $sales_number_sold; ?></td>
                                                        </tr>
                                                        <?php endforeach; ?>

                                                    </table>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="d-flex justify-content-center justify-content-md-end">
                                                        <?php 
                                                            $chart_years = rtrim($chart_years, ',');
                                                            $chart_median_sold_prices = rtrim($chart_median_sold_prices, ',');
                                                        ?>
                                                        <canvas class="dsp-chart" data-years="<?= $chart_years; ?>"
                                                            data-median-sold-prices="<?= $chart_median_sold_prices; ?>"></canvas>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php else : ?>
                                    <div class="row">
                                        <div class="col-12">
                                            <h4 class="display-2 fs-1 text-dark">Not enough data</h4>
                                            <p><?= $number_sold; ?> sales this year for 2 bedroom House in
                                                <?= $suburb_label; ?>, market performance data requires a minimum of 10
                                                sales.
                                            </p>
                                        </div>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <p>* Data based on sales within the last 12 months</p>
            </div>
        </div>
    </div>
</section>

<style>
.section--dsp-nav {
    position: sticky;
    top: 0;
    z-index: 2;
}

body.admin-bar .section--dsp-nav {
    top: 32px;
}

@media (max-width: 782px) {
    body.admin-bar .section--dsp-nav {
        top: 46px;
    }
}
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function(event) {

    // Nice Number
    function niceNumber(value) {
        if (value >= 1000000) {
            return (value / 1000000).toFixed(0) + 'M';
        } else if (value >= 1000) {
            return (value / 1000).toFixed(0) + 'K';
        } else {
            return value;
        }
    }

    // Chart JS
    var charts = document.querySelectorAll('.dsp-chart');

    charts.forEach(function(chart) {
        var ctx = chart.getContext('2d');
        var years = chart.getAttribute('data-years').split(',');
        var medianSoldPrices = chart.getAttribute('data-median-sold-prices').split(',');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: years,
                datasets: [{
                    label: 'Median Sold Prices',
                    data: medianSoldPrices,
                    borderColor: '#ff7538',
                    borderWidth: 2
                }]
            },
            options: {
                scales: {
                    x: {
                        reverse: true,
                    },
                    y: {
                        ticks: {
                            callback: function(value) {
                                return '$' + niceNumber(value);
                            }
                        },
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    });
});
document.addEventListener('DOMContentLoaded', function() {
    <?php if ( isset ($boundary ) && isset ($center ) ) { ?>
    // Map Boundary
    var suburbCoords = <?php echo $boundary ?>;
    var suburbCenter = <?php echo $center ?>;

    function initMap() {
        var mapOptions = {
            zoom: 13,
            center: suburbCenter,
        };
        var map = new google.maps.Map(document.getElementById('map'), mapOptions);

        var suburbPolygon = new google.maps.Polygon({
            paths: suburbCoords,
            strokeColor: '#f7941d',
            strokeOpacity: 0.8,
            strokeWeight: 2,
            fillColor: '#f7941d',
            fillOpacity: 0.35,
        });

        suburbPolygon.setMap(map);
    }

    google.maps.event.addDomListener(window, 'load', initMap);
    <?php } ?>

    // Update drive times on change or click of select element
    const selectElement = document.querySelector('.dsp-nearby-suburbs');
    const driveCarElement = document.querySelector('.rc-drive-car');
    const driveTrainElement = document.querySelector('.rc-drive-train');
    const driveWalkElement = document.querySelector('.rc-drive-walking');
    const driveBikeElement = document.querySelector('.rc-drive-bicycle');

    function updateDriveTimes() {
        const selectedOption = selectElement.options[selectElement.selectedIndex];
        driveCarElement.textContent = selectedOption.getAttribute('data-drive-car') || 'N/A';
        driveTrainElement.textContent = selectedOption.getAttribute('data-drive-train') || 'N/A';
        driveWalkElement.textContent = selectedOption.getAttribute('data-drive-walking') || 'N/A';
        driveBikeElement.textContent = selectedOption.getAttribute('data-drive-bicycle') || 'N/A';
    }

    // Update drive times on initial load
    updateDriveTimes();

    // Update drive times on change or click of select element
    selectElement.addEventListener('change', updateDriveTimes);
    selectElement.addEventListener('click', updateDriveTimes);

    const options = document.querySelectorAll('.dsp-nearby-suburbs option');
    const origin = {
        lat: <?= $lat; ?>,
        lng: <?= $long; ?>
    };
});

// Progress bar
function updateProgressBar() {
    const progressBar = document.querySelector('.progress-bar');
    const totalHeight = document.body.scrollHeight - window.innerHeight;
    const progress = (window.pageYOffset / totalHeight) * 100;
    progressBar.style.width = progress + '%';
}

updateProgressBar();
window.addEventListener('scroll', updateProgressBar);
window.addEventListener('resize', updateProgressBar);


// function scrollToTop() {
//     window.scrollTo({
//         top: 0,
//         behavior: 'smooth'
//     });
// }

// const scrollToTopElement = document.querySelector('.scroll-to-top');
// scrollToTopElement.addEventListener('click', scrollToTop);
</script>

<style>
nav.navbar {
    background-color: #fff;
}

.nav-link {
    color: #000;
    text-decoration: none;
    padding: 15px;
}

.admin-bar .position-sticky.top-0 {
    top: 32px !important;
}
</style>
<?php