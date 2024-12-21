<?php

namespace App\Http\Middleware;

use App\Enums\User\UserType;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPlanFeatures
{
    public function handle($request, Closure $next, $feature)
    {

        $user = auth()->user();

        if($user->role == UserType::ADMIN){
            $subscription = Subscription::where('user_id', $user->id)->latest()->first();
            $plan = Plan::find($subscription->plan_id);


            $features =$plan->features;

            foreach ($features['dashboard'] as $key => $featureData) {
                if ($featureData['featureName'] == $feature) {
                    return $next($request);
                }
            }
        }

        if($user->role == UserType::CLIENT){
            $tenantUser = User::where('tenant_id', $user->tenant_id)->first();

            $subscription = Subscription::where('user_id', $tenantUser->id)->latest()->first();
            $plan = Plan::find($subscription->plan_id);

            $features =json_decode($plan->features, true);

            foreach ($features['client'] as $key => $featureData) {
                if ($featureData['featureName'] == $feature) {
                    return $next($request);
                }
            }

        }

        return response()->json(['error' => 'Feature not available'], 401);

    }
}
