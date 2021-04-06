<?php

namespace App\Http\Controllers\Admin\Promotions;

use App\Empresa;
use App\Http\Controllers\Controller;
use App\Shop\Products\Product;
use App\Shop\Products\ProductPromotion;
use App\Shop\Promotions\Promotion;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class PromotionController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $promotions = Promotion::paginate(10);
        return view('admin.promotions.list', compact('promotions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.promotions.create', [
            'promotions' => Promotion::all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  CreateCategoryRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Promotion::create($request->all());
        return redirect()->route('admin.promotions.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $promotion = Promotion::find($id);
        $products = new Collection();
        $productPromotion = ProductPromotion::where('idpromocao', $promotion->id)->get();

        foreach ($productPromotion as $item) {
            $products->push(Product::find($item->idproduto));
        }

        return view('admin.promotions.show', [
            'promotion' => $promotion,
            'products' => $products
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return view('admin.promotions.edit', [
            'promotion' => Promotion::find($id),
            'empresas' => Empresa::all()
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateCategoryRequest $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $promotion = Promotion::find($id);
        $promotion->update($request->all());
        $request->session()->flash('message', 'Update successful');
        return redirect()->route('admin.promotions.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        Promotion::find($id)->delete();
        request()->session()->flash('message', 'Promoção Deletada Com Sucesso');
        return redirect()->route('admin.coupons.index');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function removeImage(Request $request)
    {
        $this->categoryRepo->deleteFile($request->only('category'));
        request()->session()->flash('message', 'Image delete successful');
        return redirect()->route('admin.coupons.edit', $request->input('category'));
    }
}
