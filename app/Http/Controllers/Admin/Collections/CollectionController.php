<?php

namespace App\Http\Controllers\Admin\Collections;

use App\Empresa;
use App\Http\Controllers\Controller;
use App\Shop\Collection\Collection;
use App\Shop\Products\Product;
use App\Shop\Products\ProductCollection;
use Collator;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Http\Request;

class CollectionController extends Controller
{
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $collections = Collection::paginate(10);
        return view('admin.collections.list', compact('collections'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   
        return view('admin.collections.create',[
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
        Collection::create($request->all());
        return redirect()->route('admin.collections.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {   
        $collection = Collection::find($id);
        $products = new EloquentCollection();
        $productCollection = ProductCollection::where('idcolecao',$collection->id)->get();

        foreach ($productCollection as $item) {
            $products->push(Product::find($item->idproduto));
        }

        return view('admin.collections.show', [
            'collection' => $collection,
            'collections' => Collection::all(),
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
        return view('admin.collections.edit', [
            'collection' => Collection::find($id),
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
        $collection = Collection::find($id);
        $collection->update($request->all());


        $request->session()->flash('message', 'Update successful');
        return redirect()->route('admin.collections.edit', $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {   
        $collection = Collection::find($id);
        $collection->delete();

        request()->session()->flash('message', 'Categoria Deletada Com Sucesso');
        return redirect()->route('admin.collections.index');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function removeImage(Request $request)
    {
        $this->categoryRepo->deleteFile($request->only('category'));
        request()->session()->flash('message', 'Image delete successful');
        return redirect()->route('admin.categories.edit', $request->input('category'));
    }
}
