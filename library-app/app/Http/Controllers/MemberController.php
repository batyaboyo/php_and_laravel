<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MemberController extends Controller
{
    /**
     * Display a paginated list of library members.
     */
    public function index()
    {
        $members = User::query()
            ->where('role', 'member')
            ->withCount(['borrowRecords as active_borrows_count' => function ($query) {
                $query->whereNull('returned_date');
            }])
            ->latest()
            ->paginate(10);

        return view('members.index', compact('members'));
    }

    /**
     * Display a specific member's profile and full borrow history.
     */
    public function show(User $member)
    {
        if ($member->role !== 'member') {
            abort(404);
        }

        $borrowRecords = $member->borrowRecords()
            ->with('book')
            ->latest('borrowed_date')
            ->get();

        return view('members.show', compact('member', 'borrowRecords'));
    }

    /**
     * Show form to edit member details.
     */
    public function edit(User $member)
    {
        if ($member->role !== 'member') {
            abort(404);
        }

        return view('members.edit', compact('member'));
    }

    /**
     * Update member details.
     */
    public function update(Request $request, User $member)
    {
        if ($member->role !== 'member') {
            abort(404);
        }

        $validated = $request->validate([
            'name'      => ['required', 'string', 'max:255'],
            'email'     => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($member->id)],
            'max_books' => ['required', 'integer', 'min:1'],
        ]);

        $member->update($validated);

        return redirect()->route('members.index')->with('success', 'Member details updated successfully.');
    }

    /**
     * Suspend a member.
     */
    public function suspend(User $member)
    {
        if ($member->role !== 'member') {
            abort(404);
        }

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

    /**
     * Activate a member.
     */
    public function activate(User $member)
    {
        if ($member->role !== 'member') {
            abort(404);
        }

        $member->update(['membership_status' => 'active']);

        return back()->with('success', 'Member account activated successfully.');
    }
}
