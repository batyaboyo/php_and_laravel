<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MemberController extends Controller
{
    // Display a paginated list of library members.
    public function index()
    {
        $members = User::query()
            ->withCount(['borrowRecords as active_borrows_count' => function ($query) {
                $query->whereNull('returned_date');
            }])
            ->latest()
            ->paginate(10);

        return view('members.index', compact('members'));
    }

    // Display a specific member's profile and full borrow history.
    public function show(User $member)
    {
        $borrowRecords = $member->borrowRecords()
            ->with('book')
            ->latest('borrowed_date')
            ->get();

        return view('members.show', compact('member', 'borrowRecords'));
    }

    // Show form to edit member details.

    public function edit(User $member)
    {
        return view('members.edit', compact('member'));
    }

    // Update member details.
     
    public function update(Request $request, User $member)
    {
        $validated = $request->validate([
            'name'      => ['required', 'string', 'max:255'],
            'email'     => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($member->id)],
            'max_books' => ['required', 'integer', 'min:1'],
        ]);

        $member->update($validated);

        return redirect()->route('members.index')->with('success', 'Member details updated successfully.');
    }

    // Suspend a member.
    
    public function suspend(User $member)
    {
        // Check if member has unreturned books with an unpaid fine > 0
        $hasUnreturnedBookWithFine = $member->borrowRecords()
            ->whereNull('returned_date')
            ->where('fine', '>', 0)
            ->exists();

        if ($hasUnreturnedBookWithFine) {
            return back()->with('error', 'Cannot suspend member: Member has unreturned books with unpaid fines.');
        }

        $member->update(['membership_status' => 'suspended']);

        return back()->with('success', 'Member account suspended successfully.');
    }

    // Activate a member.
     
    public function activate(User $member)
    {
        $member->update(['membership_status' => 'active']);

        return back()->with('success', 'Member account activated successfully.');
    }

    // Promote a member to admin.
     
    public function makeAdmin(User $member)
    {
        if ($member->role === 'admin') {
            return back()->with('error', 'This user is already an admin.');
        }

        $member->update(['role' => 'admin']);

        return back()->with('success', "{$member->name} has been promoted to admin successfully.");
    }

    // Remove a user/member from the system.
    public function destroy(User $member)
    {
        if ($member->id === auth()->id()) {
            return back()->with('error', 'You cannot remove your own admin account.');
        }

        $hasActiveBorrows = $member->borrowRecords()
            ->whereNull('returned_date')
            ->exists();

        if ($hasActiveBorrows) {
            return back()->with('error', 'Cannot remove user: User currently has active borrowed books that must be returned first.');
        }

        $memberName = $member->name;
        $member->borrowRecords()->delete();
        $member->delete();

        return redirect()->route('members.index')->with('success', "User '{$memberName}' has been removed successfully.");
    }
}
