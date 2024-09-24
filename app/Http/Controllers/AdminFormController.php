<?php

namespace App\Http\Controllers;

use App\Models\Form;
use Illuminate\Http\Request;

class AdminFormController extends Controller
{
  public function index()
  {
      // Retrieve all form submissions from the database
      $forms = Form::all();
      
      // Return the data as a JSON response
      return response()->json($forms);
  }

  public function store(Request $request)
    {
        // Validate the request data
        $validated = $request->validate([
            'form_name' => 'required|string|max:255',
            'user_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            // Add other validation rules as needed
        ]);

        // Create a new form submission
        $form = Form::create($validated);

        // Return the created form as a JSON response
        return response()->json($form, 201);
    }

    public function update(Request $request, $id)
    {
        // Find the form submission by ID
        $form = Form::find($id);

        if (!$form) {
            return response()->json(['message' => 'Form submission not found.'], 404);
        }

        // Validate the request data
        $validated = $request->validate([
            'form_name' => 'sometimes|required|string|max:255',
            'user_name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|max:255',
            // Add other validation rules as needed
        ]);

        // Update the form submission
        $form->update($validated);

        // Return the updated form as a JSON response
        return response()->json($form, 200);
    }

    public function destroy($id)
    {
        $form = Form::find($id);
    
        if ($form) {
            // Delete associated files if they exist
            if (is_array($form->file_path) && !empty($form->file_path)) {
                foreach ($form->file_path as $file) {
                    \Storage::disk('public')->delete($file);
                }
            }
    
            // Delete the form record
            $form->delete();
    
            return response()->json(['message' => 'Form submission and associated files deleted successfully.'], 200);
        } else {
            return response()->json(['message' => 'Form submission not found.'], 404);
        }
    }
}