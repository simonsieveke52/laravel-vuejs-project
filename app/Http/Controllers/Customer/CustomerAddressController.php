<?php

namespace App\Http\Controllers\Customer;

use App\Address;
use App\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use App\Repositories\AddressRepository;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\CartController;
use App\Http\Requests\CreateAddressRequest;

class CustomerAddressController extends Controller
{
    /**
     * @var AddressRepository
     */
    protected $addressRepository;

    /**
     * @param AddressRepository $addressRepository
     */
    public function __construct(AddressRepository $addressRepository)
    {
        $this->addressRepository = $addressRepository;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $customer = $this->loggedUser();

        return view('front.customers.addresses.list', [
            'customer' => $customer,
            'addresses' => $customer->addresses
        ]);
    }

    /**
     * @param int $customerId
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(int $customerId)
    {
        return view('front.customers.addresses.create', [
            'customer' => $this->loggedUser(),
            'countries' => Country::all(),
            'customer_id' => $customerId
        ]);
    }

    /**
     * @param CreateAddressRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CreateAddressRequest $request)
    {
        $data = $request->except('_token');

        $address = $request->input('address_type') === 'billing'
            ? $this->addressRepository->createBillingAddress($data)
            : $this->addressRepository->createShippingAddress($data);

        $this->loggedUser()->addresses()->save($address);
        
        return redirect()->route('customer.account');
    }

    /**
     * Update selected address
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request): \Illuminate\Http\JsonResponse
    {
        if ($request->has('billing_address')) {
            session(['billing_address' => (int) $request->billing_address]);
        }

        if ($request->has('shipping_address')) {
            session(['shipping_address' => (int) $request->billing_address]);
        }

        return response()->json(true);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int  $customerId
     * @param int  $addressId
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($customerId, $addressId): \Illuminate\Http\RedirectResponse
    {
        $customer = $this->loggedUser();

        if ($customer->id !== $customerId) {
            $customerId = $customer->id;
        }

        Address::where('id', $addressId)
            ->where('customer_id', $customerId)
            ->delete();
        
        return redirect()->back();
    }
}
