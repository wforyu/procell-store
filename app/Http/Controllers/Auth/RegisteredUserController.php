<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\ReferralCode;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'referral_code' => ['nullable', 'string', 'max:20', 'exists:referral_codes,code'],
        ]);

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ];

        if ($request->filled('referral_code')) {
            $referral = ReferralCode::where('code', $request->referral_code)->first();
            if ($referral) {
                $userData['referred_by'] = $referral->user_id;
            }
        }

        $user = User::create($userData);

        $user->initReferralCode();

        $guestOrders = Order::whereNull('user_id')
            ->whereHas('customer', fn ($q) => $q->where('email', $request->email))
            ->get();

        foreach ($guestOrders as $guestOrder) {
            $guestOrder->update(['user_id' => $user->id]);
        }

        if ($user->referred_by) {
            $referrerPoints = (int) (app('settings')?->get('points_referral_bonus') ?: 500);
            $referrer = User::find($user->referred_by);
            if ($referrer && $referrer->loyaltyPoint) {
                $referrer->loyaltyPoint->addPoints(
                    $referrerPoints,
                    'earned_from_referral',
                    'user',
                    $user->id,
                    "Bonus referral dari {$user->name}"
                );
                $referrer->referralCode->increment('total_referrals');
                $referrer->referralCode->increment('total_points_earned', $referrerPoints);
            }
        }

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
