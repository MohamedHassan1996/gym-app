<?php
namespace App\Http\Middleware;

use App\Models\Tenant;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class CustomTenantFinder
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Get the bearer token
        $token = $request->bearerToken();
        if (!$token) {
            return response()->json(['error' => 'Token not provided'], 401);
        }

        // Ensure the user is authenticated
        if (auth()->check()) {
            $tenantId = auth()->guard('api')->user()->tenant_id;

            if (!$tenantId) {
                return response()->json(['error' => 'No tenant assigned to the user'], 403);
            }

            // Fetch the tenant details
            $tenant = Tenant::find($tenantId);

            if (!$tenant) {
                return response()->json(['error' => 'Tenant not found'], 404);
            }

            // Dynamically set the tenant's database connection
            $this->setTenantConnection($tenant);

        }

        return $next($request);
    }

    /**
     * Set the tenant database connection dynamically.
     *
     * @param Tenant $tenant
     * @return void
     */
    private function setTenantConnection(Tenant $tenant): void
    {

        // Configure the tenant's database connection dynamically
        config([
            'database.connections.tenant' => [
                'driver'   => 'mysql',
                'host'     => '127.0.0.1',//env('DB_HOST', '127.0.0.1'),
                'port'     => '3306',//env('DB_PORT', '3306'),
                'database' => $tenant->database,
                'username' => 'root',//env('DB_USERNAME', 'root'),
                'password' => ''//env('DB_PASSWORD', ''),
            ],
        ]);

        // Set the default connection to the tenant
        DB::purge('tenant'); // Clear any cached connections
        DB::setDefaultConnection('tenant');

    }
}
