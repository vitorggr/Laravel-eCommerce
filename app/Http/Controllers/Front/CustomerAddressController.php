<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Providers\CorreioEnvironmentServiceProvider;
use App\Providers\SigepServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Shop\Addresses\Address;
use App\Shop\Addresses\Requests\CreateAddressRequest;
use App\Shop\Addresses\Requests\UpdateAddressRequest;
use App\Shop\Cities\Repositories\Interfaces\CityRepositoryInterface;
use App\Shop\Addresses\Repositories\Interfaces\AddressRepositoryInterface;
use App\Shop\Countries\Repositories\Interfaces\CountryRepositoryInterface;
use App\Shop\Provinces\Repositories\Interfaces\ProvinceRepositoryInterface;
use PhpSigep\Services\SoapClient\Real;
use Illuminate\Support\Facades\Auth;

class CustomerAddressController extends Controller
{
    /**
     * @var AddressRepositoryInterface
     */
    private $addressRepo;

    /**
     * @var CountryRepositoryInterface
     */
    private $countryRepo;

    /**
     * @var CityRepositoryInterface
     */
    private $cityRepo;

    /**
     * @var ProvinceRepositoryInterface
     */
    private $provinceRepo;

    /**
     * @param AddressRepositoryInterface  $addressRepository
     * @param CountryRepositoryInterface  $countryRepository
     * @param CityRepositoryInterface     $cityRepository
     * @param ProvinceRepositoryInterface $provinceRepository
     */
    public function __construct(
        AddressRepositoryInterface $addressRepository,
        CountryRepositoryInterface $countryRepository,
        CityRepositoryInterface $cityRepository,
        ProvinceRepositoryInterface $provinceRepository
    ) {
        $this->addressRepo = $addressRepository;
        $this->countryRepo = $countryRepository;
        $this->provinceRepo = $provinceRepository;
        $this->cityRepo = $cityRepository;
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function index()
    {
        return redirect()->route(
            'conta',
            [
                'tab' => 'address',
                'result' => $this->getAutoComplete()
            ]
        );
    }

    public function getAutoComplete()
    {
        return parent::getAutoComplete();
    }

    /**
     * @param  $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {        
        $customer = Auth::user();
        $result = null;
        
        if(request()->input('cep')){
            new SigepServiceProvider();

            $q = request()->input('cep');
            $phpSigep = new Real();
            $result = $phpSigep->consultaCep($q);
            if ($result->getResult() == null && $q!=null) {
                session()->flash('error', Str::title($result->getErrorMsg()));
            }elseif($result->getResult() != null){
                session()->flash('message','Cep Válido');
            }
                
            $result = $result->getResult();
        }
        
        return view('front.customers.addresses.create', [
            'customer' => $customer,
            'endereco' => $result,
            'result' => $this->getAutoComplete()
        ]);

    }

    /**
     * @param CreateAddressRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request['cliente_id'] = Auth::user()->Id;
        // $this->addressRepo->createAddress($request->except('_token', '_method'));
        Address::create($request->all());
        return redirect()->route('conta', ['tab' => 'endereco'])
            ->with('message', 'Endereço adicionado com sucesso!');
    }

    /**
     * @param $addressId
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($customerId, $addressId)
    {
        // $countries = $this->countryRepo->listCountries();
        // $address = $this->addressRepo->findCustomerAddressById($addressId, auth()->user());
        $address = Address::where('cliente_id', Auth::user()->Id)->first();

        return view('front.customers.addresses.edit', [
            'customer' => Auth::user(),
            'address' => $address,
            'result' => $this->getAutoComplete()
            // 'countries' => $countries,
            // 'cities' => $this->cityRepo->listCities(),
            // 'provinces' => $this->provinceRepo->listProvinces()
        ]);
    }

    /**
     * @param UpdateAddressRequest $request
     * @param $addressId
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $customerId, $addressId)
    {
        Address::find($addressId)->update($request->all());
        return redirect()->route('conta', ['tab' => 'endereco'])
            ->with('message', 'Endereço atualizado com sucesso!');
    }

    /**
     * @param $addressId
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($customerId, $addressId)
    {
        // $address = $this->addressRepo->findCustomerAddressById($addressId, auth()->user());
        $address = Address::find($addressId);
        $address->delete();
        //    if ($address->orders()->exists()) {
        //          $address->status=0;
        //          $address->save();
        //    }
        //    else {
        //          $address->delete();
        //    }
        return redirect()->route('conta', ['tab' => 'endereco'])
            ->with('message', 'Endereço removido com sucesso');
    }

    
}
