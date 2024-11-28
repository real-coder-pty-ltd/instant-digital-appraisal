<?php
/**
 * The template for displaying a single appraisal.
 *
 * @package understrap
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

// Assets
$banner_img_url = the_post_thumbnail('full', ['class' => 'w-100', 'style' => 'max-height: 650px; object-fit: cover;']) ?? '<img class="w-100 object-fit-cover" style="max-height: 650px;" src="' . plugins_url('../images/background.jpg', __FILE__) . '">';

// Address Details
$suburb = get_field('general_suburb');
$state = get_field('general_state');
$postcode = get_field('general_postcode');

// Demographics
$demographics = get_field('demographics');

// Population
$population = number_format($demographics['agegroupofpopulation']['total']);

// Average Age
$average_age = [];
$average_age_highest_value = 0;
$average_age_list = $demographics['agegroupofpopulation']['items'];

foreach ($average_age_list as $index => $item) {
    if ($item['value'] > $average_age_highest_value) {
        $average_age_highest_value = $item['value'];
        $average_age = $item;
    }
}

// Nature of Occupancy
$nature_of_occupancy = $demographics['natureofoccupancy']['items'];
$nature_of_occupancy_result = rc_ida_calculate_and_display_percentage($nature_of_occupancy, ['Rented', 'Fully Owned']);

// Marital Status
$marital_status = $demographics['maritalstatus']['items'];
$marital_status_result = rc_ida_calculate_and_display_percentage($marital_status, ['Never Married', 'Married']);

// Suburb Performance Statistics
$suburb_performance_statistics = get_field('suburb_performance_statistics');
$suburb_performance_statistics_list = $suburb_performance_statistics['items'];

?>

<section class="section--rc-ida-hero mb-5">
    <div class="container">
        <div class="row">
            <div class="col-12">
            </div>
        </div>
    </div>
    <div class="bg-image text-center text-white position-relative">
        <?= $banner_img_url;  ?>
        <div class="position-absolute d-flex top-0 right-0 bottom-0 left-0 h-100 w-100 justify-content-center flex-column text-center"
            style="background-color: rgba(0, 0, 0, 0.4)">
            <h1 class="rc-ida-hero__title text-center mb-0"><?= get_the_title(); ?></h1>
        </div>
    </div>
</section>

<section class="section--rc-ida-nav bg-white mb-5">
    <div class="container">
        <div class="row">
            <nav class="col navbar pb-0">
                <div class="container-fluid justify-content-center">
                    <a class="navbar-brand px-3 fw-bold" href="#suburb-profile-about">About</a>
                    <a class="navbar-brand px-3 fw-bold" href="#suburb-profile-demographics">Demographics</a>
                    <a class="navbar-brand px-3 fw-bold" href="#suburb-profile-location">Location</a>
                    <a class="navbar-brand px-3 fw-bold" href="#suburb-profile-market-trends">Market</a>
                </div>
                <hr class="bg-dark border-1 border-top border-dark w-100 mt-2 mb-0">
            </nav>
        </div>
    </div>
</section>

<section class="section--rc-ida-about" id="suburb-profile-about">
    <div class="container">
        <div class="row">
            <div class="col">
                <h2 class="rc-ida-about__title">About <?= get_bloginfo('name'); ?></h2>
                <div class="rc-ida-about__content">
                    <?php if (get_the_content()) : ?>
                    <?= get_the_content(); ?>
                    <?php else : ?>
                    <p class="mb-0">Showcasing a prominent local presence in <?= $suburb ?> and a team illustrating rich
                        and accumulative experience, <?= get_bloginfo('name'); ?> offers an unrivalled calibre of
                        personal attention. Established with a focus on delivering a personal and customised service,
                        our commitment to honesty, integrity and professionalism is reflected in our strong sales
                        history and industry reputation in <?= $suburb ?> and surrounding suburbs.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section--hr">
    <div class="container">
        <div class="row">
            <div class="col">
                <hr class="bg-dark border-1 border-top border-dark my-5">
            </div>
        </div>
    </div>
</section>

<?php if ($demographics) : ?>
<section class="section--rc-ida-demographics" id="suburb-profile-demographics">
    <div class="container">
        <div class="row mb-5">
            <div class="col">
                <h2 class="rc-ida-demographics__title">Demographics</h2>
                <div class="rc-ida-demographics__content">
                    <p>A little bit about who lives locally, as provided by government census data.</p>
                </div>
            </div>
        </div>
        <div class="row gy-3">
            <?php if ($population) : ?>
            <div class="col-6 col-md-3">
                <h5 class="mb-2">Population</h5>
                <p class="fs-2 py-5 border border-1 border-dark text-center rounded fw-bold"><?= $population ?></p>
            </div>
            <?php endif; ?>
            <?php if ($average_age) : ?>
            <div class="col-6 col-md-3">
                <h5 class="mb-2">Average Age</h5>
                <p class="fs-2 py-5 border border-1 border-dark text-center rounded fw-bold">
                    <?= $average_age['label'] ?></p>
            </div>
            <?php endif; ?>

            <div class="col-md-6">
                <?php if ($nature_of_occupancy_result) : ?>
                <div class="d-flex justify-content-between">
                    <h5 class="mb-2">Owner</h5>
                    <h5 class="mb-2">Renter</h5>
                </div>
                <div class="progress d-flex justify-content-between pe-3 mb-3" style="height: 40px;">
                    <div class="progress-bar ps-3 text-start fs-5 fw-bold" role="progressbar"
                        aria-valuenow="<?= $nature_of_occupancy_result['item_1_percentage'] ?>" aria-valuemin="0"
                        aria-valuemax="100" style="width:<?= $nature_of_occupancy_result['item_1_percentage'] ?>%">
                        <?= $nature_of_occupancy_result['item_1_percentage'] ?>%</div>
                    <span class="float-right fs-5 fw-bold"
                        style="line-height: 40px;"><?= $nature_of_occupancy_result['item_2_percentage'] ?>%</span>
                </div>
                <?php endif; ?>

                <?php if ($marital_status_result) : ?>
                <div class="d-flex justify-content-between">
                    <h5 class="mb-2">Family</h5>
                    <h5 class="mb-2">Single</h5>
                </div>
                <div class="progress d-flex justify-content-between pe-3 mb-3" style="height: 40px;">
                    <div class="progress-bar w-75 ps-3 text-start fs-5 fw-bold" role="progressbar"
                        aria-valuenow="<?= $marital_status_result['item_1_percentage'] ?>" aria-valuemin="0"
                        aria-valuemax="100" style="width:<?= $marital_status_result['item_1_percentage'] ?>%">
                        <?= $marital_status_result['item_1_percentage'] ?>%</div>
                    <span class="float-right fs-5 fw-bold"
                        style="line-height: 40px;"><?= $marital_status_result['item_2_percentage'] ?>%</span>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<section class="section--hr">
    <div class="container">
        <div class="row">
            <div class="col">
                <hr class="bg-dark border-1 border-top border-dark my-5">
            </div>
        </div>
    </div>
</section>

<section class="section--rc-ida-location" id="suburb-profile-location">
    <div class="container">
        <div class="row">
            <div class="col">
                <div id="map" style="height: 500px; width: 100%;"></div>
            </div>
        </div>
    </div>
</section>

<section class="section--hr">
    <div class="container">
        <div class="row">
            <div class="col">
                <hr class="bg-dark border-1 border-top border-dark my-5">
            </div>
        </div>
    </div>
</section>

<?php if ($suburb_performance_statistics) : ?>
<section class="section--rc-ida-market-trends" id="suburb-profile-market-trends">
    <div class="container">
        <div class="row">
            <div class="col">
                <h2 class="rc-ida-market-trends__title">Market Trends</h2>
                <div class="rc-ida-market-trends__content">
                    <p>View median property prices in <?= $suburb ?> to get a better understanding of local market
                        trends.</p>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Bedrooms</th>
                            <th>Type</th>
                            <th>Median Price</th>
                            <th>Avg Days on Market</th>
                            <th>Clearance Rate</th>
                            <th>Sold This Year</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($suburb_performance_statistics_list as $item) : ?>
                        <?php 
                                $id = $item['propertycategory'] . '-' . $item['bedrooms'];
                                $bedrooms = $item['bedrooms'] == 1 ? $item['bedrooms'] . ' Bed' : $item['bedrooms'] . ' Beds';
                                $property_category = $item['propertycategory'];
                                $series_data = json_decode($item['series_data'], true);

                                $latest_item = null;
                                if (!empty($series_data['seriesInfo'])) {
                                    $latest_item = end($series_data['seriesInfo']);
                                }

                            if ( 0 != $latest_item['values']['medianSoldPrice'] || 0 != $latest_item['values']['numberSold'] || 0 != $latest_item['values']['daysOnMarket']) :
                                $latest_year = $latest_item['year'];
                                $median_sold_price = rc_ida_nice_number($latest_item['values']['medianSoldPrice']) ? : '-';
                                $entry_level = rc_ida_nice_number($latest_item['values']['5thPercentileSoldPrice']) ? : '-';
                                $high_end = rc_ida_nice_number($latest_item['values']['95thPercentileSoldPrice']) ? : '-';
                                $median_rent_listing_price = rc_ida_nice_number($latest_item['values']['medianRentListingPrice']);
                                $days_on_market = $latest_item['values']['daysOnMarket'] ?? 0;
                                $days_label = $days_on_market > 1 ? 'days' : 'day';
                                $days_text = $days_on_market != 0 ? $days_on_market . ' ' . $days_label : '-';
                                $number_sold = $latest_item['values']['numberSold'] ? : '-';
                                $auction_clearance_rate = 0;

                                if (($latest_item['values']['auctionNumberAuctioned'] + $latest_item['values']['auctionNumberWithdrawn']) > 0) {
                                    $auction_number_sold = $latest_item['values']['auctionNumberSold'];
                                    $auction_number_auctioned = $latest_item['values']['auctionNumberAuctioned'];
                                    $auction_number_withdrawn = $latest_item['values']['auctionNumberWithdrawn'] ?? 0;

                                    $auction_clearance_rate = $auction_number_sold / ($auction_number_auctioned + $auction_number_withdrawn) * 100;
                                    $auction_clearance_rate_text = number_format($auction_clearance_rate, 0) . '%';
                                } else {
                                    $auction_clearance_rate_text = '-';
                                }
                                
                                // Output the totals
                                echo '<tr>';
                                echo '<td>' . $bedrooms . '</td>';
                                echo '<td>' . $property_category . '</td>';
                                echo '<td>' . $median_sold_price . '</td>';
                                echo '<td>' . $days_text . '</td>';
                                echo '<td>' . $auction_clearance_rate_text . '</td>';
                                echo '<td>' . $number_sold . '</td>';
                                echo
                                    '<td>
                                        <button class="btn btn-sm btn-primary rounded-circle fw-bolder p-2 d-inline-flex justify-content-center"
                                            style="width: 30px; height: 30px; line-height: 0.75;" data-bs-toggle="collapse"
                                            href="#trend-' . $id . '" role="button" aria-expanded="false"
                                            aria-controls="trend-' . $id . '">+</button>
                                    </td>';
                                echo '</tr>';
                            ?>

                        <tr>
                            <td colspan="7" scope="row" class="collapse rounded-2 border" id="trend-<?= $id; ?>">
                                <div class="suburb-profile-market-performance p-4">
                                    <div class="row">
                                        <div class="col-12">
                                            <h3>Market Performance</h3>
                                        </div>
                                    </div>
                                    <?php if (10 <= number_format($latest_item['values']['numberSold'])) : ?>
                                    <div class="row gy-4">
                                        <div class="col-12">
                                            <h4>Sales Price Range</h4>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="row gy-4">
                                                <div class="col-12">
                                                    <h5>Median Price</h5>
                                                    <p class="h2"><?= $median_sold_price; ?></p>
                                                </div>
                                                <div class="col-md-6">
                                                    <h5>Entry Level</h5>
                                                    <p class="h2"><?= $entry_level; ?>
                                                </div>
                                                <div class="col-md-6">
                                                    <h5>High End</h5>
                                                    <p class="h2"><?= $high_end; ?></p>
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
                                                        <span>$<?= $median_rent_listing_price; ?></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="d-flex justify-content-between">
                                                        <span>Auction clearance<br><small>Higher = more
                                                                competition</small></span>
                                                        <span><?= $auction_clearance_rate_text; ?></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="d-flex justify-content-between">
                                                        <span>Average days on market<br><small>Lower = more
                                                                competition</small></span>
                                                        <span><?= $days_text; ?></span>
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
                                                    <table class="table table-striped">
                                                        <tr>
                                                            <th>Year</th>
                                                            <th>Median</th>
                                                            <th>Growth</th>
                                                            <th># of Sales</th>
                                                        </tr>
                                                        <?php
                                                                        $median_sold_price_last = 0;
                                                                        
                                                                        $data = [];

                                                                        $years = '';
                                                                        $median_sold_prices = '';

                                                                        foreach ($series_data['seriesInfo'] as $item) {
                                                                            $year = 0;
                                                                            $median_sold_price = 0;
                                                                            $growth = 0;
                                                                            $number_sold = 0;

                                                                            $year = $item['year'];
                                                                            $median_sold_price = $item['values']['medianSoldPrice'] ?? 0;

                                                                            if ($latest_year - 6 < $year) {
                                                                                $median_sold_price_label = rc_ida_nice_number($item['values']['medianSoldPrice']) ?: '-';

                                                                                if ($median_sold_price_last != 0) {
                                                                                    $growth = (($median_sold_price - $median_sold_price_last) / $median_sold_price_last) * 100;
                                                                                } else {
                                                                                    $growth = 0;
                                                                                }
                                                                                $growth_label = number_format($growth, 1) . '%';
                                                                                $number_sold = $item['values']['numberSold'] ?: '-';

                                                                                $data[] = [
                                                                                    'year' => $year,
                                                                                    'median_sold_price' => $median_sold_price,
                                                                                    'median_sold_price_label' => $median_sold_price_label,
                                                                                    'growth_label' => $growth_label,
                                                                                    'number_sold' => $number_sold
                                                                                ];

                                                                                $years .= $year . ',';
                                                                                $median_sold_prices .= (string) $median_sold_price . ',';
                                                                            }

                                                                            $median_sold_price_last = $median_sold_price;
                                                                        }

                                                                        usort($data, function ($a, $b) {
                                                                            return $b['year'] <=> $a['year'];
                                                                        });

                                                                        foreach ($data as $row) {
                                                                            echo '<tr>';
                                                                            echo '<td>' . $row['year'] . '</td>';
                                                                            echo '<td>' . $row['median_sold_price_label'] . '</td>';
                                                                            echo '<td>' . $row['growth_label'] . '</td>';
                                                                            echo '<td>' . $row['number_sold'] . '</td>';
                                                                            echo '</tr>';
                                                                        }

                                                                        $years = rtrim($years, ',');
                                                                        $median_sold_prices = rtrim($median_sold_prices, ',');
                                                                    ?>
                                                    </table>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="d-flex justify-content-center justify-content-md-end">
                                                        <canvas class="rc-ida-chart" data-years="<?= $years ?>"
                                                            data-median-sold-prices="<?= $median_sold_prices ?>"></canvas>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php else : ?>
                                    <div class="row">
                                        <div class="col-12">
                                            <h4>Not enough data</h4>
                                            <p><?= $number_sold; ?> sales this year for 2 bedroom House in
                                                <?= $suburb ;?>, market performance data requires a minimum of 10 sales.
                                            </p>
                                        </div>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endif; ?>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<style>
.section--rc-ida-hero {
    background-image: url('<?= $bg_img_url; ?>');
    background-repeat: no-repeat;
    background-size: cover;
}

.section--rc-ida-nav {
    position: sticky;
    top: 0;
    z-index: 2;
}

body.admin-bar .section--rc-ida-nav {
    top: 32px;
}

@media (max-width: 782px) {
    body.admin-bar .section--rc-ida-nav {
        top: 46px;
    }
}
</style>

<?php 
    $state = match($state) {
        'NSW' => 'New South Wales',
        'VIC' => 'Victoria',
        'QLD' => 'Queensland',
        'SA' => 'South Australia',
        'WA' => 'Western Australia',
        'TAS' => 'Tasmania',
        'NT' => 'Northern Territory',
        'ACT' => 'Australian Capital Territory',
        default => 'Queensland',
    };
    $fetcher = [
        'suburb' => $suburb,
        'state' => $state,
        'country' => 'Australia',
    ];
    $fetch = new BoundaryFetcher($fetcher['suburb'], $fetcher['state'], $fetcher['country']);
?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function(event) {
    var charts = document.querySelectorAll('.rc-ida-chart');

    function niceNumber(value) {
        if (value >= 1000000) {
            return (value / 1000000).toFixed(0) + 'M';
        } else if (value >= 1000) {
            return (value / 1000).toFixed(0) + 'K';
        } else {
            return value;
        }
    }

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
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    x: {},
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

    var suburbCoords = <?php echo $fetch->boundary; ?>;
    var suburbCenter = <?php echo $fetch->center; ?>;

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
});
</script>

<?php