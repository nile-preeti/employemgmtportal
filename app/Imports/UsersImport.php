<?php

namespace App\Imports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Mail\EmployeeCredentialsMail;
use Illuminate\Support\Facades\Mail;


class UsersImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // Convert keys to lowercase and trim values
        $row = array_change_key_case(array_map('trim', $row), CASE_LOWER); 
        
        // Skip if emp_id or email already exists
        if (User::where('emp_id', $row['emp_id'])->orWhere('email', $row['email'])->exists()) {
            return null;
        }

        // Generate a default password
        $password = '123456';
        
        // Create a new user instance
        $user = new User([
            'name'        => $row['name'],
            'email'       => $row['email'],
            'designation' => $row['designation'],
            'emp_id'      => $row['emp_id'],
            'phone'       => $row['phone'],
            'rep_manager' => $row['reporting_manager'],
            'role_id'     => 2,
            'status'      => 1, // Default status = 1 (Active)
            'password'    => bcrypt($password) // Hash password
        ]);

        // Save the user to the database
        $user->save();

        // Send email with credentials
        //Mail::to($user->email)->send(new EmployeeCredentialsMail($user, $password));

        return $user;
    }
}

