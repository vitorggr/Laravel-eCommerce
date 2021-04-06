<?php

namespace App\Http\Controllers;

use App\Shop\Orders\Order;
use App\Shop\Products\Color;
use App\Shop\Products\Product;
use App\Shop\Products\ProductColor;
use App\Shop\Products\ProductGrade;
use App\Shop\Products\ProductPromotion;
use App\Shop\Products\ProductReview;
use App\Shop\Promotions\Promotion;
use Illuminate\Support\Collection as SupportCollection;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function loggedUser()
    {
        return auth()->user();
    }

    public static function getAutoComplete()
    {
        return str_replace(
            [chr(34) . "descricao" . chr(34), "{", ":", "}"],
            ["", "", "", ""],
            json_encode(DB::table('tblproduto')->select('descricao')->get())
        );
    }

    public function getColors(SupportCollection $products)
    {
        $idColors = new Collection;

        $products->map(function ($product) {
            $product->colors = ProductColor::select('idcor')->where('idproduto', $product->id)->get();
            return $product;
        });

        foreach ($products as $product) {
            $idColors->push($product->colors);
        }

        $idColors = $idColors->filter(function ($idColors) {
            return isset($idColors->first()->idcor);
        });

        $stringReplace = str_replace([chr(34), ",", "idcor:", "{", "[", "]", "}"], ["", "", "", "", "", "", ""], json_encode($idColors));

        $ids = array_unique(str_split($stringReplace, 1));

        $colors = new Collection;

        foreach ($ids as $id) {
            $colors->push(Color::where('id', $id)->first());
        }

        return json_decode($colors);
    }

    public function filterBySize($products)
    {

        if ($products->first() instanceof Collection) {
            $products = $products->first();
        }

        //Seta as grades presentes em cada produto=
        $products->map(function ($product) {
            $grades = new Collection();
            $grade = ProductGrade::where('idproduto', $product->id)->get();
            if ($grade->isNotEmpty()) {
                foreach ($grade as $item) {
                    $grades->push($item);
                }
            }
            $product->grades = $grades;
            return $product;
        });
        //Elimina da lista os produtos que não contem o tamanho escolhido
        $products = $products->reject(function ($product) {
            foreach ($product->grades as $grade) {
                if ($grade->descricao == request()->input('tamanho')) {
                    $product->grade = $grade;
                    return false;
                }
            }
            return true;
        });
        return $products;
    }

    public function filterByColor($products)
    {
        //Seta os ID's das cores presentes em cada produto
        if ($products->first() instanceof Collection) {
            $products = $products->first();
        }

        $products->map(function ($product) {
            $colors = new Collection();
            $color = ProductColor::select('idcor')->where('idproduto', $product->id)->get();
            if ($color->count() != 0) {
                foreach ($color as $item) {
                    $colors->push($item->idcor);
                }
            }
            $product->colors = $colors;

            //Seta as grades dos produtos presentes na lista
            /*$grades = new Collection();
            $grade = ProductGrade::where('idproduto', $product->id)->get();
            if ($grade->isNotEmpty()) {
                foreach ($grade as $item) {
                    $grades->push($item);
                }
            }
            $product->grades = $grades->first();
            */
            if (!isset($product->grade)) {
                $product->grade = ProductGrade::where('idproduto', $product->id)->get();
            }

            return $product;
        });



        //Elimina da lista os produtos que não contem a cor escolhida 
        $products = $products->reject(function ($product) {
            $idcolor = Color::select('id')->where('descricao', 'like', '%' . str_slug(request()->input('cor'), ' ') . '%')->first();
            foreach ($product->colors as $color) {
                if ($color == $idcolor->id) {
                    return false;
                }
            }
            return true;
        });

        return $products;
    }

    public function getGrade($products)
    {
        if ($products instanceof Collection || $products instanceof SupportCollection) {
            $products->map(function ($product) {
                $grades = new Collection();

                //Seta todas as grades pertencentes ao produto
                $grade = ProductGrade::where('idproduto', $product->id)->get();
                $product->grades = $grade;

                //Seta a grade de menor valor venda a ser exibida
                if ($grade->isNotEmpty()) {
                    $grades->push($grade[0]);
                    for ($i = 1; $i < sizeof($grade); $i++) {
                        if ($grade[$i]->valorvenda < $grade[0]->valorvenda)
                            $grades[0] = $grade[$i];
                    }
                }

                if ($grades->first() != null) {
                    $product->grade = $grades->first();
                }

                return $product;
            });
        } else {
            $grades = new Collection();

            //Seta todas as grades pertencentes ao produto
            $grade = ProductGrade::where('idproduto', $products->id)->get();
            $products->grades = $grade;

            //Seta a grade de menor valor venda a ser exibida
            if ($grade->isNotEmpty()) {
                $grades->push($grade[0]);
                for ($i = 1; $i < sizeof($grade); $i++) {
                    if ($grade[$i]->valorvenda < $grade[$i - 1]->valorvenda)
                        $grades->push($grade[$i]);
                }
            }

            if ($grades->first() != null) {
                $products->grade = $grades->first();
            }

            return $products;
        }

        $setThumbnail = false;

        foreach ($products as $product) {
            $setThumbnail = false;

            foreach ($product->images as $image) {
                if ($product->idthumbnail && $product->idthumbnail == $image->id) :
                    $image->thumbnail = true;
                    $setThumbnail = true;
                endif;
            }

            if (!$setThumbnail) {
                if($product->images->first()){
                $product->images->first()->thumbnail = true;
                }
            }
        }

        return $products;
    }

    protected function generateRowId($id, array $options)
    {
        ksort($options);

        return md5($id . serialize($options));
    }

    public function checkPromotion(Product $product)
    {
        $promocaoProduto = ProductPromotion::where('idproduto', $product->id)->first();
        if ($promocaoProduto != null) {
            $promocao = Promotion::where('id', $promocaoProduto->idpromocao)->first();
            if (isset($promocao)) {
                $product->promocao = true;
                $estiloPromocao = DB::table('tblestilotag')->where('id', $promocao->idestilotag)->first();
                $product->promocaoTitle = $promocao->titulo;
                $product->promocaoEstilo = $estiloPromocao->descricao;
                //Variavel temporaria recebe grades do produtoR
                $grades = $product->grades;
                //Variavel temporaria recebe grade do produto
                $grade = $product->grade;
                foreach ($grades as $item) {
                    if (isset($promocao->descontopercentual) && isset($item->valorvenda)) {
                        //atualização no valor das grades de acordo com a porcentagem de desconto
                        $item->valorvendadesconto = $item->valorvenda - ($item->valorvenda * $promocao->descontopercentual / 100);
                    } else if (isset($promocao->descontounitario) && isset($item->valorvenda)) {
                        //atualização no valor das grades de acordo com desconto unitario
                        $item->valorvendadesconto = $item->valorvenda - $item->descontounitario;
                    }
                }
                $product->grades = $grades;
                if (isset($promocao->descontopercentual) && isset($grade->valorvenda)) {
                    //atualização no valor da grade de acordo com a porcentagem de desconto
                    $grade->valorvendadesconto = $grade->valorvenda - ($grade->valorvenda * $promocao->descontopercentual / 100);
                } else if (isset($promocao->descontounitario) && isset($grade->valorvenda)) {
                    //atualização no valor da grade de acordo com a desconto unitario
                    $grade->valorvendadesconto = $grade->valorvenda - $grade->descontounitario;
                }
                $product->grade = $grade;
            } else {
                $product->promocao = false;
                $product->promocaoTitle = null;
            };
        } else {
            $product->promocao = false;
            $product->promocaoTitle = null;
        }

        return $product;
    }

    public function getPromotionCards()
    {
        $cards = Promotion::where([
            ['ativo', 1],
            ['datainicio', '<', date("Y-m-d")],
            ['datafim', '>', date("Y-m-d")],
        ])->limit(3)->get();
        return $cards;
    }

    public function clearFlash()
    {
        session()->forget('message');
        session()->forget('error');
    }

    public function getRating(Collection $review)
    {
        if (count($review) != 0) {
            $avg = null;
            foreach ($review as $r) {
                $avg += $r->avaliacao;
            }
            return $avg / count($review);
        } else return null;
    }

    public function getReviews($product)
    {
        return ProductReview::where([['idproduto', $product->id], ['ativo', 1]])->get();
    }

    public static function getLoja()
    {
        $domain = request()->getHttpHost();
        $loja = DB::table('tblconfigloja')->where('url', $domain)->first();

        return $loja;
    }
}
