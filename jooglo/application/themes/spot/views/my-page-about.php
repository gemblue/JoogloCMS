	<div id="blue">
		<div class="container">
			<div class="row centered">
				<div class="col-lg-8 col-lg-offset-2">
				<h4>LEARN MORE ABOUT US</h4>
				<p>WE ARE COOL PEOPLE</p>
				</div>
			</div><!-- row -->
		</div><!-- container -->
	</div><!--  bluewrap -->


	<div class="container w">
		<div class="row centered">
			<br><br>
			<div class="col-lg-3">
				<img class="img-circle" src="<?php echo $template_path.'/assets/img/pic.jpg';?>" width="110" height="110" alt="">
				<h4>Frank Lampard</h4>
				<p>There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form.</p>
				<p><a href="#">@Frank_BlackTie</a></p>
			</div><!-- col-lg-3 -->

			<div class="col-lg-3">
				<img class="img-circle" src="<?php echo $template_path.'/assets/img/pic2.jpg';?>" width="110" height="110" alt="">
				<h4>David Wright</h4>
				<p>There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form.</p>
				<p><a href="#">@David_BlackTie</a></p>
			</div><!-- col-lg-3 -->

			<div class="col-lg-3">
				<img class="img-circle" src="<?php echo $template_path.'/assets/img/pic3.jpg';?>" width="110" height="110" alt="">
				<h4>Mark Milestone</h4>
				<p>There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form.</p>
				<p><a href="#">@Mark_BlackTie</a></p>
			</div><!-- col-lg-3 -->

			<div class="col-lg-3">
				<img class="img-circle" src="<?php echo $template_path.'/assets/img/pic4.jpg';?>" width="110" height="110" alt="">
				<h4>Tania Tissen</h4>
				<p>There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form.</p>
				<p><a href="#">@Tania_BlackTie</a></p>
			</div><!-- col-lg-3 -->

		</div><!-- row -->
		<br>
		<br>
	</div><!-- container -->


	<!-- PORTFOLIO SECTION -->
	<div id="dg">
		<div class="container">
			<div class="row centered">
				<h4>OUR SKILLS</h4>
				<br>
				
			<!-- First Chart -->
			<div class="col-lg-3">
				<canvas id="canvas" height="130" width="130"></canvas>
				<br>
				<script>
					var doughnutData = [
							{
								value: 70,
								color:"#3498db"
							},
							{
								value : 30,
								color : "#ecf0f1"
							}
						];
						var myDoughnut = new Chart(document.getElementById("canvas").getContext("2d")).Doughnut(doughnutData);
				</script>
				<p><b>Design & Brand</b></p>
				<p><small>There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form.</small></p>
			</div><!-- /col-lg-3 -->

			<!-- Second Chart -->
			<div class="col-lg-3">
				<canvas id="canvas2" height="130" width="130"></canvas>
				<br>
				<script>
					var doughnutData = [
							{
								value: 90,
								color:"#3498db"
							},
							{
								value : 10,
								color : "#ecf0f1"
							}
						];
						var myDoughnut = new Chart(document.getElementById("canvas2").getContext("2d")).Doughnut(doughnutData);
				</script>
				<p><b>Web Development</b></p>
				<p><small>There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form.</small></p>
			</div><!-- /col-lg-3 -->
			
			<!-- Third Chart -->
			<div class="col-lg-3">
				<canvas id="canvas3" height="130" width="130"></canvas>
				<br>
				<script>
					var doughnutData = [
							{
								value: 50,
								color:"#3498db"
							},
							{
								value : 50,
								color : "#ecf0f1"
							}
						];
						var myDoughnut = new Chart(document.getElementById("canvas3").getContext("2d")).Doughnut(doughnutData);
				</script>
				<p><b>Seo Services</b></p>
				<p><small>There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form.</small></p>
			</div><!-- /col-lg-3 -->
			
			<!-- Fourth Chart -->
			<div class="col-lg-3">
				<canvas id="canvas4" height="130" width="130"></canvas>
				<br>
				<script>
					var doughnutData = [
							{
								value: 80,
								color:"#3498db"
							},
							{
								value : 20,
								color : "#ecf0f1"
							}
						];
						var myDoughnut = new Chart(document.getElementById("canvas4").getContext("2d")).Doughnut(doughnutData);
				</script>
				<p><b>Printing</b></p>
				<p><small>There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form.</small></p>
			</div><!-- /col-lg-3 -->
				
				
			</div><!-- row -->
		</div><!-- container -->
	</div><!-- DG -->



	
	<div id="r">
		<div class="container">
			<div class="row centered">
				<div class="col-lg-8 col-lg-offset-2">
					<h4>WE ARE STORYTELLERS. BRANDS ARE OUR SUBJECTS. DESIGN IS OUR VOICE.</h4>
					<p>We believe ideas come from everyone, everywhere. At BlackTie, everyone within our agency walls is a designer in their own right. And there are a few principles we believe—and we believe everyone should believe—about our design craft. These truths drive us, motivate us, and ultimately help us redefine the power of design.</p>
				</div>
			</div><!-- row -->
		</div><!-- container -->
	</div><! -- r wrap -->