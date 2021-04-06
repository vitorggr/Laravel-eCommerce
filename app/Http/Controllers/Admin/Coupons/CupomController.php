<?php

namespace App\Http\Controllers\Admin\Coupons;

use App\Empresa;
use App\Http\Controllers\Controller;
use App\Shop\Collection\Collection;
use App\Shop\Coupon\Coupon;
use App\Shop\Products\Product;
use App\Shop\Products\ProductCollection;
use Collator;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Http\Request;

class CupomController extends Controller
{
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cupons = Coupon::paginate(10);
        return view('admin.coupons.list', compact('cupons'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   
        return view('admin.coupons.create',[
            'empresas' => Empresa::all()
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
        Coupon::create($request->all());
        return redirect()->route('admin.coupons.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {   
        $coupon = Coupon::find($id);

        return view('admin.coupons.show', [
            'coupon' => $coupon   
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
        return view('admin.coupons.edit', [
            'coupon' => Coupon::find($id),
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
        $cupom = Coupon::find($id);
        $cupom->update($request->all());


        $request->session()->flash('message', 'Update successful');
        return redirect()->route('admin.coupons.edit', $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {   
        Coupon::find($id)->delete();
        
        request()->session()->flash('message', 'Cupom Deletado Com Sucesso');
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
