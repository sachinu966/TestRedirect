<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RedirectionController extends Controller
{
    public function storeRedirectionData(Request $request)
{
    dd($request);
    // Handle form submission and generate the JavaScript script
    $mainUrl = $request->input('mainUrl');
    $redirectUrl1 = $request->input('redirectUrl1');
    $redirectUrl2 = $request->input('redirectUrl2');

    // Get the user's IP address
    $userIpAddress = $request->ip();

    // Create the JavaScript script
    $script = "
        // Send a request back to the server to record the visit based on IP address
        var xhr = new XMLHttpRequest();
        xhr.open('POST', '{$mainUrl}/record-visit', true);
        xhr.setRequestHeader('Content-Type', 'application/json');
        xhr.send(JSON.stringify({ ip_address: '{$userIpAddress}' }));

        // Redirect logic based on first-time or repeat visit
        if (localStorage.getItem('visitedBefore')) {
            window.location.href = '{$redirectUrl2}';
        } else {
            localStorage.setItem('visitedBefore', 'true');
            window.location.href = '{$redirectUrl1}';
        }
    ";

    // Return the script in the response
    return response()->json(['script' => $script, 'userIpAddress' => $userIpAddress]);
}

}
