	<?php

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

	// $loja = \App\Http\Controllers\Controller::getLoja();

	$directory = null ?>

	<header class="header">
		<div class="header_inner d-flex flex-row align-items-center justify-content-start">
			<div class="logo"><a href="/">Easy Shop</a></div>
			<nav class="main_nav">
				<ul>
					<li><a href="/">Home</a></li>
					<li type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						<a>Categorias <i class="fa fa-caret-down" aria-hidden="true"></i></a>
					</li>

					<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
						<div class="dropdown">
							@foreach(\App\Shop\Categories\Category::all() as $category)
							<li><a class="dropdown-item col-md-12" href="{{ route('front.category.slug', str_slug($category->descricao,'-')) }}">{{$category->descricao}}</a></li>
							@endforeach
						</div>
					</div>
					<div class="menu_dropdown">
						<li type="button" id="dropdownMenuButton2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							<a>Coleções <i class="fa fa-caret-down" aria-hidden="true"></i></a>
						</li>


						<div class="dropdown-menu" aria-labelledby="dropdownMenuButton2">
							<div class="dropdown">
								@foreach(\App\Shop\Collection\Collection::all() as $collection)
								<li><a class="dropdown-item col-md-12" href="{{ route('front.collection.slug', str_slug($collection->nome,'-')) }}">{{$collection->nome}}</a></li>
								@endforeach
							</div>
						</div>
					</div>
					<li><a href="/contato">Contato</a></li>

				</ul>
			</nav>
			<div class="header_content">
				<div class="search header_search">
					<form autocomplete="off" method="GET" action="../../../busca">
						<div class="autocomplete">
							<input type="text" name="produto" id="headerInput" class="search_input" required>
							<button type="submit" id="search_button" class="search_button"><img src="{{ asset(''.$directory.'images/magnifying-glass.svg') }}" alt=""></button>
						</div>
					</form>
				</div>

				<div class="shopping">
					<!-- Avatar -->
					<a href="{{ route('conta', ['tab'=> 'perfil'])}}">
						<div class="avatar">
							<img src="{{ asset(''.$directory.'images/avatar.svg') }}" alt="">
							@if(auth()->user())
							Olá, {{explode(" ",auth()->user()->Nome)[0]}}!
							@endif
						</div>
					</a>
					<!-- Cart -->
					<a href="{{ route('carrinho.index') }}" title="Ver Carrinho">
						<div class="cart">
							<img src="{{ asset(''.$directory.'images/shopping-bag.svg') }}" alt="">
							<div class="cart_num_container">
								<div class="cart_num_inner">
									<div class="cart_num">{{$cartCount}}</div>
								</div>
							</div>
						</div>
					</a>
					<!-- Star -->
					<a href="{{route('favoritos.index')}}" title="Lista de Desejos">
						<div class="star">
							<img src="{{ asset(''.$directory.'images/star.svg') }}" alt="">
							<div class="star_num_container">
								<div class="star_num_inner">
									<div class="star_num">
										<?php
										$items = Session::get('wishlist');
										$count = 0;
										if (!empty($items)) {
											foreach ($items['default'] as $item) {
												// $count += $item->qty;
												$count += 1;
											}
										}
										echo $count;
										?>
									</div>
								</div>
							</div>
						</div>
					</a>

					<!-- Logout -->
					<a href="{{ route('logout') }}">
						<div class="avatar">
							<img src="{{ asset(''.$directory.'images/logout2.svg') }}" alt="">
						</div>
					</a>
				</div>
			</div>

			<div class="burger_container d-flex flex-column align-items-center justify-content-around menu_mm">
				<div></div>
				<div></div>
				<div></div>
			</div>

	</header>
	<!-- Menu -->

	<div class="menu d-flex flex-column align-items-end justify-content-start text-right menu_mm trans_400">
		<div class="menu_close_container">
			<div class="menu_close">
				<div></div>
				<div></div>
			</div>
		</div>
		<div class="logo menu_mm"><a href="#">Easy Shop</a></div>
		<div class="search">
			<form autocomplete="off" method="GET" action="../../../busca">
				<div class="autocomplete">
					<input type="search" class="search_input menu_mm" name="produto" id="menuInput" required="required">
					<button type="submit" id="search_button_menu" class="search_button menu_mm"><img class="menu_mm" src="{{ asset('images/magnifying-glass.svg') }}" alt=""></button>
				</div>
			</form>
		</div>
		<nav class="menu_nav">
			<ul class="menu_mm">
				<li class="menu_mm"><a href="#">home</a></li>
				@foreach(\App\Shop\Categories\Category::all() as $category)
				<li><a href="{{ route('front.category.slug', str_slug($category->descricao,'-')) }}">{{$category->descricao}}</a></li>
				@endforeach
				<li class="menu_mm"><a href="#">contato</a></li>
				<li class="menu_mm"><a href="route{{route ('logout') }}"><b>Sair</b></a></li>
			</ul>

		</nav>
	</div>

	</div>

	<script type="text/javascript">
		function autoComplete(inp, arr) {

			var currentFocus;

			inp.addEventListener("input", function(e) {
				var a, b, i, val = this.value,
					count = 0;
				/*close any already open lists of autocompleted values*/
				closeAllLists();
				if (!val) {
					return false;
				}
				currentFocus = -1;
				/*create a DIV element that will contain the items (values):*/
				a = document.createElement("DIV");
				a.setAttribute("id", this.id + "autocomplete-list");
				a.setAttribute("class", "autocomplete-items");
				/*append the DIV element as a child of the autocomplete container:*/
				this.parentNode.appendChild(a);
				/*for each item in the array...*/
				if (inp.value.length > 2) {
					for (i = 0; i < arr.length; i++) {
						/*check if the item starts with the same letters as the text field value:*/
						if (arr[i].substr(0, val.length).toUpperCase() == val.toUpperCase()) {
							/*create a DIV element for each matching element:*/
							b = document.createElement("DIV");
							/*make the matching letters bold:*/
							const name = arr[i];
							b.innerHTML = "<a href=/" + name.replace(/\s/g, '%20') + "><strong>" + arr[i].substr(0, val.length) + "</strong></a>";
							b.innerHTML += arr[i].substr(val.length);
							/*insert a input field that will hold the current array item's value:*/
							b.innerHTML += "<input type='hidden' value='" + arr[i] + "'>";
							/*execute a function when someone clicks on the item value (DIV element):*/
							b.addEventListener("click", function(e) {
								//php function to redirect route
								/*insert the value for the autocomplete text field:*/
								inp.value = this.getElementsByTagName("input")[0].value;
								/*close the list of autocompleted values,
								(or any other open lists of autocompleted values:*/
								closeAllLists();
							});
							if (count < 15) {
								a.appendChild(b);
								count++;
							}
						}
					}
				}

			});
			/*execute a function presses a key on the keyboard:*/
			inp.addEventListener("keydown", function(e) {
				var x = document.getElementById(this.id + "autocomplete-list");
				if (x) x = x.getElementsByTagName("div");
				if (e.keyCode == 40) {
					/*If the arrow DOWN key is pressed,
					increase the currentFocus variable:*/
					currentFocus++;
					/*and and make the current item more visible:*/
					addActive(x);
				} else if (e.keyCode == 38) { //up
					/*If the arrow UP key is pressed,
					decrease the currentFocus variable:*/
					currentFocus--;
					/*and and make the current item more visible:*/
					addActive(x);
				} else if (e.keyCode == 13) {
					/*If the ENTER key is pressed, prevent the form from being submitted,*/
					// e.preventDefault();
					if (currentFocus > -1) {
						/*and simulate a click on the "active" item:*/
						if (x) x[currentFocus].click();
					}
				}
			});

			function addActive(x) {
				/*a function to classify an item as "active":*/
				if (!x) return false;
				/*start by removing the "active" class on all items:*/
				removeActive(x);
				if (currentFocus >= x.length) currentFocus = 0;
				if (currentFocus < 0) currentFocus = (x.length - 1);
				/*add class "autocomplete-active":*/
				x[currentFocus].classList.add("autocomplete-active");
			}

			function removeActive(x) {
				/*a function to remove the "active" class from all autocomplete items:*/
				for (var i = 0; i < x.length; i++) {
					x[i].classList.remove("autocomplete-active");
				}
			}

			function closeAllLists(elmnt) {
				/*close all autocomplete lists in the document,
				except the one passed as an argument:*/
				var x = document.getElementsByClassName("autocomplete-items");
				for (var i = 0; i < x.length; i++) {
					if (elmnt != x[i] && elmnt != inp) {
						x[i].parentNode.removeChild(x[i]);
					}
				}
			}
			/*execute a function when someone clicks in the document:*/
			document.addEventListener("click", function(e) {
				closeAllLists(e.target);
			});
		}
		var result = <?php echo Controller::getAutoComplete(); ?>;
		autoComplete(document.getElementById("headerInput"), result);
		autoComplete(document.getElementById("menuInput"), result);
	</script>