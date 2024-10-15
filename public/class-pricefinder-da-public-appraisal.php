<?php

/**
 * The public-facing functionality of the plugin, all functions used in the templates.
 *
 * @link       https://stafflink.com.au
 * @since      1.0.0
 *
 * @package    Pricefinder_Da
 * @subpackage Pricefinder_Da/public
 */

function da_pricefinder_sales_history($rental_sales_history) {

	if (!is_array($rental_sales_history)) return;
	
    if(!empty($rental_sales_history)) { 

		$sales_history = '
		<div id="sales-history" class="mb-90">
			<div class="row">
				<div class="col">
					<h2 class="text-center mb-5"> Property Sales History </h2>
				</div>
			</div>
			<div class="row">
				<div class="col">
					<ul id="sales-history-graph">';

		foreach ($rental_sales_history as $event) : 
			
			$sales_history .= '
						<li>
							<ul>
								<li class="history-date">'.$event['date'].'</li>
								<li class="history-action">'.$event['action'].'</li>
								<li class="history-price">'.$event['price'].'</li>
								<li class="history-sale-type">'.$event['sale_type'].'</li>
							</ul>
						</li>';
				
		endforeach;

		$sales_history .= '

						</ul>			
					</div>
				</div>
			</div>';

	}

	return $sales_history;
}
