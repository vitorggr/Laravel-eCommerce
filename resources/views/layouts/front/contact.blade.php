	<!-- Google Map -->
	<div class="map">
		<div id="google_map" class="google_map">
			<div class="map_container">
				<div id="map"></div>
			</div>
		</div>
	</div>
	<br><br>
	<div class="contact">

		<div class="container">
			<div class="row">
				<div class="col">
					@include('layouts.errors-and-messages')<br>
					<div class="review_form_title">Entre em Contato</div>
					<div class="review_form_content">
						<form method="POST" action="/contact" id="review_form" class="review_form">
							{{csrf_field()}}
							<div class="d-flex flex-md-row flex-column align-items-start justify-content-between">
								<input type="text" name="name" class="review_form_input" placeholder="Nome" required="required">
								<input type="email" name="email" class="review_form_input" placeholder="E-mail" required="required">
								<input type="text" name="subject" class="review_form_input" placeholder="Assunto">
							</div>
							<textarea class="review_form_text" name="msg" placeholder="Mensagem"></textarea>
							<button type="submit" class="review_form_button" required="required">Enviar Mensagem</button>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
	</div>


	<!-- Contact Informations -->
	<div class="contact_text">
		<div class="container">
			<div class="row">


				<div class="col-lg-5">

					<div class="contact_info">
						<div class="contact_title">Informações Úteis</div>
						<div class="contact_info_content">
							<ul>
								<li>
									<div class="contact_info_icon"><img src="images/contact_info_1.png" alt=""></div>
									<div class="contact_info_text">Av. Getúlio Vargas, 1420, Savassi</div>
								</li>
								<li>
									<div class="contact_info_icon"><img src="images/contact_info_2.png" alt=""></div>
									<div class="contact_info_text">contato@easyfashion.com.br</div>
								</li>
								<li>
									<div class="contact_info_icon"><img src="images/contact_info_3.png" alt=""></div>
									<div class="contact_info_text">(31) 3358-6569</div>
								</li>
							</ul>
						</div>
					</div>
				</div>


				<div class="col-lg-7">
					<div class="faq">
						<div class="contact_title" style="text-align: center;">Dúvidas Frequentes</div>
						<div class="faq_content">


							<div class="accordions">

								<div class="accordion_container">
									<div class="accordion d-flex flex-row align-items-center">
										<div>Duvida 1</div>
									</div>
									<div class="accordion_panel">
										<p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Tenetur aperiam mollitia modi amet quas natus, adipisci ducimus minima impedit quo assumenda voluptatem reiciendis culpa recusandae quae vero repudiandae dicta nam.</p>
									</div>
								</div>

								<div class="accordion_container">
									<div class="accordion d-flex flex-row align-items-center">
										<div>Duvida 2</div>
									</div>
									<div class="accordion_panel">
										<p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Vel sunt dolorem laudantium facilis? Id, voluptatem autem? Nobis natus reiciendis ipsam, aspernatur exercitationem quaerat rem, perspiciatis quod aliquid iusto dicta et?</p>
									</div>
								</div>

								<div class="accordion_container">
									<div class="accordion d-flex flex-row align-items-center active">
										<div>Duvida 3</div>
									</div>
									<div class="accordion_panel">
										<p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Velit accusantium dolores aliquam, nemo tenetur adipisci iste perspiciatis magni veritatis culpa, illum suscipit quis molestiae voluptates inventore hic quisquam possimus sunt!</p>
									</div>
								</div>

							</div>

						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- Newsletter -->

	@include('front.newsletter.index')