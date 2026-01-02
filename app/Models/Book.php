<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;
    protected $guarded = []; // <--- Make sure this is here
    
    public function copies() {
        return $this->hasMany(BookCopy::class);
    }
    // Update User
public function updateUser(Request $request, $id)
{
    $request->validate([
        'name' => 'required',
        'email' => 'required|email'
    ]);

    $user = User::findOrFail($id);
    $user->update([
        'name' => $request->name,
        'email' => $request->email
    ]);

    return back()->with('success', 'Member details updated.');
}

// Delete User
public function deleteUser($id)
{
    $user = User::findOrFail($id);
    
    // Optional: Prevent deleting yourself
    if (auth()->id() == $user->id) {
        return back()->with('error', 'You cannot delete your own account.');
    }

    $user->delete();

    return back()->with('success', 'Member removed from system.');
}
}
