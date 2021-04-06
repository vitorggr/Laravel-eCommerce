<div id="newsletter" class="newsletter">
    <div class="newsletter_content">
        <div class="newsletter_image" style="background-image: url({{ asset('images/newsletter.jpg') }});"></div>
        <div class="container">
            <div class="row">
                <div class="col">
                    <div class="section_title_container text-center">
                        <div class="section_subtitle"></div>
                        <div class="section_title">Se inscreva na nossa newsletter</div>
                    </div>
                </div>
            </div>
            <div class="row newsletter_container">
                <div class="col-lg-10 offset-lg-1">
                    <div class="newsletter_form_container">
                        <form action="newsletter" method="POST">
                            {{ csrf_field() }}
                            <input type="email" class="newsletter_input" name="email" required="required" placeholder="E-mail">
                            <button type="submit" class="newsletter_button">Me Inscrever</button>
                        </form>
                    </div>
                    <div class="newsletter_text">Você receberá promoções exclusivas.</div>
                </div>
            </div>
        </div>
    </div>
</div>