<?php

namespace App\Livewire\Fundraising;

use App\Models\Fundraiser;
use App\Models\PaymentMethod;
use Livewire\Component;
use LivewireUI\Modal\ModalComponent;

class Donate extends ModalComponent
{
    public Fundraiser $fundraiser;
    public $amount;
    public $payment_method;
    public $is_anonymous = false;
    public $message;
    public $phone_number;
    public $account_number;

    public $paymentMethods = [];

    /**
     * Supported: 'sm', 'md', 'lg', 'xl', '2xl', '3xl', '4xl', '5xl', '6xl', '7xl'
     */
    public static function modalMaxWidth(): string
    {
        return '2xl';
    }

    public function mount(Fundraiser $fundraiser)
    {
        $this->fundraiser = $fundraiser;
        $this->paymentMethods = PaymentMethod::getAllActiveMethods();
        $this->amount = 5000; // Default amount in UGX
    }

    public function rules()
    {
        $rules = [
            'amount' => 'required|numeric|min:1000|max:' . $this->fundraiser->getRemainingAmount(),
            'payment_method' => 'required|exists:payment_methods,slug',
            'is_anonymous' => 'boolean',
            'message' => 'nullable|string|max:500'
        ];

        // Add phone number validation for mobile money
        if ($this->payment_method && PaymentMethod::where('slug', $this->payment_method)->where('type', 'mobile_money')->exists()) {
            $rules['phone_number'] = 'required|string|regex:/^256[0-9]{9}$/';
        }

        // Add account number validation for bank transfers
        if ($this->payment_method && PaymentMethod::where('slug', $this->payment_method)->where('type', 'bank_transfer')->exists()) {
            $rules['account_number'] = 'required|string|max:20';
        }

        return $rules;
    }

    public function updatedPaymentMethod()
    {
        // Reset form fields when payment method changes
        $this->phone_number = null;
        $this->account_number = null;
    }

    public function submit()
    {
        $this->validate();

        // Check if user is authenticated
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // Get payment method details
        $paymentMethod = PaymentMethod::where('slug', $this->payment_method)->first();

        // Create donation record
        $donation = $this->fundraiser->addDonation(
            auth()->id(),
            $this->amount,
            $this->payment_method,
            $paymentMethod->provider_code,
            $this->message,
            $this->is_anonymous
        );

        // Here you would integrate with actual payment gateway
        // For now, we'll simulate payment completion
        $this->processPayment($donation);

        $this->dispatch('close');
        $this->dispatch('donation-made', $donation->id);

        session()->flash('message', 'Thank you for your donation! Your contribution will make a difference.');
    }

    private function processPayment($donation)
    {
        // Simulate payment processing
        // In production, this would integrate with:
        // - MTN Mobile Money API
        // - Airtel Money API
        // - Bank APIs
        // - Payment gateway APIs

        // For demo purposes, we'll mark the donation as completed
        $this->fundraiser->completeDonation($donation->transaction_reference, [
            'gateway_response' => 'Payment processed successfully',
            'reference' => $donation->transaction_reference,
            'processed_at' => now()
        ]);
    }

    public function getPaymentMethodProperty()
    {
        return PaymentMethod::where('slug', $this->payment_method)->first();
    }

    public function render()
    {
        return view('livewire.fundraising.donate');
    }
}
