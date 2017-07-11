<?php namespace Octommerce\Shipping\Components;

use Auth;
use Flash;
use Validator;
use ApplicationException;
use Cms\Classes\ComponentBase;
use Octommerce\Shipping\Models\Address;

class Locations extends ComponentBase
{

    public function componentDetails()
    {
        return [
            'name'        => 'Locations Component',
            'description' => 'No description provided yet...'
        ];
    }

    public function defineProperties()
    {
        return [];
    }

    public function onRun()
    {
        $this->page['addresses'] = $this->loadAddresses();
    }

    public function loadAddresses()
    {
        if (! Auth::check())
            return [];
    
        return Address::whereUserId($this->getUser()->id)->get();
    }

    public function loadAddress($addressId)
    {
        return Address::whereId($addressId)->whereUserId($this->getUser()->id)->first();
    }

    public function onSave()
    {
        if ( ! $user = $this->getUser()) {
            return;
        }

        $validator = $this->getValidator();

        if ($validator->fails()) {
            throw new ApplicationException($validator->messages()->first());
        }

        if (post('address_id')) {
            $address = Address::whereId(post('address_id'))->whereUserId($user->id)->first();

            if ( ! $address) {
                throw new \ApplicationException('Address not found.');
            }

            $address->fill(post());

            $address->save();

            $this->page['addresses'] = $this->loadAddresses();

            Flash::success('Alamat berhasil diubah');

            return;
        }

        $data = array_merge(post(), ['user_id' => Auth::getUser()->id]);

        $address = Address::create($data);

        $this->page['address'] = $address;
        $this->page['addresses'] = $this->loadAddresses();

        Flash::success('Alamat berhasil ditambahkan');
    }

    public function onDelete()
    {
        if ( ! $user = $this->getUser()) {
            return;
        }

        $address = Address::whereId(post('address_id'))->whereUserId($user->id)->first();

        if ( ! $address) {
            throw new \ApplicationException('Address not found.');
        }

        $address->delete();

        $this->page['addresses'] = $this->loadAddresses();

        Flash::success('Alamat berhasil dihapus');
    }

    public function onSetPrimary()
    {
        if ( ! $user = $this->getUser()) {
            return;
        }

        if ( ! $address = $this->loadAddress(post('address_id')))
            return new \ApplicationException('Address not found');

        $address->setPrimary();

        $this->page['addresses'] = $this->loadAddresses();
        $this->page['address'] = $address;

        Flash::success('Berhasil mengubah alamat tujuan bawaan');
    }

    public function onGetAddress()
    {
        if (!$user = $this->getUser()) {
            return;
        }

        if ( ! $address = $this->loadAddress(post('address_id')))
            return new \ApplicationException('Address not found');

        $this->page['address'] = $address;

        return [
            'address' => $address->toArray(),
        ];
    }

    protected function getUser()
    {
        if ( ! $user = Auth::getUser()) {
            return null;
        }

        return $user;
    }

    protected function getValidator()
    {
        
        $rules = [
            'address_name'  => ['required', 'min:3', 'regex:/^[a-z A-Z]+$/'],
            'name'          => ['required', 'min:3', 'regex:/^[a-z A-Z]+$/'],
            'street'        => 'required|min:30|string',
            'phone'         => ['required', 'regex:/^(?:\+?62[^0]|0[^0])[0-9]{9,10}$/'],
            'location_code' => 'required',
        ];

        $messages = [
            'location_code.required' => 'The subdistrict field is required.'
        ];

        return Validator::make(post(), $rules, $messages);
    }

}
