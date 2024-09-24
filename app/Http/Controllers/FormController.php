<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Form;

class FormController extends Controller
{
    public function store(Request $request)
    {
      // Identify the form type from the request
      $formName = $request->input('form_name');
  
      // Set up dynamic validation rules based on form type
      $rules = $this->getValidationRules($formName);
  
      // Validate the request data
      $validator = \Validator::make($request->all(), $rules);
  
      if ($validator->fails()) {
          return response()->json(['errors' => $validator->errors()], 422);
      }
  
      $data = $validator->validated();
    
        // Handle file uploads if present
        $uploadedFiles = $this->handleFileUploads($request);
    
        // Create a new form entry in the database
        $form = Form::create([
            'form_name' => $formName,
            'user_name' => $data['user_name'] ?? null,
            'company_name' => $data['company_name'] ?? null,
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'po_number' => $data['po_number'] ?? null,
            'due_date' => $data['due_date'] ?? null,
            'subject' => $data['subject'] ?? null,
            'project_description' => $data['project_description'] ?? null,
            'file_description' => $data['file_description'] ?? null,
            'project_details' => $data['project_details'] ?? null,
            'website_url' => $data['website_url'] ?? null,
            'message' => $data['message'] ?? null,
            'career_option' => $data['career_option'] ?? null,
            'file_path' => !empty($uploadedFiles) ? $uploadedFiles : null, // Store as an array
        ]);
    
        return response()->json(['message' => "{$formName} form submitted successfully!"], 200);
    }  

    protected $casts = [
      'file_path' => 'array',
    ];

    // Function to return validation rules based on form type
    protected function getValidationRules($formName)
    {
        $rules = [
            'email' => 'required|email|max:255',
        ];

        switch ($formName) {
            case 'Contact':
                $rules['user_name'] = 'required|string|min:2|max:255';
                $rules['phone'] = 'nullable|string|max:15';
                $rules['message'] = 'required|string|min:10|max:2000';
                break;

            case 'Quote':
                $rules['user_name'] = 'required|string|min:2|max:255';
                $rules['company_name'] = 'nullable|string|max:255';
                $rules['phone'] = 'nullable|string|max:15';
                $rules['project_description'] = 'required|string|min:10|max:1000';
                $rules['project_details'] = 'nullable|string|max:2000';
                $rules['files.*'] = 'nullable|file|max:10240'; // 10MB max per file
                break;

            case 'Order':
                $rules['user_name'] = 'required|string|min:2|max:255';
                $rules['company_name'] = 'nullable|string|max:255';
                $rules['phone'] = 'nullable|string|max:15';
                $rules['po_number'] = 'required|string|max:50';
                $rules['due_date'] = 'required|date';
                $rules['project_description'] = 'required|string|min:10|max:1000';
                $rules['project_details'] = 'nullable|string|max:2000';
                break;
              
            case 'Upload':
                $rules['user_name'] = 'required|string|min:2|max:255';
                $rules['company_name'] = 'nullable|string|max:255';
                $rules['phone'] = 'nullable|string|max:15';
                $rules['file_description'] = 'nullable|string|max:1000';
                $rules['files.*'] = 'nullable|file|max:10240'; // 10MB max per file
                break;
            
            case 'Careers':
                $rules['user_name'] = 'required|string|min:2|max:255';
                $rules['email'] = 'required|email|max:255';
                $rules['phone'] = 'nullable|string|max:15';
                $rules['career_option'] = 'required|string|in:Vinyl Graphic Installer,Sales Rep,Sales Professional';
                $rules['files.*'] = 'nullable|file|max:10240'; // 10MB max per file
                break;

            default:
                $rules['user_name'] = 'required|string|min:2|max:255';
                break;
        }

        return $rules;
    }

    // Function to handle file uploads
    protected function handleFileUploads(Request $request)
    {
        $uploadedFiles = [];
        
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                // Store file with a more descriptive name
                $filename = uniqid() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('form_files', $filename, 'public'); // Store in 'public' disk
                $uploadedFiles[] = $path;
            }
        }
        
        return $uploadedFiles;
    }
}
