
<div id="intro"
	<div id="slideshow">
		<div id="slide1" class="ui-tabs-panel">
			<h1>Some Description About your Software or Website</h1>
			<img src="<?php echo Yii::app()->themeManager->baseUrl; ?>/images/logo_software.png" alt="Softech Logo" class="imglogo" />
			<div id="placedesc">
				<p><strong>Put your software logo and give some description</strong>. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam a augue ipsum, in porta felis. Pellentesque suscipit odio id felis accumsan eu eleifend nulla consectetur. Proin rutrum blandit ipsum eget vehicula. Nulla vitae nulla metus. Praesent eget odio sem, sit amet pellentesque metus.</p>
				<a href="#" class="butmore">Learn More</a>
			</div>
		</div>
		<div id="slide2" class="ui-tabs-panel">
			<div id="framesslide">
				<img src="<?php echo Yii::app()->themeManager->baseUrl; ?>/images/feature.jpg" alt="Featured" />
				Lorem ipsum dolor sit amet
			</div>
			<div id="placefeatslide">
				<h1>Amazing Features in one Solutions</h1>
				<ul class="featslide">
					<li class="icon1"><strong>Phasellus scelerisque</strong> turpis ut sapien iaculis vehicula.</li>
					<li class="icon2"><strong>Proin vitae</strong> neque turpis</li>
					<li class="icon3"><strong>Donec</strong> sit amet eros at augue</li>
					<li class="icon4"><strong>Curabitur mollis</strong>, sem vestibulum condimentum vestibulum, felis dolor varius</li>
				</ul>
				<ul class="featslide">
					<li class="icon5"><strong>Sed vestibulum lobortis</strong>. Quisque tempor, sem in bibendum consequat, arcu sem molestie elit</li>
					<li class="icon6"><strong>Praesent</strong> sodales arcu non</li>
					<li class="icon7">Nulla rhoncus auctor odio</li>
				</ul>
			</div>
		</div>
		<div id="slide3" class="ui-tabs-panel">
			<h1>Pricing and Package</h1>
			<ul id="placepriceslide">
				<li>
					<div class="boxprice">
						<div class="ribbon1"></div>
						<h2>Professional Package</h2>
						<p>Curabitur mollis, sem vestibulum condimentum vestibulum, felis dolor varius neque, in viverra felis libero eget turpis.</p>
						<h3>$299.00<span>/year</span></h3>
						<a href="#" class="butorder">Order</a>
					</div>
				</li>
				<li>
					<div class="boxprice">
						<div class="ribbon2"></div>
						<h2>Unlimited Package</h2>
						<p>Sed vestibulum vestibulum lobortis. Quisque tempor, sem in bibendum consequat, arcu sem molestie elit, id volutpat enim dui ac lectus</p>
						<h3>$99.00</h3>
						<a href="#" class="butorder">Order</a>
					</div>
				</li>
				<li class="last">
					<div class="boxprice">
						<div class="ribbon3"></div>
						<h2>Personal Package</h2>
						<p>Sed vestibulum vestibulum lobortis. Quisque tempor, sem in bibendum consequat, arcu sem molestie elit, id volutpat enim dui ac lectus</p>
						<h3>FREE</h3>
						<a href="#" class="butorder">Order</a>
					</div>
				</li>
			</ul>
		</div>
		<div id="slide4" class="ui-tabs-panel">
			<h1 class="titleslide">Latest Softech <span>(v.3.1.2)</span></h1>
			<strong id="textrelease">Release date: <span>October 10th 2009</span></strong>
			<div class="clear"></div>
			<div class="placerelease1">
				<h2>New Features:</h2>
				<ul class="listfeatureslide">
					<li class="icon8"><strong>Phasellus scelerisque</strong> turpis ut sapien iaculis vehicula.</li>
					<li class="icon8">Mauris ac eros quam. <a href="#">Duis euismod</a></li>
					<li class="icon8">Aliquam euismod, massa id rhoncus bibendum, orci mauris dictum lorem, ut pharetra risus eros vitae quam.</li>
				</ul>
				<ul class="listfeatureslide">
					<li class="icon8"><strong>Vestibulum vulputate</strong> sapien sit amet augue commodo ullamcorper et at nulla. </li>
					<li class="icon8">Morbi eros eros, condimentum sed pulvinar vel, porttitor vel lacus.</li>
				</ul>
			</div>
			<div class="placerelease2">
				<h2>Release Notes:</h2>
				<ul class="listfeatureslide">
					<li class="icon9">Aliquam euismod, massa id rhoncus bibendum, orci mauris dictum lorem, ut pharetra risus eros vitae quam. </li>
					<li class="icon9"><strong>Phasellus scelerisque</strong> turpis ut sapien iaculis vehicula.</li>
					<li class="icon9">Lorem ipsum dolor</li>
				</ul>
			</div>
		</div>
		<div id="slide5" class="ui-tabs-panel">
			<h1>Testimonial</h1>
			<p id="texttestimonial">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed purus sem, porttitor vel ullam corper eu, venenatis at tortor. Vivamus non tortor lectus, nec sagittis sapien. Suspendisse id gravida nulla. Maecenas a metus ligula, ac ultricies leo. Morbi at mi sit amet velit dictum laoreet ut non ipsum.
			</p>
			<p id="texttestiname"><strong>John Doe</strong>, JohnDoe Corp. Inc.<br />
				<a href="#">http://www.themeforest.net</a>
			</p>
			<a href="#" class="butmore">More Testimonial</a>
		</div>
		<ul id="menuslide" class="ui-tabs-nav">
			<li class="first"><a href="#slide1">A Complete Coding Solutions</a></li>
			<li><a href="#slide2">Features</a></li>
			<li><a href="#slide3">Pricing &amp; Bundles</a></li>
			<li><a href="#slide4">Release Notes</a></li>
			<li class="last"><a href="#slide5">What Did They Said?</a></li>
		</ul>
	</div>
</div>

<?php 
$code = <<<EOF
$(document).ready(function() {
	$("#menuslide").tabs({ fx: { opacity: 'toggle' } }).tabs({ fx: { opacity: 'toggle' }}).tabs('rotate',5000); 
});
EOF;

Yii::app()->clientScript->registerScript('menuslide', $code, CClientScript::POS_END);

?>