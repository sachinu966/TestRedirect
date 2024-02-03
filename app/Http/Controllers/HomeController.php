<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\IpAddress;
use App\Models\Redirect;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function storeRedirectionData(Request $request)
    {
        // Handle form submission and generate the JavaScript script
        $mainUrl = $request->input('main_url');
        $redirectUrl1 = $request->input('redirection_url_1');
        $redirectUrl2 = $request->input('redirection_url_2');
        
        Redirect::updateOrCreate(
            [
                'main_url' => $mainUrl,
            ],
            [
                'main_url' => $mainUrl,
                'redirection_url_1' => $redirectUrl1,
                'redirection_url_2' => $redirectUrl2
            ]);
    
        // Dynamically generate the base URL for the script
        $baseUrl = url('/');
        
        // Generate the script URL
        $scriptUrl = "{$baseUrl}/generate-redirection-script";
    
        // Return the script URL in the response
        return response($scriptUrl);
    }
    

    public function generateRedirectionScript(Request $request)
    {
        // Get the user's IP address
        $userIpAddress = $request->ip();
        
        // Get the base URL of the third party dynamically
        $baseUrl = $request->getSchemeAndHttpHost();
        
        // Get redirection URLs from the database
        $data = Redirect::where('main_url', $baseUrl)->first();
        
        // Get the main URL, redirection URLs
        $redirectUrl1 = $data->redirection_url_1; // Change this to your actual first redirection URL
        $redirectUrl2 = $data->redirection_url_2; // Change this to your actual second redirection URL
        $userVisitedBefore = IpAddress::where('user_ip', $userIpAddress)->exists();

        // Check if the user's IP exists in the database
        $script = "
        // Script for redirection
        if (!{$userVisitedBefore}) {
            // First-time visit
            window.location.href = '{$redirectUrl1}';
        } else {
            // Repeat visit
            window.location.href = '{$redirectUrl2}';
        }
    ";

    // Return the script in the response
    return response($script)->header('Content-Type', 'application/javascript');

    }
    
    

    public function handleRedirection(Request $request)
    {
        // Get the user's IP address
        $userIpAddress = $request->ip();
        // Check if the user's IP is already in the database
        $userVisit = IpAddress::where('user_ip', $userIpAddress)->first();

        if (!$userVisit) {
            // If the user is visiting for the first time, store the IP in the database
            IpAddress::create(['user_ip' => $userIpAddress]);

            // Redirect to the first URL
            return redirect('https://www.google.com');
        } else {
            // Redirect to the second URL
            return redirect('https://www.yahoo.com');
        }
    }

}
