<?php

namespace App\Http\Controllers\Front;

use App\Shop\Categories\Category;
use App\Shop\Categories\Repositories\Interfaces\CategoryRepositoryInterface;
use App\Shop\Contact\Contact;
use App\Shop\Products\Product;
use App\Slider;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Providers\PixServiceProvider;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Front\CollectionController;

class HomeController extends Controller
{
    /**
     * @var CategoryRepositoryInterface
     */
    private $categoryRepo;
    private $controllerColecao;

    private $aux;

    /**
     * HomeController constructor.
     * @param CategoryRepositoryInterface $categoryRepository
     */
    public function __construct(CategoryRepositoryInterface $categoryRepository, Controller $base)
    {
        $this->categoryRepo = $categoryRepository;
        $this->controllerColecao  = new CollectionController();
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {   

        $this->clearFlash();

        $sliders = Slider::where([
            ['ativo', 1],
            ['idempresa', 13]
        ])->limit(5)->get();

        $destaques = Product::where([
            ['principal', 1],
            ['loja', 1],
            ['status', 1],
            ['promocao', 0]
        ])->orderBy('id', 'DESC')->limit(9)->get();
        $destaques = $this->getGrade($destaques);

        $promocoes = Product::where([
            ['promocao', 1],
            ['loja', 1],
            ['status', 1],
            ['promocao', 1]
        ])->orderBy('id', 'DESC')->limit(9)->get();
        $promocoes = $this->getGrade($promocoes);

        $destaques->map(function ($product) {
            $this->checkPromotion($product);
            //Busca media da avaliação do produto
            $product->media = $this->getRating($this->getReviews($product));
        });

        $promocoes->map(function ($product) {
            $this->checkPromotion($product);
            //Busca media da avaliação do produto
            $product->media = $this->getRating($this->getReviews($product));
        });


        $cont = 0;
        $sliders = $sliders->map(function ($slider, $cont) {
            $slider->categoria = Category::where('id', $slider->idcategoria)->get();
            $slider->titulo = Category::where('id', $slider->idcategoria)->first()->descricao;
            $slider->subtitulo = 'Oferta';
            $categoria = Category::where('id', $slider->idcategoria)->first();
            $slider->link = 'categoria/' . $categoria->descricao;
            $slider->cont = ++$cont;
            return $slider;
        });

        $cards = $this->getPromotionCards();

        $multiplied = $promocoes->map(function ($item, $key) {
            return $item->images;
        });

        $colecaoThema = DB::table('tblcolecao')->where('colecaothema', 1)->first();
        $colecaoThema->products = $this->controllerColecao->getProductCollection($colecaoThema->id);

        return view('front.index', compact('destaques', 'promocoes', 'sliders', 'cards', 'colecaoThema'));
    }

    public function getContact()
    {
        return view('front.contact.contact');
    }

    public function storeContact(Request $request)
    {
        $contact = new Contact();
        $contact->nome = $request->name;
        $contact->email = $request->email;
        $contact->assunto = $request->subject;
        $contact->mensagem = $request->msg;
        $contact->save();
        request()->session()->flash('message', 'Formulário Enviado Com Sucesso!');
        return view('front.contact.contact');
    }

    public function clearFlash()
    {
        session()->forget('message');
        session()->forget('error');
    }
    
}
