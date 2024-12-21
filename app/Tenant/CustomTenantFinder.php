<?php

namespace App\Tenant;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Multitenancy\Models\Tenant;
use Spatie\Multitenancy\TenantFinder\TenantFinder;

class CustomTenantFinder extends TenantFinder
{
    public function findForRequest(Request $request): ?Tenant
    {
        // Ensure the request has an Authorization header or token
        $token = $request->bearerToken();
        if (!$token) {
            return null;
        }

        if (auth()->check()) {
            $tenantId = auth()->guard('api')->user()->tenant_id;

            return Tenant::find($tenantId);
        }

        return null;
    }
}
