<?php

namespace App\Http\Controllers;

use App\Review;
use App\Product;
use Illuminate\Http\Request;
use App\Http\Requests\StoreReviewRequest;

class ReviewController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\StoreReviewRequest  $request
     * @param \App\Product  $product
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function store(StoreReviewRequest $request, Product $product)
    {
        $review = $product->reviews()->create([
            'name' => $request->name,
            'description' => $request->description,
            'grade' => $request->grade,
            'title' => $request->title,
            'email' => $request->email
        ]);

        $product->refreshReviews();

        return $review;
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Product $product)
    {
        if (! $request->ajax()) {
            abort(404);
        }

        $query = $product->reviews();

        if (strlen($request->input('searchText')) > 0) {
            $query = $query->where('description', 'like', '%' . trim($request->input('searchText')) . '%');
        }

        switch ($request->input('sortBy')) {

            case 'popular-reviews':
                $query = $query->orderBy('grade', 'desc');
                break;
            
            default:
                $query = $query->orderBy('id', 'desc');
                break;
        }

        return $query->paginate(config('default-variables.pagination'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Review  $review
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Review $review)
    {
        $request->validate([
            'action' => ['required', 'in:like,dislike,report'],
        ]);

        switch ($request->input('action')) {
            
            case 'like':
                $review->increment('like_counter');
                break;

            case 'dislike':
                $review->increment('dislike_counter');
                break;

            case 'report':
                $review->increment('report_counter');
                break;

            default:
                abort(500);
                break;
        }

        return $review;
    }
}
