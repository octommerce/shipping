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

        if (post('address_id')) {
            $address = Address::whereId(post('address_id'))->whereUserId($user->id)->first();

            if (! $address) {
                throw new ApplicationException('Address not found');
            }
        } else {
            $address = Address::make(['user_id' => Auth::getUser()->id]);
        }

        $address->fill(post());

        Flash::success($address->id ? 'Address changed' : 'New address created');

        $address->save();

        $this->page['address'] = $address;
        $this->page['addresses'] = $this->loadAddresses();
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
}
