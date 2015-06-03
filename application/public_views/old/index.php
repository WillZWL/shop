<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
	<div class="container">
		<header></header>
		<nav>
			<ul>
				<li><a href="#">Home</a></li>
				<li><a href="#">Home</a></li>
				<li><a href="#">Home</a></li>
				<li><a href="#">Home</a></li>
				<li><a href="#">Home</a></li>
				<li><a href="#">Home</a></li>
			</ul>
		</nav>

		<section>
			<div class="category">
			</div>
			<div class="search">
			</div>
		</section>

		<section>
			<div class="banner">
			</div>
		</section>

		<?php $this->load->view('lastest_product', $contry) ?>
		<?php $this->load->view('lastest_arrival') ?>
		<?php $this->load->view('lastest_product') ?>

		<section class="lastest_pordoucts">
			<header>
				<h2>Lastest Pordoucts</h2>
			</header>
			<ul>
				<?php foreach ($best_seller as $sku => $product): ?>
				<li>
					<img src="#" alt="">
					<div class="desc">
						<h3><a href="<?=$product['prod_url']?>"><?=$product['prod_name']?></a></h3>
						<ol>
							<li><?=$product['rrp_price']?></li>
							<li><?=$product['price']?></li>
							<li><?=$product['listing_status']?></li>
							<li><span><a href="<?=$product['prod_url']?>">more info</a></span><span><a href="#">add to basket</a></span></li>
						</ol>
					</div>
				</li>
				<?php endforeach ?>
			</ul>
		</section>

		<section class="lastest_pordoucts">
			<header>
				<h2>Latest Arrival</h2>
			</header>
			<ul>
				<?php foreach ($latest_arrival as $sku => $product): ?>
				<li>
					<img src="#" alt="">
					<div class="desc">
						<h3><a href="<?=$product['prod_url']?>"><?=$product['prod_name']?></a></h3>
						<ol>
							<li><?=$product['rrp_price']?></li>
							<li><?=$product['price']?></li>
							<li><?=$product['listing_status']?></li>
							<li><span><a href="<?=$product['prod_url']?>">more info</a></span><span><a href="#">add to basket</a></span></li>
						</ol>
					</div>
				</li>
				<?php endforeach ?>
			</ul>
		</section>

		<section class="lastest_pordoucts">
			<header>
				<h2>Clearance Product</h2>
			</header>
			<ul>
				<?php foreach ($clearance_product as $sku => $product): ?>
				<li>
					<img src="#" alt="">
					<div class="desc">
						<h3><a href="<?=$product['prod_url']?>"><?=$product['prod_name']?></a></h3>
						<ol>
							<li><?=$product['rrp_price']?></li>
							<li><?=$product['price']?></li>
							<li><?=$product['listing_status']?></li>
							<li><span><a href="<?=$product['prod_url']?>">more info</a></span><span><a href="#">add to basket</a></span></li>
						</ol>
					</div>
				</li>
				<?php endforeach ?>
			</ul>
		</section>


		<section class="reasons_to_buy_from_us">
			<article>

			</article>
		</section>

		<section class="linkshare">
			<ul>
				<li>1</li>
				<li>1</li>
				<li>1</li>
				<li>1</li>
				<li>1</li>
			</ul>
		</section>
	</div>
</body>
</html>