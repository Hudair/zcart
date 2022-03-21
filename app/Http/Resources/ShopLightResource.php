<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ShopLightResource extends JsonResource
{
    /**
     * @var feedback_given
     */
    private $feedback_id;

    /**
     * Create a new resource instance.
     *
     * @param  mixed  $resource
     * @return void
     */
    public function __construct($resource, $feedback_id = Null)
    {
        // Ensure you call the parent constructor
        parent::__construct($resource);
        $this->resource = $resource;

        $this->feedback_id = $feedback_id;
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'verified' => $this->isVerified(),
            'verified_text' => $this->verifiedText(),
            'rating' => $this->rating(),
            'image' => get_logo_url($this, 'small'),
            // 'can_evaluate' => $this->when($request->is('api/order/*'), ! (bool) $this->feedback_id),
            'feedback' => $this->when($request->is('api/order/*'), function() {
                $feedback = \App\Feedback::find($this->feedback_id);

                return $feedback ? new FeedbackResource($feedback) : Null;
            }),
        ];
    }
}