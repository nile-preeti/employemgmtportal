<?php

namespace App\Imports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;


class UsersImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
       // dd($row);
        $row = array_change_key_case(array_map('trim', $row), CASE_LOWER); 
        
        // Skip if emp_id or email already exists
        if (User::where('emp_id', $row['emp_id'])->orWhere('email', $row['email'])->exists()) {
            return null;
        }

        return new User([
            'name'        => $row['name'],
            'email'       => $row['email'],
            'designation' => $row['designation'],
            'emp_id'      => $row['emp_id'],
            'phone'       => $row['phone'],
            'rep_manager' =>$row['reporting_manager'],
            'role_id'      => 2,
            'status'      => 1, // Default status = 1 (Active)
            'password'    => bcrypt('123456') // Set a default password
        ]);
    }
}

