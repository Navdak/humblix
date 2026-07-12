<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index() { return view('admin.reviews.index', ['reviews' => Review::latest()->paginate(15)]); }

    public function update(Request $request, Review $review): RedirectResponse
    {
        $data = $request->validate(['is_approved' => ['nullable','boolean'], 'admin_response' => ['nullable','string','max:1000']]);
        $data['is_approved'] = (bool) $request->boolean('is_approved');
        $review->update($data);
        return back()->with('success','Review updated.');
    }

    public function destroy(Review $review): RedirectResponse
    {
        $review->delete();
        return back()->with('success','Review deleted.');
    }
}
