<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;

class SettingsController extends Controller
{
    /**
     * Display the settings page.
     */
    public function index()
    {
        // Get current settings
        $settings = [
            'app_name' => config('app.name'),
            'app_url' => config('app.url'),
            'app_timezone' => config('app.timezone'),
            'app_locale' => config('app.locale'),
            'mail_from_address' => config('mail.from.address'),
            'mail_from_name' => config('mail.from.name'),
            'pagination_limit' => config('app.pagination_limit', 10),
            'default_role' => config('app.default_role', 'student'),
        ];
        
        // Pass the data to the view
        return view('admin.settings.index', [
            'settings' => $settings
        ]);
    }

    /**
     * Update the settings.
     */
    public function update(Request $request)
    {
        // Validate the request data
        $request->validate([
            'app_name' => 'required|string|max:255',
            'app_url' => 'required|url',
            'app_timezone' => 'required|string',
            'app_locale' => 'required|string',
            'mail_from_address' => 'required|email',
            'mail_from_name' => 'required|string|max:255',
            'pagination_limit' => 'required|integer|min:5|max:100',
            'default_role' => 'required|string|in:admin,teacher,student',
        ]);

        try {
            // Update the .env file
            $this->updateEnvFile([
                'APP_NAME' => $request->app_name,
                'APP_URL' => $request->app_url,
                'APP_TIMEZONE' => $request->app_timezone,
                'APP_LOCALE' => $request->app_locale,
                'MAIL_FROM_ADDRESS' => $request->mail_from_address,
                'MAIL_FROM_NAME' => $request->mail_from_name,
                'PAGINATION_LIMIT' => $request->pagination_limit,
                'DEFAULT_ROLE' => $request->default_role,
            ]);

            // Redirect back with success message
            return redirect()->route('admin.settings')->with('success', 'Settings updated successfully');
        } catch (\Exception $e) {
            // Redirect back with error message
            return redirect()->route('admin.settings')->with('error', 'Failed to update settings: ' . $e->getMessage());
        }
    }

    /**
     * Update the .env file with the given data.
     */
    private function updateEnvFile(array $data)
    {
        $envPath = base_path('.env');
        
        if (File::exists($envPath)) {
            $envContent = File::get($envPath);
            
            foreach ($data as $key => $value) {
                // If the key exists in the .env file, replace its value
                if (strpos($envContent, $key . '=') !== false) {
                    $envContent = preg_replace('/^' . $key . '=.*$/m', $key . '=' . $this->formatEnvValue($value), $envContent);
                } else {
                    // If the key doesn't exist, add it to the end of the file
                    $envContent .= "\n" . $key . '=' . $this->formatEnvValue($value);
                }
            }
            
            File::put($envPath, $envContent);
        }
    }

    /**
     * Format the value for the .env file.
     */
    private function formatEnvValue($value)
    {
        // If the value contains spaces or special characters, wrap it in quotes
        if (preg_match('/\s|[^A-Za-z0-9_]/', $value)) {
            return '"' . str_replace('"', '\"', $value) . '"';
        }
        
        return $value;
    }
}
