<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class MemberController extends Controller
{
    public function index()
    {
        $members = Member::withCount('borrowRecords')->latest()->paginate(10);

        return view('members.index', compact('members'));
    }

    public function create()
    {
        return view('members.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:members,email'],
            'phone' => ['nullable', 'string', 'max:255'],
            'membership_date' => ['nullable', 'date'],
            'status' => ['required', 'in:active,suspended'],
        ]);

        $data = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'status' => $validated['status'],
        ];

        if ($this->memberHasMembershipDateColumn()) {
            $data['membership_date'] = $validated['membership_date'] ?? now()->toDateString();
        }

        Member::create($data);

        return redirect('/members')->with('success', 'Member added successfully.');
    }

    public function show(Member $member)
    {
        $member->load(['borrowRecords' => function ($query) {
            $query->with('book')->latest('borrowed_date');
        }]);

        return view('members.show', compact('member'));
    }

    public function edit(Member $member)
    {
        return view('members.edit', compact('member'));
    }

    public function update(Request $request, Member $member)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:members,email,' . $member->id],
            'phone' => ['nullable', 'string', 'max:255'],
            'membership_date' => ['nullable', 'date'],
            'status' => ['required', 'in:active,suspended'],
        ]);

        $data = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'status' => $validated['status'],
        ];

        if ($this->memberHasMembershipDateColumn()) {
            $data['membership_date'] = $validated['membership_date'] ?? optional($member->membership_date)->toDateString() ?? now()->toDateString();
        }

        $member->update($data);

        return redirect('/members')->with('success', 'Member updated successfully.');
    }

    protected function memberHasMembershipDateColumn(): bool
    {
        try {
            return Schema::hasColumn('members', 'membership_date');
        } catch (\Throwable $e) {
            return false;
        }
    }

    public function destroy(Member $member)
    {
        $member->delete();

        return redirect('/members')->with('success', 'Member deleted successfully.');
    }
}
